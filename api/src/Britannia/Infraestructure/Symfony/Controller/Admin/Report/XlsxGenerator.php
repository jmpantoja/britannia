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

namespace Britannia\Infraestructure\Symfony\Controller\Admin\Report;


use Britannia\Domain\Service\Report\TemplateBasedXlsxReport;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx as Reader;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx as Writer;

final class XlsxGenerator extends AbstractTemplateFillerOut
{
    /**
     * @var XlsxReader
     */
    private XlsxReader $reader;
    /**
     * @var XlsxWriter
     */
    private XlsxWriter $writer;

    public function create(TemplateBasedXlsxReport $report, string $pathToTemplatesDir, string $pathToTempDir): string
    {
        $pathToTemplate = $this->getPathToTemplate($report, $pathToTemplatesDir);
        $target = $this->getTarget($report, $pathToTempDir);

        $sheets = $this->getSheets($report);
        $document = $this->load($pathToTemplate);

        $this->setValues($document, $sheets);
        $this->save($document, $target);

        return $target;
    }

    /**
     * @param TemplateBasedXlsxReport $report
     * @return array|mixed
     */
    private function getSheets(TemplateBasedXlsxReport $report)
    {
        $params = $report->params();
        $sheets = $params['sheets'] ?? [];
        return $sheets;
    }

    /**
     * @param string $pathToTemplate
     * @return Spreadsheet
     */
    private function load(string $pathToTemplate): Spreadsheet
    {
        $reader = new Reader();
        $document = $reader->load($pathToTemplate);
        return $document;
    }

    /**
     * @param Spreadsheet $document
     * @param array $sheets
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    private function setValues(Spreadsheet $document, array $sheets): void
    {
        foreach ($sheets as $index => $cells) {
            $workSheet = $document->getSheet($index);
            $this->setValuesOnWorkSheet($workSheet, $cells);
        }
    }

    /**
     * @param Worksheet $workSheet
     * @param $cells
     */
    private function setValuesOnWorkSheet(Worksheet $workSheet, $cells): void
    {
        foreach ($cells as $cell => $values) {
            $workSheet->fromArray($values, null, $cell);
        }
    }

    /**
     * @param Spreadsheet $document
     * @param string $target
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    private function save(Spreadsheet $document, string $target): void
    {
        $writer = new Writer($document);
        $writer->save($target);
    }


}
