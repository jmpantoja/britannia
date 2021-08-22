<?php

namespace PlanB\DDD\Domain\VO;

use Symfony\Component\Validator\Constraint;

/**
 * Address
 */
class RefundPrice extends Price
{

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\Price([
            'required' => $options['required'] ?? true,
            'refund' => true
        ]);
    }

    public static function fromPrice(Price $price): self
    {
        return static::make($price->toFloat());
    }

    public function isZero(): bool
    {
        return $this->toFloat() == 0;
    }

}
