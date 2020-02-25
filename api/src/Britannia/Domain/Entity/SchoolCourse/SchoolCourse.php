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

namespace Britannia\Domain\Entity\SchoolCourse;


use Britannia\Domain\VO\SchoolCourse\SchoolLevel;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\PositiveInteger;

final class SchoolCourse
{
    private $id;

    /**
     * @var PositiveInteger
     */
    private $course;

    /**
     * @var SchoolLevel
     */
    private $level;

    /**
     * @var CarbonImmutable
     */
    private $createdAt;

    /**
     * @var CarbonImmutable
     */
    private $updatedAt;

    /**
     * @var float|int
     */
    private $weight;


    public static function make(SchoolCourseDto $dto): self
    {
        return new self($dto);
    }

    private function __construct(SchoolCourseDto $dto)
    {
        $this->id = new SchoolCourseId();
        $this->createdAt = CarbonImmutable::now();

        $this->update($dto);
    }


    public function update(SchoolCourseDto $dto): self
    {
        $this->course = $dto->course;
        $this->level = $dto->level;

        $this->updateWeight();

        $this->updatedAt = CarbonImmutable::now();
        return $this;
    }

    /**
     * @return float|int
     */
    protected function updateWeight(): self
    {
        $course = $this->course->toInt();
        $level = $this->level->order();
        $order = ($level - 1) * 10 + $course ;


        $this->weight = PositiveInteger::make($order);
        return $this;
    }

    /**
     * @return mixed
     */
    public function id(): ?SchoolCourseId
    {
        return $this->id;
    }

    /**
     * @return PositiveInteger
     */
    public function course(): PositiveInteger
    {
        return $this->course;
    }

    /**
     * @return SchoolLevel
     */
    public function level(): SchoolLevel
    {
        return $this->level;
    }


    /**
     * @return string
     */
    public function name(): string
    {
        return sprintf('%sº %s', $this->course, $this->level->getValue());
    }


    public function __toString()
    {
        return $this->name();
    }


}
