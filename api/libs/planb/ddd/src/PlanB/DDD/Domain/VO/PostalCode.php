<?php

namespace PlanB\DDD\Domain\VO;

use Symfony\Component\Validator\Constraint;

/**
 * Address
 */
class PostalCode
{

    use Traits\Validable;

    /**
     * @var string
     */
    private $postalCode;

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\PostalCode([
            'required' => $options['required'] ?? false
        ]);
    }


    public static function make(string $postalCode): self
    {
        $postalCode = self::assert($postalCode);

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
        $this->postalCode = $postalCode;
        return $this;
    }

    /**
     * @return string
     */
    public function getPostalCode(): string
    {
        return $this->postalCode;
    }

    public function __toString()
    {
        return $this->getPostalCode();
    }


}
