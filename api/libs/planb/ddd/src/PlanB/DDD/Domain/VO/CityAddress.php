<?php

namespace PlanB\DDD\Domain\VO;

use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator;

/**
 * Address
 */
class CityAddress
{
    use Validable;
    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $province;

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\CityAddress([
            'required' => $options['required'] ?? true
        ]);

    }

    public static function make(string $city, string $province): self
    {
        $data = self::assert([
            'city' => $city,
            'province' => $province
        ]);

        return new self($data['city'], $data['province']);
    }

    private function __construct(string $city, string $province)
    {
        $this->setCity($city);
        $this->setProvince($province);
    }

    /**
     * @return string
     */
    public function getCity(): string
    {
        return $this->city;
    }

    /**
     * @param string $city
     * @return CityAddress
     */
    private function setCity(string $city): CityAddress
    {
        $this->city = $city;
        return $this;
    }

    /**
     * @return string
     */
    public function getProvince(): string
    {
        return $this->province;
    }

    /**
     * @param string $province
     * @return CityAddress
     */
    private function setProvince(string $province): CityAddress
    {
        $this->province = $province;
        return $this;
    }


}
