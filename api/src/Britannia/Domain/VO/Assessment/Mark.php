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


use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;

class Mark
{
    use Validable;

    /**
     * @var float
     */
    private $mark;

    private function __construct(?float $mark)
    {
        $this->mark = $mark;
    }

    public static function buildConstraint(array $options = []): Constraint
    {
        return new \Britannia\Domain\VO\Assessment\Validator\Mark([
            'required' => $options['required'] ?? false
        ]);
    }

    public static function make(?float $mark): self
    {
        if (is_null($mark)) {
            return new self(null);
        }

        $mark = self::assert($mark);
        return new self($mark);
    }

    public static function notAssessment(): self
    {
        return new self(null);
    }

    /**
     * @return float
     */
    public function toFloat(): ?float
    {
        return $this->mark;
    }

    public function format(int $dec = 1): string
    {
        return bcdiv((string)$this->toFloat(), '1', $dec);
    }

    public function range(): MarkRange
    {
        return MarkRange::make($this->toFloat());
    }

    public function __toString()
    {
        if ($this->isMissingSkill()) {
            return '';
        }

        return (string)$this->format(1);
    }

    public function isMissingSkill(): bool
    {
        return is_null($this->mark);
    }

}
