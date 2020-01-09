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

namespace Britannia\Infraestructure\Symfony\Controller\Download;


use Britannia\Domain\Service\Report\ReportList;
use Symfony\Component\HttpFoundation\Response;

final class DebugDownload extends Download
{
    public function createResponse(ReportList $reportList): Response
    {
        $html = '';
        foreach ($reportList as $report) {
            $html .= $this->renderReport($report);
        }

        return new Response($html, 200);
    }
}
