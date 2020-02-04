<?php

namespace PlanB\DDD\Domain\VO;

use Symfony\Component\Validator\Constraint;

/**
 * Address
 */
class PositiveInteger
{

    use Traits\Validable;

    private $number;

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\PositiveInteger([
            'required' => $options['required'] ?? true
        ]);
    }


    public static function make(int $number): self
    {
        $number = self::assert($number);

        return new self($number);
    }

    private function __construct(int $number)
    {
        $this->setNumber($number);
    }

    /**
     * @param int $number
     * @return PositiveInteger
     */
    private function setNumber(int $number): PositiveInteger
    {
        $this->number = $number;
        return $this;
    }

    /**
     * @return int
     */
    public function toInt(): int
    {
        return $this->number;
    }

    public function addInteger(int $value): PositiveInteger
    {
        $number = $this->toInt() + $value;
        return PositiveInteger::make($number);
    }

    public function substractInteger(int $value): PositiveInteger
    {
        $number = $this->toInt() - $value;
        return PositiveInteger::make($number);
    }

    public function compare(PositiveInteger $other): int
    {
        return $this->toInt() <=> $other->toInt();
    }


    public function __toString()
    {
        return (string)$this->toInt();
    }


}
