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


use Britannia\Domain\Service\Report\ReportList;
use Symfony\Component\HttpFoundation\Response;

final class DownloadFactory
{

    /**
     * @var DebugDownload
     */
    private DebugDownload $debug;
    /**
     * @var PdfDownload
     */
    private PdfDownload $pdf;
    /**
     * @var ZipDownload
     */
    private ZipDownload $zip;

    public function __construct(DebugDownload $debug, PdfDownload $pdf, ZipDownload $zip)
    {

        $this->debug = $debug;
        $this->pdf = $pdf;
        $this->zip = $zip;
    }

    public function create(ReportList $reportList, bool $debug): Response
    {
        if ($debug) {
            return $this->debug->createResponse($reportList);
        }

        if(1 === $reportList->count()){
            return $this->pdf->createResponse($reportList);
        }

        return $this->zip->createResponse($reportList);
    }

}
