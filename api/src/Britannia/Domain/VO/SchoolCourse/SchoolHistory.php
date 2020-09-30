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


final class SchoolHistory
{

    private array $history = [];
    private int $current;

    public static function fromArray($input)
    {
        return new self($input);
    }

    private function __construct(array $history)
    {
        $this->history = $history;
        $this->current = SchoolYear::make()
            ->from();
    }

    public function numOfFailedCourses(): int
    {

        $data = array_count_values(array_filter($this->history));
        return collect($data)
            ->map(fn(int $value) => $value - 1)
            ->sum();
    }

    public function update($value): self
    {
        $current = $this->current;
        $this->history[$current] = $value;

        return new static($this->history);
    }

    public function current(): ?SchoolCourse
    {
        $current = $this->current;
        $key = $this->history[$current] ?? null;

        if (is_null($key)) {
            return null;
        }

        return SchoolItinerary::schoolCourseByKey($key);
    }

    public function toArray(): array
    {
        return $this->history;
    }
}
