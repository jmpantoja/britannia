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

namespace Britannia\Infraestructure\Symfony\Admin\SchoolCourse;


use Britannia\Domain\Entity\School\School;
use Britannia\Domain\Entity\School\SchoolDto;
use Britannia\Domain\Entity\SchoolCourse\SchoolCourse;
use Britannia\Domain\Entity\SchoolCourse\SchoolCourseDto;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;

final class SchoolCourseMapper extends AdminMapper
{

    protected function className(): string
    {
        return SchoolCourse::class;

    }

    protected function create(array $values): SchoolCourse
    {
        $dto = SchoolCourseDto::fromArray($values);
        return SchoolCourse::make($dto);
    }

    /**
     * @param School $school
     * @param array $values
     * @return School
     */
    protected function update($school, array $values): SchoolCourse
    {
        $dto = SchoolCourseDto::fromArray($values);
        return $school->update($dto);
    }
}
