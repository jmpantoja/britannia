<?php

namespace PlanB\DDD\Domain\VO;

use Symfony\Component\Validator\Constraint;

/**
 * Address
 */
class PostalAddress
{
    use Traits\Validable;
    /**
     * @var string
     */
    private $address;

    /**
     * @var string
     */
    private $postalCode;

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\PostalAddress([
            'required' => $options['required'] ?? true
        ]);
    }


    public static function make(string $address, PostalCode $postalCode): self
    {
        $data = self::assert([
            'address' => $address,
            'postalCode' => $postalCode
        ]);

        return new self($data['address'], $data['postalCode']);
    }

    private function __construct(string $address, PostalCode $postalCode)
    {
        $this->setAddress($address);
        $this->setPostalCode($postalCode);
    }

    private function setAddress(string $address): self
    {
        $this->address = $address;
        return $this;
    }

    /**
     * @param string $postalCode
     * @return PostalAddress
     * @throws \Assert\AssertionFailedException
     */
    private function setPostalCode(PostalCode $postalCode): self
    {
        $this->postalCode = $postalCode;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function getFullAddress(): string
    {
        return sprintf('%s. %s', $this->getAddress(), $this->getPostalCode());
    }

    /**
     * @return string
     */
    public function getPostalCode(): ?PostalCode
    {
        return $this->postalCode;
    }

    public function __toString()
    {
        return $this->getFullAddress();
    }


}
