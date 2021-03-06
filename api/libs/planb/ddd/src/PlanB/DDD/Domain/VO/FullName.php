<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PlanB\DDD\Domain\VO;


use Symfony\Component\Validator\Constraint;

class FullName
{

    use Traits\Validable;

    private $firstName;
    private $lastName;
    private $fullName;

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\FullName([
            'required' => $options['required'] ?? true
        ]);
    }

    public static function make(string $firstName, string $lastName)
    {
        $data = self::assert([
            'firstName' => $firstName,
            'lastName' => $lastName
        ]);
        return new self($data['firstName'], $data['lastName']);
    }


    private function __construct(string $firstName, string $lastName)
    {
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
        $this->setFullName($firstName, $lastName);
    }


    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $firstName
     * @return FullName
     */
    private function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;
        return $this;
    }

    /**
     * @param mixed $firstName
     * @return FullName
     */
    private function setFullName(string $firstName, string $lastName): self
    {
        $fullName = sprintf('%s %s', ...[
            $firstName,
            $lastName
        ]);

        $this->fullName = strtolower($fullName);
        return $this;
    }


    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $lastName
     * @return FullName
     */
    private function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;
        return $this;
    }


    public function getReversedMode(): string
    {
        return sprintf('%s, %s', ...[
            $this->getLastName(),
            $this->getFirstName()
        ]);
    }

    public function getFullName(): string
    {
        return $this->fullName;
    }

    public function getRegular(): string
    {
        return sprintf('%s %s', ...[
            $this->getFirstName(),
            $this->getLastName()
        ]);
    }

    public function __toString()
    {
        return $this->getReversedMode();
    }
}
