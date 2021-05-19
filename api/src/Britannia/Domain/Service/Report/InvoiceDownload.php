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

namespace Britannia\Domain\Service\Report;


use Britannia\Domain\Entity\Invoice\Invoice;
use Britannia\Domain\Entity\Setting\Setting;
use Cocur\Slugify\Slugify;

final class InvoiceDownload extends HtmlBasedPdfReport
{

    public static function make(Invoice $invoice, bool $includeCopyForTheAcademy, Setting $setting): self
    {
        $params = [
            'date' => $invoice->paymentDate(),
            'includeCopyForTheAcademy' => $includeCopyForTheAcademy,
            'invoice' => $invoice,
            'setting' => $setting
        ];

        $options = [
            'page-size' => 'A5',
            'orientation' => 'Portrait',
        ];

        $slug = Slugify::create();
        $name = sprintf('recibo-%s-%s', ...[
            $slug->slugify($invoice->student()),
            date_to_string($invoice->createdAt(), -1, -1, "MMMM-YYYY")
        ]);

        return new self($name, $params, $options);
    }

    private function __construct(string $title, array $params = [], array $options = [])
    {
        parent::__construct($title, $params, $options);
    }
}
