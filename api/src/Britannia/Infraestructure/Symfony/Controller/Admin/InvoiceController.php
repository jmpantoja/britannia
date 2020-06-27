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
use Britannia\Application\UseCase\Invoice\GenerateSepaXlsx;
use Britannia\Domain\Entity\Invoice\Invoice;
use Britannia\Domain\Service\Report\ReportEmailSender;
use Britannia\Domain\VO\Message\EmailPurpose;
use Britannia\Infraestructure\Symfony\Controller\Admin\Report\DownloadFactory;
use Britannia\Infraestructure\Symfony\Form\Type\Invoice\MonthType;
use Britannia\Infraestructure\Symfony\Form\Type\Invoice\YearType;
use Carbon\CarbonImmutable;
use League\Tactician\CommandBus;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Request;

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

    public function downloadSepaAction(Request $request)
    {
        $form = $this->createForm(MonthType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $month = CarbonImmutable::createFromDate($data['year'], $data['month'], 1);

            $command = GenerateSepaXlsx::byMonth($month);
            $reportList = $this->commandBus->handle($command);

            return $this->downloadFactory
                ->create($reportList, self::DEBUG_MODE);
        }

        $formView = $form->createView();
        $this->setFormTheme($formView, $this->admin->getFormTheme());
        return $this->renderWithExtraParams('admin/invoice/invoice_controller_sepa_action.html.twig', [
            'form' => $formView,
        ]);



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


    /**
     * Sets the admin form theme to form view. Used for compatibility between Symfony versions.
     */
    private function setFormTheme(FormView $formView, ?array $theme = null): void
    {
        $twig = $this->get('twig');

        $twig->getRuntime(FormRenderer::class)->setTheme($formView, $theme);
    }

}
