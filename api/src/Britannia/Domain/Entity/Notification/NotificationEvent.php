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

namespace Britannia\Domain\Entity\Notification;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\Student;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Event\DomainEvent;

abstract class NotificationEvent extends DomainEvent implements NotificationEventInterface
{
    protected ?Student $student;
    protected ?Course $course;
    protected CarbonImmutable $date;
    protected TypeOfNotification $type;
    protected string $subject;
    protected ?string $message = '';


    private function __construct(Student $student, Course $course)
    {
        $this->withStudent($student)
            ->withCourse($course)
            ->withDate(CarbonImmutable::today())
            ->withType($this->type());
    }

    protected static function builder(Student $student, Course $course): self
    {
        return new static($student, $course);
    }

    /**
     * @param Student|null $student
     * @return NotificationEvent
     */
    public function withStudent(?Student $student): NotificationEvent
    {
        $this->student = $student;
        return $this;
    }

    /**
     * @param Course|null $course
     * @return NotificationEvent
     */
    public function withCourse(?Course $course): NotificationEvent
    {
        $this->course = $course;
        return $this;
    }


    public function withDate(CarbonImmutable $date): NotificationEvent
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @param TypeOfNotification $type
     * @return NotificationEvent
     */
    public function withType(TypeOfNotification $type): NotificationEvent
    {
        $this->type = $type;
        return $this;
    }

    /**
     * @param string $subject
     * @return NotificationEvent
     */
    public function withSubject(string $subject): NotificationEvent
    {
        $this->subject = $subject;
        return $this;
    }

    /**
     * @param string|null $message
     * @return NotificationEvent
     */
    public function withMessage(?string $message): NotificationEvent
    {
        $this->message = $message;
        return $this;
    }

    /**
     * @return Course|null
     */
    public function course(): ?Course
    {
        return $this->course;
    }

    /**
     * @return Student|null
     */
    public function student(): ?Student
    {
        return $this->student;
    }

    public function dto(): NotificationDto
    {
        return NotificationDto::fromArray([
//            'author' => $this->author,
            'student' => $this->student,
            'course' => $this->course,
            'date' => $this->date ?? CarbonImmutable::today(),
            'type' => $this->type,
            'subject' => $this->subject ?? $this->makeSubject(),
            'message' => $this->message
        ]);
    }

    protected function makeSubject(): string
    {
        return '';
    }


}
