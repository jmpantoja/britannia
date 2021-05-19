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


use Britannia\Domain\Entity\Course\Traits\BookTrait;
use Britannia\Domain\Entity\Course\Traits\CourseTrait;
use Britannia\Domain\Entity\Course\Traits\LessonTrait;
use Britannia\Domain\Entity\Course\Traits\StudentTrait;
use Britannia\Domain\Entity\Course\Traits\TeacherTrait;
use Britannia\Domain\Entity\Course\Traits\TimeRangeTrait;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;


abstract class Course implements Comparable, CoursePaymentInterface
{

    use CourseTrait;
    use AggregateRootTrait;
    use ComparableTrait;
    use TeacherTrait;
    use StudentTrait;
    use TimeRangeTrait;
    use LessonTrait;
    use BookTrait;

    //  use RecordTrait;

    /**
     * @var CarbonImmutable
     */
    private $createdAt;

    /**
     * @var CarbonImmutable
     */
    private $updatedAt;


    public static function make(CourseDto $dto): self
    {
        return new static($dto);
    }

    protected function __construct(CourseDto $dto)
    {
        $this->id = new CourseId();
        $this->books = new ArrayCollection();
        $this->lessons = new ArrayCollection();
        $this->records = new ArrayCollection();
        $this->teachers = new ArrayCollection();
        $this->courseHasStudents = new ArrayCollection();

        $this->createdAt = CarbonImmutable::now();


        $this->update($dto);
    }

    public function update(CourseDto $dto): self
    {

        $this->updateCourse($dto);
        $this->updateTeachers($dto);
        $this->updateStudents($dto);

        $this->updateBooks($dto);

        $this->updatedAt = CarbonImmutable::now();

        return $this;
    }

    /**
     * @return CourseId
     */
    public function id(): ?CourseId
    {
        return $this->id;
    }

}
