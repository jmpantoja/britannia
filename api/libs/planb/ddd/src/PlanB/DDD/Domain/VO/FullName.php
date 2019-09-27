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


use Britannia\Domain\VO\Rules\FirstNameRule;
use Respect\Validation\Validator;

class FullName
{
    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $lastName;

    public static function byDefault(): self
    {
        return new self('', '');
    }

    public static function make(string $firstName, string $lastName): self
    {
        return new self($firstName, $lastName);
    }

    private function __construct(string $firstName, string $lastName)
    {
        $this->ensureIsValid($firstName, $lastName);

        $this->setFirstName($firstName);
        $this->setLastName($lastName);
    }


    /**
     * @param $input
     */
    private function ensureIsValid(string $firstName, string $lastName): void
    {
        Validator::with(__NAMESPACE__ . '\Rules');

        $validator = Validator::create();
        $validator
            ->key('firstName', Validator::personNameRule()->notEmpty())
            ->key('lastName', Validator::personNameRule()->notEmpty());

        $validator->assert([
            'firstName' => $firstName,
            'lastName' => $lastName,
        ]);
    }


    /**
     * @param string $firstName
     * @return FullName
     * @throws \Assert\AssertionFailedException
     */
    private function setFirstName(string $firstName): FullName
    {

        $this->firstName = $this->normalize($firstName);
        return $this;
    }

    /**
     * @param string $lastName
     * @return FullName
     * @throws \Assert\AssertionFailedException
     */
    private function setLastName(string $lastName): FullName
    {
        $this->lastName = $this->normalize($lastName);
        return $this;
    }

    private function normalize($name): string
    {
        return ucwords($name);
    }


    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }


    public function getReversedMode(): string
    {
        return sprintf('%s, %s', $this->lastName, $this->firstName);
    }


    public function __toString()
    {
        return $this->getReversedMode();
    }


}
