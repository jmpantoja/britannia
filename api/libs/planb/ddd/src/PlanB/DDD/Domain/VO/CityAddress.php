<?php

namespace PlanB\DDD\Domain\VO;

use Respect\Validation\Exceptions\AllOfException;
use Respect\Validation\Validator;

/**
 * Address
 */
class CityAddress
{
    /**
     * @var string
     */
    private $city;

    /**
     * @var string
     */
    private $province;


    public static function make(string $city, string $province): self
    {

        Validator::with(__NAMESPACE__ . '\Rules');
        $validator = Validator::create();

        $validator->key('city', Validator::notEmpty());
        $validator->key('province', Validator::notEmpty());


        $validator->assert([
            'city' => $city,
            'province' => $province
        ]);

        return new self($city, $province);
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
