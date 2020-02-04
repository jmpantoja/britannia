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
        $this->reading = $this->sanitize($marks['reading'] ?? null);
        $this->writing = $this->sanitize($marks['writing'] ?? null);
        $this->listening = $this->sanitize($marks['listening'] ?? null);
        $this->speaking = $this->sanitize($marks['speaking'] ?? null);
        $this->grammar = $this->sanitize($marks['grammar'] ?? null);
        $this->vocabulary = $this->sanitize($marks['vocabulary'] ?? null);
    }

    private function sanitize($mark): Mark
    {
        if ($mark instanceof Mark) {
            return $mark;
        }

        if (is_null($mark)) {
            return Mark::notAssessment();
        }

        return Mark::make($mark);
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

        return $mark->toFloat();

    }

    public function toArray(): array
    {
        return [
            'reading' => $this->reading->toFloat(),
            'writing' => $this->writing->toFloat(),
            'listening' => $this->listening->toFloat(),
            'speaking' => $this->speaking->toFloat(),
            'grammar' => $this->grammar->toFloat(),
            'vocabulary' => $this->vocabulary->toFloat()
        ];
    }

    public function average(SetOfSkills $skills): Mark
    {
        $average = collect($skills)
            ->map(fn(string $skill) => $this->toFloat($skill))
            ->average();

        return Mark::make($average);
    }

    public function someMissedSkils(SetOfSkills $skills): bool
    {
        return collect($skills)
            ->filter(fn(string $skill) => $this->isMissingSkill($skill))
            ->isNotEmpty();

    }

    public function isMissingSkill(string $skill): bool
    {
        return $this->get($skill)->isMissingSkill();
    }

}
