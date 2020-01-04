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

final class MarkList
{
    /**
     * @var SetOfSkills
     */
    private $skills;

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

    /**
     * @var ?Mark
     */
    private $average;


    public static function make(SetOfSkills $skills, array $marks): self
    {
        return new self($skills, collect($marks));
    }

    private function __construct(SetOfSkills $skills, Collection $marks)
    {
        $this->skills = $skills;

        $this->setMarks($marks);
        $this->setFinal($marks);
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

    private function setFinal(Collection $marks): self
    {
        $sum = $marks->sum(function (?Mark $mark) {
            return $mark instanceof Mark ? $mark->mark() : 0;
        });

        $mark = $sum / $this->skills()->toInt();
        $this->average = Mark::make($mark);

        return $this;
    }

    /**
     * @return SetOfSkills
     */
    public function skills(): SetOfSkills
    {
        return $this->skills;
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

    /**
     * @return mixed
     */
    public function average()
    {
        return $this->average;
    }

    public function get(string $name): ?Mark
    {
        return call_user_func([$this, $name]);
    }

    public function isEmpty(): bool
    {
        foreach ($this->skills() as $name) {
            $data[$name] = $this->get($name);
        }

        return collect($data)
            ->filter()
            ->isEmpty();

    }
}
