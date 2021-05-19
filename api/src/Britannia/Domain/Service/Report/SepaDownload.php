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
use Britannia\Domain\Entity\Invoice\InvoiceList;
use Britannia\Domain\Entity\Setting\Setting;
use Britannia\Domain\VO\BankAccount\BankAccount;

final class SepaDownload extends TemplateBasedXlsxReport
{

    /**
     * @var InvoiceList
     */
    private InvoiceList $invoiceList;
    /**
     * @var Setting
     */
    private Setting $setting;

    public static function make(string $name, InvoiceList $invoices, Setting $setting): self
    {
        return new self($name, $invoices, $setting);
    }

    private function __construct(string $title, InvoiceList $invoiceList, Setting $setting)
    {
        parent::__construct($title);

        $this->invoiceList = $invoiceList;
        $this->setting = $setting;

        $values = $this->calculeRows($invoiceList);
        $this->setValue(1, 'A12', $values);

    }

    public function params(): array
    {
        return [
            'settings' => $this->setting,
            'sheets' => [
                1 => [
                    'A7' => $this->calculeHead(),
                    'A12' => $this->calculeRows()
                ]
            ]
        ];
    }


    /**
     * @param InvoiceList $invoiceList
     * @return array
     */
    private function calculeRows(): array
    {
        $values = [];
        foreach ($this->invoiceList as $invoice) {
            $values[] = $this->calculeRow($invoice);
        }
        return $values;
    }

    private function calculeHead(): array
    {
        return [
            $this->setting->sepaPresenterId(),
            $this->setting->sepaPresenterName(),
            $this->setting->sepaBbvaOffice(),
            $this->setting->sepaCreditorId(),
            null,
            $this->setting->sepaCreditorName(),
            $this->setting->sepaCreditorIban(),
            $this->getFormule(),
            'EUR'
        ];
    }

    /**
     * @param Invoice $invoice
     * @return array
     */
    private function calculeRow(Invoice $invoice): array
    {
        $bankAccount = $this->getBankAccount($invoice);

        return [
            $bankAccount->getTitular(),
            $bankAccount->getIban()->getPrintedFormat(),
            '???',
            '???',
            $this->calculeCode($invoice),
            '???',
            $invoice->total(),
            $invoice->subject()
        ];
    }

    /**
     * @param Invoice $invoice
     * @return mixed
     */
    private function getBankAccount(Invoice $invoice): BankAccount
    {
        return $invoice->student()->payment()->getAccount();
    }

    private function calculeCode(Invoice $invoice): string
    {
        return '????';
    }

    private function getFormule(): string
    {
        $count = $this->invoiceList->count();
        return sprintf('=SUM(G12:G%s)', 12 + $count);
    }

}
