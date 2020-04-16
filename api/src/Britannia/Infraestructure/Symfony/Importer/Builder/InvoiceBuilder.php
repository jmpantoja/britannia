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

namespace Britannia\Infraestructure\Symfony\Importer\Builder;


use Britannia\Domain\Entity\Invoice\Invoice;
use Britannia\Domain\Entity\Invoice\InvoiceDetail;
use Britannia\Domain\Entity\Invoice\InvoiceDetailDto;
use Britannia\Domain\Entity\Invoice\InvoiceDetailList;
use Britannia\Domain\Entity\Invoice\InvoiceDto;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Payment\PaymentMode;
use Britannia\Infraestructure\Symfony\Importer\Builder\Traits\StudentMaker;
use Britannia\Infraestructure\Symfony\Importer\Maker\FullNameMaker;
use Britannia\Infraestructure\Symfony\Importer\Resume;
use Carbon\CarbonImmutable;
use Exception;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Price;
use PlanB\DDD\Domain\VO\RefundPrice;

class InvoiceBuilder extends BuilderAbstract
{
    use StudentMaker;

    private const TYPE = 'Invoice';
    private const MAX_SUBJECT_LENGTH = 50;
    /**
     * @var object|null
     */
    private ?object $student;
    /**
     * @var string
     */
    private string $subject;
    /**
     * @var CarbonImmutable|null
     */
    private ?CarbonImmutable $createdAt;
    /**
     * @var CarbonImmutable|null
     */
    private ?CarbonImmutable $expiredAt;
    /**
     * @var string
     */
    private PaymentMode $type;

    private Price $total;
    /**
     * @var bool
     */
    private bool $status;
    /**
     * @var array
     */
    private $details = [];

    public function initResume(array $input): Resume
    {
        $title = (string)$input['student'];
        return Resume::make((int)$input['id'], self::TYPE, $title);
    }


    public function withStudent(int $studentId): self
    {
        $this->student = $this->findOneOrNull(Student::class, [
            'oldId' => $studentId
        ]);

        return $this;
    }

    public function withSubject(string $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    public function withCreatedAt(?string $createdAt, ?string $paidAt): self
    {
        $date = $createdAt ?? $paidAt;
        $this->createdAt = CarbonImmutable::make($date);
        return $this;
    }

    public function withExpiredAt(?string $expiredAt): self
    {
        if (is_null($expiredAt)) {
            $this->expiredAt = null;
        }

        $this->expiredAt = CarbonImmutable::make($expiredAt);
        return $this;
    }

    public function withType(string $type): self
    {
        if ('Domicialización bancaria (1)' === $type) {
            $this->type = PaymentMode::DAY_1();
            return $this;
        }

        if ('Domicialización bancaria (10)' === $type) {
            $this->type = PaymentMode::DAY_10();
            return $this;
        }

        if ('efectivo' === $type) {
            $this->type = PaymentMode::CASH();
            return $this;
        }

        throw new Exception("cosas raras");
        return $this;
    }

    public function withTotal(?string $total): self
    {

        if (Price::isValid((float)$total)) {
            $this->total = Price::make((float)$total);
        }

        return $this;
    }

    public function withStatus(?string $status): self
    {
        if ('No pagada' === $status) {
            $this->status = false;
            return $this;
        }

        if ('1' === $status) {
            $this->status = false;
            return $this;
        }

        if ('2' === $status) {
            $this->status = true;
            return $this;
        }

        if (is_null($status)) {
            $this->status = false;
            return $this;
        }

        throw new Exception("cosas raras");
        return $this;
    }


    public function build(): ?object
    {
        if (empty($this->student) or !isset($this->total)) {
            return null;
        }
        if (empty($this->details)) {
            $this->withDetail($this->subject, "1", "0", (string)$this->total);
        }

        $paymentDate = $this->caculePaymentDate();

        $input = [
            'student' => $this->student,
            'subject' => $this->subject,
            'createdAt' => $this->createdAt,
            'expiredAt' => $this->expiredAt,
            'total' => $this->total,
            'mode' => $this->type,
            'paid'=>$this->status,
            'paidAt' => $paymentDate,
            'details' => InvoiceDetailList::collect($this->details)
        ];

        $dto = InvoiceDto::fromArray($input);
        return Invoice::make($dto);
    }

    private function caculePaymentDate(): ?CarbonImmutable
    {
        if ($this->status === false) {
            return null;
        }

        return $this->expiredAt ?? $this->createdAt;
    }

    public function withDetail(?string $concepto, ?string $unidades, ?string $descuento, ?string $importe): self
    {
        if (empty($concepto)) {
            return $this;
        }

        $importe = (float)$importe;
        if (!Price::isValid($importe)) {
            return $this;
        }

        $unidades = (float)$unidades;
        if (!PositiveInteger::isValid($unidades)) {
            $unidades = 1;
        }

        $dto = InvoiceDetailDto::fromArray([
            'subject' => $concepto,
            'numOfUnits' => PositiveInteger::make((int)$unidades),
            'discount' => Percent::make((int)$descuento),
            'price' => RefundPrice::make((float)$importe),
        ]);

        $this->details[] = InvoiceDetail::make($dto);
        return $this;
    }

}
