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


use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator;

class PhoneNumber
{

    use Validable;
    /**
     * @var string
     */
    private $phoneNumber;


    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\PhoneNumber([
            'required' => $options['required'] ?? true
        ]);
    }

    public static function make(string $phoneNumber): self
    {
        $phoneNumber = self::assert($phoneNumber);
        return new self($phoneNumber);
    }

    private function __construct(string $phoneNumber)
    {
        $this->setPhoneNumber($phoneNumber);
    }

    private function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
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
