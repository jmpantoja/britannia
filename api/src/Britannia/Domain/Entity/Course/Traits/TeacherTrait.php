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

namespace Britannia\Domain\Entity\Course\Traits;


use Britannia\Domain\Entity\Course\CourseDto;
use Britannia\Domain\Entity\Staff\StaffList;
use Britannia\Domain\Entity\Staff\StaffMember;
use Doctrine\Common\Collections\ArrayCollection;

trait TeacherTrait
{
    /**
     * @var StaffList
     */
    private $teachers;

    public function updateTeachers(CourseDto $dto){


        if (isset($dto->teachers)) {
            $this->setTeachers($dto->teachers);
        }
    }

    public function setTeachers(StaffList $teachers)
    {
        $this->teachersList()
            ->forRemovedItems($teachers, [$this, 'removeTeacher'])
            ->forAddedItems($teachers, [$this, 'addTeacher']);
    }

    public function removeTeacher(StaffMember $member): self
    {
        $this->teachersList()
            ->remove($member, fn(StaffMember $member) => $member->removeCourse($this));

        return $this;
    }

    public function addTeacher(StaffMember $member)
    {
        $this->teachersList()
            ->add($member, fn(StaffMember $member) => $member->addCourse($this));

        return $this;
    }

    /**
     * @return StaffMember[]
     */
    public function teachers(): array
    {
        return $this->teachersList()->toArray();
    }

    public function mainTeacher(): ?StaffMember
    {
        return $this->teachersList()->values()->first();
    }

    /**
     * @return StaffList
     */
    private function teachersList(): StaffList
    {
        return StaffList::collect($this->teachers);
    }

}
