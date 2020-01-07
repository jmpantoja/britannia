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

namespace Britannia\Domain\Entity\Course;


use Britannia\Domain\Entity\Lesson\UpdateCalendarOrder;
use Britannia\Domain\Entity\Staff\StaffList;
use Britannia\Domain\Entity\Student\StudentList;
use Britannia\Domain\Service\Course\LessonGenerator;
use Britannia\Domain\Service\Course\AssessmentGenerator;
use Britannia\Domain\VO\Course\Age\Age;
use Britannia\Domain\VO\Course\Examiner\Examiner;
use Britannia\Domain\VO\Course\Intensive\Intensive;
use Britannia\Domain\VO\Course\Periodicity\Periodicity;
use Britannia\Domain\VO\Course\Support\Support;
use Britannia\Domain\VO\Course\TimeTable\TimeTable;
use Britannia\Domain\VO\Mark\AssessmentDefinition;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\Model\Dto;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Price;
use PlanB\DDD\Domain\VO\RGBA;

class CourseDto extends Dto
{

    public int $oldId;

    public ?string $name;

    public ?string  $schoolCourse;

    public RGBA $color;

    public ?Examiner $examiner = null;

    public ?Level $level = null;

    public ?PositiveInteger $numOfPlaces;

    public ?Periodicity $periodicity;

    public Support $support;

    public ?Age $age;

    public ?Intensive $intensive;

    public ?Price $monthlyPayment;

    public ?Price $enrollmentPayment;

    public StaffList $teachers;

    public StudentList $courseHasStudents;

    public ?Collection $books;

    public ?TimeTable $timeTable;

    public LessonGenerator $lessonCreator;

    public ?Collection $discount = null;

    public AssessmentDefinition $assessmentDefinition;

    public AssessmentGenerator $assessmentGenerator;

    protected function defaults(): array
    {
        return [
            'support' => Support::REGULAR()
        ];
    }


}
