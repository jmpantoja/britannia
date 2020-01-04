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

namespace Britannia\Infraestructure\Symfony\Admin\Student;


use Britannia\Domain\Entity\Student\Adult;
use Britannia\Domain\Entity\Student\Child;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentDto;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;

final class StudentMapper extends AdminMapper
{

    /**
     * @var bool
     */
    private bool $adult;

    protected function className(): string
    {
        return Student::class;
    }

    public function setAdult(bool $adult): self
    {
        $this->adult = $adult;
        return $this;
    }

    protected function create(array $values): Student
    {
        $dto = $this->makeDto($values);

        if ($this->adult) {
            return Adult::make($dto);
        }

        return Child::make($dto);
    }

    /**
     * @param Student $student
     * @param array $values
     */
    protected function update($student, array $values)
    {
        $dto = $this->makeDto($values);
        $student->update($dto);
    }

    /**
     * @param array $values
     * @return StudentDto
     */
    protected function makeDto(array $values): StudentDto
    {

        $dto = StudentDto::fromArray($values);
        return $dto;
    }
}
