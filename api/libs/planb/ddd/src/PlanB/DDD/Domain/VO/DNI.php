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


use Respect\Validation\Exceptions\AllOfException;
use Respect\Validation\Validator;

class DNI
{
    /**
     * @var string
     */
    private $identityCode = '';

    /**
     * @var string
     */
    private $number = '';

    /**
     * @var string
     */
    private $letter = '';


    /**
     * @var bool
     */
    private $isDni;


    public static function make(string $dni): self
    {

        return new self($dni);
    }

    public function __construct(string $dni)
    {
        $dni = $this->sanitize($dni);

        $this->ensureIsValid($dni);

        $this->setIdentityCode($dni);
    }

    /**
     * @param string $dni
     * @return null|string|string[]
     */
    private function sanitize(string $dni)
    {
        $dni = strtoupper($dni);
        return preg_replace('/(\s)/', '', $dni);
    }

    private function ensureIsValid(string $dni): void
    {

        Validator::with(__NAMESPACE__ . '\Rules');
        $validator = Validator::create();

        $validator::dniRule()
            ->assert($dni);

    }

    /**
     * @param string $dni
     * @return DNI
     * @throws \Assert\AssertionFailedException
     */
    private function setIdentityCode(string $dni): self
    {

        $prefix = substr($dni, 0, 1);
        $number = substr($dni, 0, 8);
        $letter = substr($dni, -1, 1);

        $this->number = $number;
        $this->letter = $letter;

        if (is_numeric($prefix)) {
            $this->setDNI($number, $letter);
        } else {
            $this->setNIE($prefix, $number, $letter);
        }

        return $this;
    }

    private function setDNI(string $number, string $letter): self
    {

        $this->identityCode = sprintf('%s %s', $number, $letter);
        $this->isDni = true;
        return $this;
    }

    private function setNIE(string $prefix, string $number, string $letter): self
    {

        $number = substr($number, 1, 8);
        $this->identityCode = sprintf('%s %s %s', $prefix, $number, $letter);
        $this->isDni = false;
        return $this;
    }



    /**
     * @return string
     */
    public function getNumber(): string
    {
        return $this->number;
    }

    /**
     * @return string
     */
    public function getLetter(): string
    {
        return $this->letter;
    }

    /**
     * @return string
     */
    public function getIdentityCode(): string
    {
        return $this->identityCode;
    }


    public function isDNI(): bool
    {
        return $this->isDni;
    }


    public function isNIE(): bool
    {
        return !$this->isDni;
    }


    public function __toString()
    {
        return (string)$this->getIdentityCode();
    }


}

