<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Britannia\Domain\Service\Invoice;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\Course\OneToOne;
use Britannia\Domain\Entity\Course\MonthlyPaymentInterface;
use Britannia\Domain\Entity\Course\Pass\Pass;
use Britannia\Domain\Entity\Course\SinglePaymentInterface;
use Britannia\Domain\Entity\Invoice\Invoice;
use Britannia\Domain\Entity\Invoice\InvoiceDetail;
use Britannia\Domain\Entity\Invoice\InvoiceDetailList;
use Britannia\Domain\Entity\Invoice\InvoiceDto;
use Britannia\Domain\Entity\Invoice\InvoiceList;
use Britannia\Domain\Entity\Setting\Setting;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Repository\InvoiceRepositoryInterface;
use Britannia\Domain\Service\Payment\Concept;
use Britannia\Domain\Service\Payment\Discount\BoundariesCalculator;
use Britannia\Domain\Service\Payment\PaymentBreakdownService;
use Britannia\Domain\Service\Payment\StudentDiscountGenerator;
use Britannia\Domain\VO\Course\Pass\PassPriceList;
use Britannia\Domain\VO\Discount\StudentDiscount;
use Britannia\Domain\VO\Payment\PaymentMode;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\RefundPrice;

final class InvoiceGenerator
{
    /**
     * @var InvoiceRepositoryInterface
     */
    private InvoiceRepositoryInterface $invoiceRepository;
    /**
     * @var PaymentBreakdownService
     */
    private PaymentBreakdownService $breakdownService;
    /**
     * @var StudentDiscountGenerator
     */
    private StudentDiscountGenerator $discountCalculator;
    /**
     * @var BoundariesCalculator
     */
    private BoundariesCalculator $boundariesCalculator;
    /**
     * @var PassPriceList|null
     */
    private PassPriceList $passPriceList;

    /**
     * InvoiceGenerator constructor.
     */
    public function __construct(InvoiceRepositoryInterface $invoiceRepository,
                                PaymentBreakdownService    $breakdownService,
                                StudentDiscountGenerator   $discountCalculator,
                                BoundariesCalculator       $boundariesCalculator,
                                Setting                    $setting)
    {
        $this->invoiceRepository = $invoiceRepository;
        $this->breakdownService = $breakdownService;
        $this->discountCalculator = $discountCalculator;
        $this->boundariesCalculator = $boundariesCalculator;

        $this->passPriceList = $setting->passPriceList();
    }

    public function generate(Student $student, CarbonImmutable $date): ?Invoice
    {
        if ($this->exists($student, $date)) {
            return null;
        }

        return $this->createInvoice($student, $date);
    }

    public function update(Student $student, CarbonImmutable $date, Course $course): InvoiceList
    {

        $invoices = [];
        $startDate = $course->start();
        $endDate = $course->end();

        $current = $date;

        while ($current->isBefore($endDate)) {
            $invoices[] = $this->updateInvoice($student, $course, $current);
            $current = $current->setDay(1)->addMonthNoOverflow();
        }

        return InvoiceList::collect(array_filter($invoices));
    }


    private function exists(Student $student, CarbonImmutable $date): bool
    {
        return $this->invoiceRepository->existsByStudentAndMonth($student, $date);
    }

    /**
     * @param Student $student
     * @param CarbonImmutable $date
     * @param Course|null $course
     * @return Invoice
     */
    private function createInvoice(Student $student, CarbonImmutable $date, ?Course $course = null): Invoice
    {
        $dto = $this->makeDto($student, $date, $course);
        return Invoice::make($dto);
    }

    /**
     * @param Student $student
     * @param CarbonImmutable $date
     * @param Course|null $course
     * @return InvoiceDto
     */
    private function makeDto(Student $student, CarbonImmutable $date, Course $course = null): InvoiceDto
    {
        $paymentMode = $student->payment()->getMode();

        $dto = InvoiceDto::fromArray([
            'student' => $student,
            'subject' => $this->getSubject($date),
            'createdAt' => CarbonImmutable::now(),
            'expiredAt' => $this->getExpiredDate($date, $paymentMode),
            'mode' => $paymentMode,
            'details' => $this->getDetails($student, $date, $course),
        ]);

        return $dto;
    }


    private function findInvoice(Student $student, CarbonImmutable $date): ?Invoice
    {
        return $this->invoiceRepository->findUnPaidByStudentAndMonth($student, $date);
    }

    /**
     * @param CarbonImmutable $date
     * @return string
     */
    private function getSubject(CarbonImmutable $date): string
    {
        $formatted = date_to_string($date, -1, -1, "MMMM 'de' Y");

        return sprintf('Mensualidad %s ', $formatted);
    }

    private function getExpiredDate(CarbonImmutable $date, PaymentMode $paymentMode): ?CarbonImmutable
    {
        if ($paymentMode->isCash()) {
            return $date->setDay(1);
        }

        $dayNumber = $paymentMode->getDayNumber();
        return $date->setDay($dayNumber);
    }

    private function getDetails(Student $student, CarbonImmutable $date, ?Course $onlyThisCourse = null): InvoiceDetailList
    {
        $temp = [];

        $courseList = [$onlyThisCourse];
        if (is_null($onlyThisCourse)) {
            $courseList = $student->activeCourses();
        }

        foreach ($courseList as $course) {
            $temp[] = $this->getDetailsFromCourse($student, $course, $date);
        }

        $details = array_merge(...$temp);

        return InvoiceDetailList::collect($details)
            ->removeWithoutPrice();
    }

    private function getDetailsFromCourse(Student $student, Course $course, CarbonImmutable $date): array
    {
        if ($course instanceof OneToOne) {
            return $this->getDetailsFromOneToOne($course, $date);
        }

        if ($course instanceof MonthlyPaymentInterface) {
            $discount = $this->discountCalculator->generate($student, $course);
            return $this->getDetailsFromMonthlyPayment($course, $discount, $date);
        }


        if ($course instanceof SinglePaymentInterface) {
            $discount = $this->discountCalculator->generate($student, $course);

            return $this->getDetailsFromSinglePayment($student, $course, $discount, $date);
        }

        throw new \Exception('Tipo de curso no reconocido');
    }

    private function getDetailsFromOneToOne(OneToOne $course, CarbonImmutable $date): array
    {
        $details = [];
        foreach ($course->passesInMonth($date) as $pass) {
            $details[] = $this->oneToOnePass($pass);
        }
        return $details;
    }

    private function oneToOnePass(Pass $pass): InvoiceDetail
    {
        $passHours = $pass->hours();
        $price = $this->passPriceList->getByPassHours($passHours);

        return InvoiceDetail::fromArray([
            'subject' => sprintf('Bono de %s', $passHours->getValue()),
            'numOfUnits' => PositiveInteger::make(1),
            'discount' => Percent::zero(),
            'price' => RefundPrice::fromPrice($price),
        ]);
    }

    private function getDetailsFromMonthlyPayment(MonthlyPaymentInterface $course, StudentDiscount $discount, CarbonImmutable $date): array
    {
        $details = [];

        if ($this->isFirstMonth($course, $discount, $date)) {
            $details[] = $this->enrollment($course, $discount);
            $details[] = $this->material($course, $discount);
            $details[] = $this->firstMonth($course, $discount, $date);
            $details[] = $this->lastMonth($course, $discount, $course->end());

            return $details;
        }

        if (!$this->isLastMonth($course, $date)) {
            $details[] = $this->currentMonth($course, $discount, $date);
        }

        return $details;
    }

    private function getDetailsFromSinglePayment(Student $student, SinglePaymentInterface $course, StudentDiscount $discount, CarbonImmutable $date): array
    {
        $details = [];
        $studentCourse = $course->courseHasSingleStudent($student);
        $isSinglePaid = $studentCourse->isSinglePaid();

        if ($this->isFirstMonth($course, $discount, $date) && $isSinglePaid) {
            $details[] = $this->singlePaid($course, $discount);
            return $details;
        }

        if ($isSinglePaid) {
            return $details;
        }

        if ($this->isFirstMonth($course, $discount, $date)) {
            $details[] = $this->firstPaid($course, $discount);
            return $details;
        }

        $details[] = $this->secondPaid($course, $discount);

        return $details;
    }

    private function enrollment(Course $course, StudentDiscount $discount): InvoiceDetail
    {
        $concept = $this->breakdownService->calculeEnrollment($course, $discount);
        $subject = sprintf('%s. Matrícula', $course->name());

        return $this->detailByConcept($concept, $subject);
    }

    private function material(Course $course, StudentDiscount $discount): InvoiceDetail
    {
        $concept = $this->breakdownService->calculeMaterial($course, $discount);
        $subject = sprintf('%s. Material curso', $course->name());

        return $this->detailByConcept($concept, $subject);
    }


    private function firstMonth(Course $course, StudentDiscount $discount, CarbonImmutable $date): InvoiceDetail
    {
        $concept = $this->breakdownService->calculeMonthly($course, $discount, $date);

        $formatted = date_to_string($date, -1, -1, "MMMM 'de' Y");
        $subject = sprintf('%s. primera mensualidad (%s)', $course->name(), $formatted);

        return $this->detailByConcept($concept, $subject);
    }

    private function lastMonth(Course $course, StudentDiscount $discount, CarbonImmutable $date): InvoiceDetail
    {
        $concept = $this->breakdownService->calculeMonthly($course, $discount, $date);

        $formatted = date_to_string($date, -1, -1, "MMMM 'de' Y");
        $subject = sprintf('%s. última mensualidad (%s)', $course->name(), $formatted);

        return $this->detailByConcept($concept, $subject);
    }

    private function currentMonth(Course $course, StudentDiscount $discount, CarbonImmutable $date): InvoiceDetail
    {
        $concept = $this->breakdownService->calculeMonthly($course, $discount, $date);

        $formatted = date_to_string($date, -1, -1, "MMMM 'de' Y");
        $subject = sprintf('%s. mensualidad %s', $course->name(), $formatted);

        return $this->detailByConcept($concept, $subject);
    }

    private function singlePaid(Course $course, StudentDiscount $discount): InvoiceDetail
    {

        $concept = $this->breakdownService->uniquePaid($course, $discount);
        $subject = sprintf('%s. Pago único', $course->name());

        return $this->detailByConcept($concept, $subject);
    }

    private function firstPaid(Course $course, StudentDiscount $discount): InvoiceDetail
    {
        $concept = $this->breakdownService->firstPaid($course, $discount);
        $subject = sprintf('%s. Primer pago', $course->name());

        return $this->detailByConcept($concept, $subject);
    }

    private function secondPaid(Course $course, StudentDiscount $discount): InvoiceDetail
    {
        $concept = $this->breakdownService->secondPaid($course, $discount);
        $subject = sprintf('%s. Segundo pago', $course->name());

        return $this->detailByConcept($concept, $subject);
    }

    private function detailByConcept(Concept $concept, string $subject): InvoiceDetail
    {
        return InvoiceDetail::fromArray([
            'subject' => $subject,
            'numOfUnits' => PositiveInteger::make(1),
            'discount' => $concept->getDiscount(),
            'price' => RefundPrice::fromPrice($concept->getPrice()),
        ]);

    }

    /**
     * @param Course $course
     * @param StudentDiscount $discount
     * @param CarbonImmutable $date
     * @return bool
     */
    private function isFirstMonth(Course $course, StudentDiscount $discount, CarbonImmutable $date): bool
    {
        return $this->boundariesCalculator->isFirstMonth($course, $discount, $date);
    }

    private function isLastMonth(MonthlyPaymentInterface $course, CarbonImmutable $date)
    {
        return $this->boundariesCalculator->isLastMonth($course, $date);
    }

    /**
     * @param Student $student
     * @param Course $course
     * @param CarbonImmutable $date
     * @return Invoice
     */
    private function updateInvoice(Student $student, Course $course, CarbonImmutable $date): ?Invoice
    {
        if ($date->isBefore($course->start()->setDay(1))) {
            return null;
        }


        $invoice = $this->findInvoice($student, $date);

        if (!($invoice instanceof Invoice)) {
            return $this->createInvoice($student, $date, $course);
        }

        $dto = $this->makeDto($student, $date);
        return $invoice->update($dto);
    }


}
