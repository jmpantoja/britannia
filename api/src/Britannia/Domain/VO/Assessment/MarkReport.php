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

namespace Britannia\Domain\VO\Assessment;


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
        $this->reading = $marks['reading'] ?? Mark::notAssessment();
        $this->writing = $marks['writing'] ?? Mark::notAssessment();
        $this->listening = $marks['listening'] ?? Mark::notAssessment();
        $this->speaking = $marks['speaking'] ?? Mark::notAssessment();
        $this->grammar = $marks['grammar'] ?? Mark::notAssessment();
        $this->vocabulary = $marks['vocabulary'] ?? Mark::notAssessment();
    }


    /**
     * @return mixed
     */
    public function reading(): Mark
    {
        return $this->reading ?? Mark::notAssessment();
    }

    /**
     * @return mixed
     */
    public function writing(): Mark
    {
        return $this->writing ?? Mark::notAssessment();
    }

    /**
     * @return mixed
     */
    public function listening(): Mark
    {
        return $this->listening ?? Mark::notAssessment();
    }

    /**
     * @return mixed
     */
    public function speaking(): Mark
    {
        return $this->speaking ?? Mark::notAssessment();
    }

    /**
     * @return mixed
     */
    public function grammar(): Mark
    {
        return $this->grammar ?? Mark::notAssessment();
    }

    /**
     * @return mixed
     */
    public function vocabulary(): Mark
    {
        return $this->vocabulary ?? Mark::notAssessment();
    }

    public function get(string $name): Mark
    {
        return call_user_func([$this, $name]);
    }

    public function toFloat(string $skill): ?float
    {
        $mark = $this->get($skill);

        return $mark->mark();

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
