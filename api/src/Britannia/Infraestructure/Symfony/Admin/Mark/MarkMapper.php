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

namespace Britannia\Infraestructure\Symfony\Admin\Mark;


use Britannia\Domain\Entity\Assessment\TermList;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Unit\Unit;
use Britannia\Domain\Entity\Unit\UnitStudent;
use Britannia\Domain\Entity\Unit\UnitStudentList;
use Britannia\Domain\Repository\StudentRepositoryInterface;
use Britannia\Domain\Repository\UnitRepositoryInterface;
use DomainException;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;

final class MarkMapper extends AdminMapper
{

    /**
     * @var StudentRepositoryInterface
     */
    private StudentRepositoryInterface $studentRepository;
    /**
     * @var UnitRepositoryInterface
     */
    private UnitRepositoryInterface $unitRepository;

    protected function className(): string
    {
        return Course::class;
    }


    protected function create(array $values): object
    {
        throw new DomainException('Este formulario no debe usarse para crear Cursos');
    }

    /**
     * @param Course $course
     * @param array $values
     * @return Course
     */
    protected function update($course, array $values): Course
    {
        $termList = $this->buildTermList($values);

        return $course->setTerms($termList);

    }

    /**
     * @param array $values
     * @return TermList
     */
    protected function buildTermList(array $values): TermList
    {
        $values = array_values($values);
        $data = collect($values)
            ->map(fn(TermList $termList) => $termList->toArray())
            ->toArray();

        $input = array_merge(...$data);
        $termList = TermList::collect($input);
        return $termList;
    }
}
