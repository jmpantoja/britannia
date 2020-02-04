<?php

namespace PlanB\DDD\Domain\VO;

use phpDocumentor\Reflection\Types\Self_;
use Symfony\Component\Validator\Constraint;

/**
 * Address
 */
class Percent
{

    use Traits\Validable;

    private $percent;

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\Percent([
            'required' => $options['required'] ?? true
        ]);
    }

    public static function zero(): self
    {
        return new self(0);
    }


    public static function make(int $percent): self
    {
        $percent = self::assert($percent);

        return new self($percent);
    }

    private function __construct(int $percent)
    {
        $this->percent = $percent;
    }

    public function toInt(): int
    {
        return $this->percent;
    }

    public function toFloat(): float
    {
        return $this->percent / 100;
    }

    public function hasValue(int $value): bool
    {
        return $this->percent === $value;
    }

    public function isZero(): bool
    {
        return $this->hasValue(0);
    }

    public function complementary(): Percent
    {
        return Percent::make(100 - $this->toInt());
    }

    public function __toString()
    {
        return (string)$this->toInt();
    }

}
