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

namespace Britannia\Domain\Entity\Mark;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Mark\Term;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\VO\PositiveInteger;

class Unit implements Comparable
{
    use ComparableTrait;

    /**
     * @var UnitId
     */
    private $id;

    /**
     * @var Course
     */
    private $course;

    /**
     * @var Term
     */
    private $term;

    /**
     * @var PositiveInteger
     */
    private $number;

    /**
     * @var PositiveInteger
     */
    private $position;


    /**
     * @var CarbonImmutable
     */
    private $completedAt;

    private function __construct(Course $course, Term $term, PositiveInteger $number)
    {
        $this->id = new UnitId();

        $this->course = $course;
        $this->term = $term;
        $this->number = $number;
    }

    public static function make(Course $course, Term $term, PositiveInteger $number)
    {
        return new self($course, $term, $number);
    }

    private function setPosition(Term $term, PositiveInteger $number): self
    {
        $this->number = $number;
        $this->position = $number->addInteger($term->toInt());
        return $this;
    }

    /**
     * @return UnitId
     */
    public function id(): UnitId
    {
        return $this->id;
    }

    /**
     * @return Course
     */
    public function course(): Course
    {
        return $this->course;
    }

    /**
     * @return Term
     */
    public function term(): Term
    {
        return $this->term;
    }


    /**
     * @return PositiveInteger
     */
    public function number(): PositiveInteger
    {
        return $this->number;
    }

    /**
     * @return PositiveInteger
     */
    public function position(): PositiveInteger
    {
        return $this->position;
    }


    /**
     * @return CarbonImmutable
     */
    public function completedAt(): ?CarbonImmutable
    {
        return $this->completedAt;
    }

    public function isCompleted(): bool
    {
        return $this->completedAt instanceof CarbonImmutable;
    }

}
