<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Controller\Admin;

use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Service\Report\ReportList;
use Britannia\Domain\VO\Assessment\CourseTerm;
use Britannia\Domain\VO\Assessment\TermName;
use Britannia\Infraestructure\Symfony\Controller\Admin\Report\DownloadFactory;
use Britannia\Infraestructure\Symfony\Service\Course\BoundariesInformation;
use Carbon\CarbonImmutable;
use League\Tactician\CommandBus;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Response;

final class CourseReportController extends CRUDController
{
    private const DEBUG_MODE = false;

    /**
     * @var CommandBus
     */
    private CommandBus $commandBus;

    /**
     * @var DownloadFactory
     */
    private DownloadFactory $downloadFactory;
    /**
     * @var BoundariesInformation
     */
    private BoundariesInformation $boundaries;

    public function __construct(CommandBus $commandBus,
                                DownloadFactory $downloadFactory,
                                BoundariesInformation $boundaries)
    {
        $this->commandBus = $commandBus;
        $this->downloadFactory = $downloadFactory;
        $this->boundaries = $boundaries;
    }

    public function boundariesPricesAction()
    {
        $course = $this->getCourse();
        $date = $this->getStartDate();

        $data = $this->boundaries->values($course, $date);

        return $this->renderJson($data);
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

    private function getStartDate(): ?CarbonImmutable
    {
        $startDate = $this->getRequest()->get('startDate');
        if (empty($startDate)) {
            return null;
        }
        return string_to_date($startDate);
    }
}
