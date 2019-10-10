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
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validation;

class FullName
{

    use Traits\Validable;

    private $firstName;
    private $lastName;


    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\FullName([
            'required' => $options['required'] ?? true
        ]);
    }

    public static function make(string $firstName, string $lastName)
    {
        self::assert([
            'firstName' => $firstName,
            'lastName' => $lastName
        ]);

        return new self($firstName, $lastName);
    }


    private function __construct(string $firstName, string $lastName)
    {
        $this->setFirstName($firstName);
        $this->setLastName($lastName);
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
    private function setFirstName($firstName)
    {
        $this->firstName = $this->normalize($firstName);
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
    private function setLastName($lastName)
    {
        $this->lastName = $this->normalize($lastName);
        return $this;
    }

    private function normalize(string $name): string
    {
        $pieces = preg_split("/\s+/", $name);

        $pieces = array_map(function (string $piece) {
            return $this->format($piece);
        }, $pieces);


        return implode(" ", $pieces);
    }

    private function format(string $name): string
    {
        if (strlen($name) <= 3) {
            return $name;
        }

        return ucfirst(strtolower($name));
    }

    public function getReversedMode(): string
    {
        return sprintf('%s, %s', ...[
            $this->getLastName(),
            $this->getFirstName()
        ]);
    }

}
