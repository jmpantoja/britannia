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

namespace Britannia\Infraestructure\Symfony\Admin\School;


use Britannia\Domain\Entity\School\School;
use Britannia\Domain\Entity\School\SchoolDto;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;

final class SchoolMapper extends AdminMapper
{

    protected function className(): string
    {
        return School::class;

    }

    protected function create(array $values): School
    {
        $dto = SchoolDto::fromArray($values);
        return School::make($dto);
    }

    /**
     * @param School $school
     * @param array $values
     * @return School
     */
    protected function update($school, array $values)
    {
        $dto = SchoolDto::fromArray($values);
        $school->update($dto);
    }
}
