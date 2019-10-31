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
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
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


    public function __toString()
    {
        return (string)$this->getNumber();
    }


}
