<?php

namespace PlanB\DDD\Domain\VO;

use Respect\Validation\Validator;

/**
 * Address
 */
class PostalCode
{

    /**
     * @var string
     */
    private $postalCode;


    public static function make(string $postalCode): self
    {
        return new self($postalCode);
    }

    private function __construct(string $postalCode)
    {
        $this->setPostalCode($postalCode);
    }

    /**
     * @param string $postalCode
     * @return PostalAddress
     * @throws \Assert\AssertionFailedException
     */
    private function setPostalCode(string $postalCode): self
    {
        $postalCode = preg_replace('/\s/', '', $postalCode);

        Validator::postalCode('ES')
            ->setTemplate("'{{name}}' is an invalid postal code")
            ->assert($postalCode);


        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return (string)$this->postalCode;
    }

    public function __toString()
    {
        return $this->getPostalCode();
    }

}
