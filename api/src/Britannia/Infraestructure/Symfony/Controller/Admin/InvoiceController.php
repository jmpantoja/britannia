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

namespace Britannia\Infraestructure\Symfony\Controller\Admin;


use Britannia\Application\UseCase\Invoice\GenerateInvoicePdf;
use Britannia\Domain\Entity\Invoice\Invoice;
use Britannia\Domain\Service\Report\ReportEmailSender;
use Britannia\Domain\VO\Message\EmailPurpose;
use Britannia\Infraestructure\Symfony\Controller\Admin\Report\DownloadFactory;
use League\Tactician\CommandBus;
use Sonata\AdminBundle\Controller\CRUDController;

class InvoiceController extends CRUDController
{
    const DEBUG_MODE = false;
    /**
     * @var CommandBus
     */
    private CommandBus $commandBus;
    /**
     * @var DownloadFactory
     */
    private DownloadFactory $downloadFactory;
    /**
     * @var ReportEmailSender
     */
    private ReportEmailSender $emailSender;

    public function __construct(CommandBus $commandBus,
                                DownloadFactory $downloadFactory,
                                ReportEmailSender $emailSender
    )
    {
        $this->commandBus = $commandBus;
        $this->downloadFactory = $downloadFactory;
        $this->emailSender = $emailSender;
    }

    public function downloadPdfAction()
    {
        $invoice = $this->getInvoice();
        $command = GenerateInvoicePdf::complete($invoice);
        $reportList = $this->commandBus->handle($command);

        return $this->downloadFactory
            ->create($reportList, self::DEBUG_MODE);
    }

    public function sendEmailAction()
    {
        $invoice = $this->getInvoice();
        $command = GenerateInvoicePdf::onlyStudent($invoice);
        $reportList = $this->commandBus->handle($command);

        $this->emailSender->send($invoice->student(), $reportList, EmailPurpose::SEND_INVOICE());

        $this->addFlash('sonata_flash_success', 'Se ha enviado el recibo por correo');
        return $this->redirectToList();
    }

    private function getInvoice(): Invoice
    {
        $id = $this->getRequest()->get('id');

        return $this->getDoctrine()
            ->getRepository(Invoice::class)
            ->find($id);
    }


}
