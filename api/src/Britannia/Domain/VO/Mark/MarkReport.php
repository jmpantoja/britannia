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

namespace Britannia\Domain\VO\Mark;


use Tightenco\Collect\Support\Collection;

final class MarkReport
{
    /**
     * @var ?Mark
     */
    private $reading;

    /**
     * @var ?Mark
     */

    private $writing;

    /**
     * @var ?Mark
     */
    private $listening;

    /**
     * @var ?Mark
     */
    private $speaking;

    /**
     * @var ?Mark
     */
    private $grammar;

    /**
     * @var ?Mark
     */
    private $vocabulary;

    public static function make(array $marks = []): self
    {
        return new self(collect($marks));
    }

    private function __construct(Collection $marks)
    {
        $this->setMarks($marks);
    }

    /**
     * @param Collection $marks
     */
    private function setMarks(Collection $marks): void
    {
        $this->reading = $marks['reading'] ?? null;
        $this->writing = $marks['writing'] ?? null;
        $this->listening = $marks['listening'] ?? null;
        $this->speaking = $marks['speaking'] ?? null;
        $this->grammar = $marks['grammar'] ?? null;
        $this->vocabulary = $marks['vocabulary'] ?? null;
    }


    /**
     * @return mixed
     */
    public function reading()
    {
        return $this->reading;
    }

    /**
     * @return mixed
     */
    public function writing()
    {
        return $this->writing;
    }

    /**
     * @return mixed
     */
    public function listening()
    {
        return $this->listening;
    }

    /**
     * @return mixed
     */
    public function speaking()
    {
        return $this->speaking;
    }

    /**
     * @return mixed
     */
    public function grammar()
    {
        return $this->grammar;
    }

    /**
     * @return mixed
     */
    public function vocabulary()
    {
        return $this->vocabulary;
    }

    public function get(string $name): ?Mark
    {
        return call_user_func([$this, $name]);
    }

    public function toFloat(string $skill): float
    {
        $mark = $this->get($skill);

        if ($mark instanceof Mark) {
            return $mark->mark();
        }
        return 0.0;
    }

    public function average(SetOfSkills $skills): Mark
    {
        $average = collect($skills)
            ->map(fn(string $skill) => $this->toFloat($skill))
            ->average();

        return Mark::make($average);
    }

    public function isEmpty(SetOfSkills $skills): bool
    {
        return collect($skills)
            ->map(fn(string $skill) => $this->get($skill))
            ->filter()
            ->isEmpty();

    }
}
