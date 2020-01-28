<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Controller\Admin;

use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Service\Report\ReportList;
use Britannia\Domain\VO\Assessment\CourseTerm;
use Britannia\Domain\VO\Assessment\TermName;
use Britannia\Infraestructure\Symfony\Controller\Admin\Report\DownloadFactory;
use Carbon\CarbonImmutable;
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


    public function rangeAction()
    {
        $course = $this->getCourse();
        $termName = $this->getTermName();

        $courseTerm = CourseTerm::make($course, $termName);

        $firstDay = $courseTerm->start();
        $lastDay = $courseTerm->end();

        $start = date_to_string($firstDay);
        $end = $lastDay instanceof CarbonImmutable ? date_to_string($lastDay) : null;

        return $this->renderJson([
            'start' => $start,
            'end' => $end,
        ]);
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

    private function getCourse(): Course
    {
        $id = $this->getRequest()->get('id');

        return $this->getDoctrine()
            ->getRepository(Course::class)
            ->find($id);
    }

    /**
     * @return mixed
     */
    private function getTermName(): TermName
    {
        $termName = $this->getRequest()->get('term');
        if (empty($termName)) {
            return TermName::FIRST_TERM();
        }
        return TermName::byName($termName);
    }
}
