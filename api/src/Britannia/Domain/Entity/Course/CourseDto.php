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
use Britannia\Domain\VO\Course\Age\Age;
use Britannia\Domain\VO\Course\Periodicity\Periodicity;
use Britannia\Domain\VO\Course\Support\Support;
use Britannia\Domain\VO\Course\TimeTable\TimeTable;
use Britannia\Domain\VO\Discount\JobStatusDiscountList;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\Model\Dto;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Price;
use PlanB\DDD\Domain\VO\RGBA;

abstract class CourseDto extends Dto
{

    public int $oldId;

    public ?string $name;

    public ?string $description;

    public RGBA $color;

    public ?PositiveInteger $numOfPlaces;

    public ?Price $monthlyPayment;

    public ?Price $enrollmentPayment;

    public StaffList $teachers;

    public StudentList $courseHasStudents;

    public ?Collection $books = null;

    public ?TimeTable $timeTable;

    public LessonGenerator $lessonCreator;

    public ?JobStatusDiscountList $discount = null;


    protected function defaults(): array
    {
        $color = $this->randomColor();
        return [
            'color' => $color
        ];
    }

    private function randomColor(): RGBA
    {

        return collect([
            RGBA::make(232, 229, 152),
            RGBA::make(237, 177, 45),
            RGBA::make(204, 28, 75),
            RGBA::make(171, 87, 164),
            RGBA::make(101, 48, 64),
            RGBA::make(40, 175, 169),
            RGBA::make(136, 149, 70),
            RGBA::make(64, 128, 191),
            RGBA::make(191, 128, 64),
        ])
            ->shuffle()
            ->first();
    }


}
