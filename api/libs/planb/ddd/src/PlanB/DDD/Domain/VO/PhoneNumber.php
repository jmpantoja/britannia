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


use Assert\Assert;
use Assert\Assertion;
use Assert\InvalidArgumentException;
use Respect\Validation\Validator;

class PhoneNumber
{

    /**
     * @var string
     */
    private $phoneNumber;


    public static function make(string $phoneNumber): self
    {
        return new self($phoneNumber);
    }

    private function __construct(string $phoneNumber)
    {
        $this->setPhoneNumber($phoneNumber);
    }

    private function setPhoneNumber(string $phoneNumber): self
    {

        Validator::phone('ES')
            ->assert($phoneNumber);

        $phoneNumber = $this->normalize($phoneNumber);

        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    private function normalize(string $phoneNumber): string
    {
        $phoneNumber = preg_replace('/[\s|\+]/', '', $phoneNumber);
        $phoneNumber = $this->normalizeNormal($phoneNumber);
        $phoneNumber = $this->normalizePrefix($phoneNumber);

        return $phoneNumber;

    }

    private function normalizeNormal(string $phoneNumber)
    {
        $matches = [];

        if (!preg_match('/^(\d{3})(\d{2})(\d{2})(\d{2})$/', $phoneNumber, $matches)) {
            return $phoneNumber;
        }

        unset($matches[0]);
        return sprintf('+34 %s %s %s %s', ...$matches);

    }

    private function normalizePrefix(string $phoneNumber)
    {
        $matches = [];

        if (!preg_match('/^(\d{2})(\d{3})(\d{2})(\d{2})(\d{2})$/', $phoneNumber, $matches)) {
            return $phoneNumber;
        }

        unset($matches[0]);


        return sprintf('+%s %s %s %s %s', ...$matches);

    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function __toString()
    {
        return $this->getPhoneNumber();
    }


}
