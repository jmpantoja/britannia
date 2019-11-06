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


use Britannia\Domain\VO\TimeSheet;

class Lesson
{

    /**
     * @var LessonId
     */
    private $id;

    /**
     * @var int
     */
    private $number;

    /**
     * @var Course
     */
    private $course;

    /**
     * @var \DateTimeImmutable
     */
    private $day;

    /**
     * @var \DateTime
     */
    private $start;

    /**
     * @var \DateTime
     */
    private $end;


    public static function make(int $number, Course $course, \DateTimeImmutable $start, \DateInterval $length): self
    {

        return new self($number, $course, $start, $length);
    }

    private function __construct(int $number, Course $course, \DateTimeImmutable $start, \DateInterval $length)
    {
        $this->id = new LessonId();
        $this->number = $number;
        $this->course = $course;

        $this->start = $start;
        $this->end = $start->add($length);

        $this->day = $start->setTime(0,0,0);
    }

    /**
     * @return LessonId
     */
    public function getId(): LessonId
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @return \DateTimeImmutable
     */
    public function getDay(): \DateTimeImmutable
    {
        return $this->day;
    }


    /**
     * @return \DateTime
     */
    public function getStart(): \DateTime
    {
        return $this->start;
    }

    /**
     * @return \DateTime
     */
    public function getEnd(): \DateTime
    {
        return $this->end;
    }

    /**
     * @return Course
     */
    public function getCourse(): Course
    {
        return $this->course;
    }

}
