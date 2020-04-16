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
        $price = static::assert($price);

        return new static($price);
    }

    private function __construct(float $price)
    {
        $this->setPrice($price);
    }

    public function add(Price $otherPrice): Price
    {
        $total = $this->toFloat() + $otherPrice->toFloat();
        return Price::make($total);
    }

    public function discount(Percent $percent): Price
    {
        $price = $this->toFloat();
        $newPrice = $price - ($price * $percent->toFloat());

        return new static($newPrice);
    }

    /**
     * Si el objeto actual es:
     * mayor que el argumento: devuelve 1
     * igual que el argumento: devuelve 0
     * menor que el argumento: devuelve -1
     *
     * @param Price $price
     * @return int
     */
    public function compare(Price $price): int
    {
        if ($this->toFloat() > $price->toFloat()) {
            return 1;
        }

        if ($this->toFloat() === $price->toFloat()) {
            return 0;
        }

        return -1;
    }

    /**
     * @return int
     */
    public function toFloat(): float
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
        return number_format($this->toFloat(), 2);
    }


}
