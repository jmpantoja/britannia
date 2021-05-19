<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Controller\Admin;

use Britannia\Application\UseCase\StudentReport\GenerateRegistrationForm;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Repository\StudentRepositoryInterface;
use Britannia\Domain\Service\Report\ReportList;
use Britannia\Infraestructure\Symfony\Controller\Admin\Report\DownloadFactory;
use League\Tactician\CommandBus;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class StudentReportController extends AbstractController
{
    private const DEBUG_MODE = false;

    /**
     * @var StudentRepositoryInterface
     */
    private StudentRepositoryInterface $repository;

    /**
     * @var CommandBus
     */
    private CommandBus $commandBus;

    /**
     * @var DownloadFactory
     */
    private DownloadFactory $downloadFactory;


    public function __construct(StudentRepositoryInterface $repository,
                                CommandBus $commandBus,
                                DownloadFactory $downloadFactory)
    {

        $this->repository = $repository;

        $this->commandBus = $commandBus;
        $this->downloadFactory = $downloadFactory;


    }

    public function registration(Request $request)
    {

        $student = $this->getStudent($request);
        $command = GenerateRegistrationForm::make($student);
        $reportList = $this->commandBus->handle($command);

        return $this->buildResponse($reportList);
    }

    private function getStudent(Request $request): Student
    {
        return $this->repository->find($request->get('id'));
    }

    private function buildResponse(ReportList $reportList): Response
    {
        return $this->downloadFactory
            ->create($reportList, self::DEBUG_MODE);
    }

}
