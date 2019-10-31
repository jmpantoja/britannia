<?php

namespace PlanB\DDD\Domain\VO;

use Symfony\Component\Validator\Constraint;

/**
 * Address
 */
class Price
{

    use Traits\Validable;

    private $price;

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\Price([
            'required' => $options['required'] ?? true
        ]);
    }


    public static function make(float $price): self
    {
        $price = self::assert($price);

        return new self($price);
    }

    private function __construct(float $price)
    {
        $this->setPrice($price);
    }

    /**
     * @return int
     */
    public function getPrice(): float
    {
        return $this->price;
    }

    /**
     * @param int $price
     * @return Price
     */
    private function setPrice(float $price): Price
    {
        $this->price = $price;
        return $this;
    }


    public function __toString()
    {
        return number_format($this->getPrice(), 2);
    }


}
