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

namespace Britannia\Infraestructure\Symfony\Admin\CourseReport;


use Britannia\Application\UseCase\CourseReport\CourseReportCommandInterface;
use Britannia\Domain\Entity\Course\Course;
use DomainException;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;

final class CourseReportMapper extends AdminMapper
{

    protected function className(): string
    {
        return Course::class;
    }

    protected function create(array $values): object
    {
        throw new DomainException('Este formulario no debe usarse para crear Cursos');
    }

    protected function update($object, array $values): CourseReportCommandInterface
    {
        return $this->chooseCommand($values);
    }

    /**
     * @param CourseReportCommandInterface[] $values
     * @return CourseReportCommandInterface
     */
    protected function chooseCommand(array $values): CourseReportCommandInterface
    {
        $tab = $values['tab'] ?? 1;
        unset($values['tab']);

        $command = collect($values)
            ->values()
            ->get($tab - 1);

        return $command;
    }
}
