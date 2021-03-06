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

namespace Britannia\Domain\Entity\Assessment;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\Assessment\MarkReport;
use Britannia\Domain\VO\Assessment\SetOfSkills;
use PlanB\DDD\Domain\Behaviour\Comparable;
use PlanB\DDD\Domain\Behaviour\Traits\ComparableTrait;
use PlanB\DDD\Domain\Model\Traits\AggregateRootTrait;
use PlanB\DDD\Domain\VO\PositiveInteger;

class Unit implements Comparable
{
    use ComparableTrait;
    use AggregateRootTrait;

    /**
     * @var UnitId
     */
    private $id;

    /**
     * @var Term
     */
    private $term;

    /**
     * @var integer
     */
    private $number;

    /**
     * @var MarkReport
     */
    private $marks;

    public static function make(Term $term, PositiveInteger $number)
    {
        return new self($term, $number);
    }

    private function __construct(Term $term, PositiveInteger $number)
    {

        $this->id = new UnitId();
        $this->term = $term;
        $this->number = $number;

        $this->marks = MarkReport::make();

    }

    public function updateMarks(MarkReport $marks): self
    {
        $this->marks = $marks;
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
     * @return int
     */
    public function term(): Term
    {
        return $this->term;
    }

    /**
     * @return SetOfSkills
     */
    public function skills(): SetOfSkills
    {
        return $this->term()->setOfSkills();
    }

    /**
     * @return Student
     */
    public function student(): Student
    {
        return $this->student;
    }

    /**
     * @return Course
     */
    public function course(): Course
    {
        return $this->course;
    }

    /**
     * @return int
     */
    public function number(): PositiveInteger
    {
        return $this->number;
    }

    /**
     * @return MarkReport
     */
    public function marks(): MarkReport
    {
        return $this->marks;
    }


    public function termHash(): string
    {
        return sprintf('%s-%s', ...[
            $this->term()->termName(),
            $this->number
        ]);
    }

}
