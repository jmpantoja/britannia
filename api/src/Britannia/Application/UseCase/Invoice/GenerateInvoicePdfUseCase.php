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


use Britannia\Domain\Entity\Setting\Setting;
use Britannia\Domain\Service\Report\InvoiceDownload;
use Britannia\Domain\Service\Report\ReportList;
use PlanB\DDD\Application\UseCase\UseCaseInterface;

final class GenerateInvoicePdfUseCase implements UseCaseInterface
{
    /**
     * @var Setting
     */
    private Setting $setting;

    public function __construct(Setting $setting)
    {
        $this->setting = $setting;
    }

    public function handle(GenerateInvoicePdf $command)
    {
        $invoice = $command->invoice();
        $includeCopyForTheAcademy = $command->includeCopyForTheAcademy();

        $invoice->markAsPaid();

        return ReportList::make($invoice->subject(), [
            InvoiceDownload::make($invoice, $includeCopyForTheAcademy, $this->setting)
        ]);

    }
}
