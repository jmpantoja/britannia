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
     * @var FileDownload
     */
    private FileDownload $download;

    public function __construct(DebugDownload $debug, FileDownload $download)
    {

        $this->debug = $debug;
        $this->download = $download;
    }

    public function create(ReportList $reportList, bool $debug): Response
    {
        if ($debug) {
            return $this->debug->createResponse($reportList);
        }

        return $this->download->createResponse($reportList);
    }

}
