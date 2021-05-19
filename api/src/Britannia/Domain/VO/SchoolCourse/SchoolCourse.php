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

namespace Britannia\Domain\VO\SchoolCourse;


use PlanB\DDD\Domain\VO\PositiveInteger;

final class SchoolCourse
{
    /**
     * @var PositiveInteger
     */
    private PositiveInteger $course;
    /**
     * @var SchoolLevel
     */
    private SchoolLevel $level;
    private string $name;
    private int $age;

    public static function fromArray(array $data): self
    {
        $course = $data['course'] ?? null;
        $level = $data['level'] ?? null;

        return new self(...[
            PositiveInteger::make($course),
            SchoolLevel::byName($level)
        ]);
    }

    private function __construct(PositiveInteger $course, SchoolLevel $level)
    {
        $this->course = $course;
        $this->level = $level;

        $this->initName();
        $this->initAge();
    }


    private function initName(): self
    {
        $this->name = sprintf('%sº de %s', $this->course, $this->level->name());
        return $this;
    }

    private function initAge(): self
    {
        $this->age = $this->level->age()->toInt() + $this->course->toInt() - 1;
        return $this;
    }

    /**
     * @return string
     */
    public function name(): string
    {
        return $this->name;
    }

    /**
     * @return int
     */
    public function age(): int
    {
        return $this->age;
    }

    public function key(): string
    {
        return sprintf('%s_%s', $this->level->getKey(), $this->course);
    }
}
