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

namespace Britannia\Infraestructure\Symfony\Admin\Report;


use Britannia\Application\UseCase\Report\ReportCommandInterface;
use Britannia\Domain\Entity\Course\Course;
use DomainException;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;

final class ReportMapper extends AdminMapper
{

    protected function className(): string
    {
        return Course::class;
    }

    protected function create(array $values): object
    {
        throw new DomainException('Este formulario no debe usarse para crear Cursos');
    }

    protected function update($object, array $values): ReportCommandInterface
    {
        return $this->chooseCommand($values);
    }

    /**
     * @param ReportCommandInterface[] $values
     * @return ReportCommandInterface
     */
    protected function chooseCommand(array $values): ReportCommandInterface
    {
        $tab = $values['tab'] ?? 1;
        unset($values['tab']);

        return collect($values)
            ->values()
            ->get($tab - 1);
    }
}
