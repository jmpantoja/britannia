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

namespace Britannia\Application\UseCase\CourseReport;


use Britannia\Domain\Entity\Setting\Setting;
use Britannia\Domain\Service\Report\CourseInformation;
use Britannia\Domain\Service\Report\CourseInformationParamsGenerator;
use Britannia\Domain\Service\Report\ReportList;
use PlanB\DDD\Application\UseCase\UseCaseInterface;

class GenerateCourseInformationUseCase implements UseCaseInterface
{
    /**
     * @var CourseInformationParamsGenerator
     */
    private CourseInformationParamsGenerator $generator;
    /**
     * @var Setting
     */
    private Setting $setting;

    public function __construct(CourseInformationParamsGenerator $paramsGenerator, Setting $setting)
    {
        $this->generator = $paramsGenerator;
        $this->setting = $setting;
    }

    public function handle(GenerateCourseInformation $command)
    {
        $course = $command->course();
        $discount = $command->discount();
        $singlePaid = $command->isSinglePaid();

        return ReportList::make($course->name(), [
            CourseInformation::make($course, $discount, $this->generator, $singlePaid, $this->setting)
        ]);

    }

}
