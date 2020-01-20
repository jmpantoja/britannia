<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Controller\Admin;

use Britannia\Domain\Service\Report\ReportList;
use Britannia\Infraestructure\Symfony\Controller\Download\DownloadFactory;
use League\Tactician\CommandBus;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;

final class ReportController extends CRUDController
{
    private const DEBUG_MODE = true;

    /**
     * @var CommandBus
     */
    private CommandBus $commandBus;

    /**
     * @var DownloadFactory
     */
    private DownloadFactory $downloadFactory;

    public function __construct(CommandBus $commandBus, DownloadFactory $downloadFactory)
    {
        $this->commandBus = $commandBus;
        $this->downloadFactory = $downloadFactory;
    }

    protected function redirectTo($object)
    {
        $report = $this->commandBus->handle($object);
        return $this->buildResponse($report);
    }

    private function buildResponse(ReportList $reportList): Response
    {
        return $this->downloadFactory
            ->create($reportList, self::DEBUG_MODE);
    }
}
