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

namespace Britannia\Application\UseCase\Invoice;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Invoice\Invoice;
use Britannia\Domain\Entity\Invoice\InvoiceList;
use Britannia\Domain\Service\Invoice\InvoiceGenerator;
use PlanB\DDD\Application\UseCase\UseCaseInterface;
use PlanB\DDDBundle\ApiPlattform\DataPersister;

final class CreateInvoiceUseCase implements UseCaseInterface
{
    /**
     * @var DataPersister
     */
    private DataPersisterInterface $dataPersister;
    /**
     * @var InvoiceGenerator
     */
    private InvoiceGenerator $generator;


    /**
     * CreateInvoiceUseCase constructor.
     */
    public function __construct(DataPersisterInterface $dataPersister, InvoiceGenerator $generator)
    {
        $this->dataPersister = $dataPersister;
        $this->generator = $generator;
    }

    public function handle(CreateInvoice $command)
    {
        /*
         * Cuando un alumno se une a un curso,
         * pueden pasar varias cosas:
         * 1. no tiene recibos previos de eses mes, se crea y palante
         * 2. tiene un recibo previo de ese mes
         *      2.1 Aun no está pagado. Se actualiza
         *      2.2 Ya está pagado. Se crea otro solo con el curso nuevo
         *
         * Para hacer esto, necesito un flag en CreateInvoice $command
         */

        /** @var InvoiceList $invoiceList */
        $invoiceList = $this->getInvoiceList($command);

        $invoiceList
            ->withValueEqualToZero()
            ->values()
            ->each(function (Invoice $invoice){
                $this->dataPersister->remove($invoice);
            });


        $invoiceList
            ->withValueGreaterThanZero()
            ->values()
            ->each(function (Invoice $invoice){
                $this->dataPersister->persist($invoice);
            });

//        $invoiceList->values()
//            ->each(function (Invoice $invoice) {
//                $this->dataPersister->persist($invoice);
//            });

    }

    /**
     * @param CreateInvoice $command
     * @return Invoice|null
     */
    private function getInvoiceList(CreateInvoice $command): InvoiceList
    {
        $student = $command->student();
        $course = $command->course();
        $date = $command->date();

        if ($course instanceof Course) {
            return $this->generator->update($student, $date, $course);
        }

        return $this->generator->generate($student, $date);
    }
}
