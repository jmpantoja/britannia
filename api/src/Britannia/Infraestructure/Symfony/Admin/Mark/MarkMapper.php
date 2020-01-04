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


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Unit\Unit;
use Britannia\Domain\Entity\Unit\UnitStudent;
use Britannia\Domain\Entity\Unit\UnitStudentList;
use Britannia\Domain\Repository\StudentRepositoryInterface;
use Britannia\Domain\Repository\UnitRepositoryInterface;
use Britannia\Domain\Service\Mark\MarkCalculator;
use Britannia\Domain\VO\Mark\SetOfSkills;
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
    /**
     * @var MarkCalculator
     */
    private MarkCalculator $calculator;

    public function __construct(StudentRepositoryInterface $studentRepository,
                                UnitRepositoryInterface $unitRepository)
    {
        $this->studentRepository = $studentRepository;
        $this->unitRepository = $unitRepository;

        parent::__construct();
    }

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
     */
    protected function update($course, array $values)
    {
        $data = [];
        $skills = $course->evaluableSkills();

        foreach ($values as $term) {
            $data[] = $this->makeUnitStudentListByTerm($skills, $term);
        }

        $data = array_merge(...$data);
        $unitStudentList = UnitStudentList::collect($data);

        $course->updateMarks($unitStudentList);

    }

    private function makeUnitStudentListByTerm(SetOfSkills $skills, array $term)
    {
        $data = [];
        foreach ($term as $studentId => $units) {
            $student = $this->studentRepository->find($studentId);

            $data[] = $this->makeUnitStudentListByUnits($student, $skills, $units);
        }

        return array_merge(...$data);
    }

    private function makeUnitStudentListByUnits(Student $student, SetOfSkills $skills, array $units)
    {
        $data = [];
        foreach ($units as $unitId => $marks) {
            /** @var Unit $unit */
            $unit = $this->unitRepository->find($unitId);

            $data[] = UnitStudent::make($student, $unit, $marks);
        }
        return $data;
    }


}
