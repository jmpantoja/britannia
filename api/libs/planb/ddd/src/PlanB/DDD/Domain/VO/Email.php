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


class Email
{
    use Validable;

    /**
     * @var string
     */
    private $email = '';


    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\Email([
            'required' => $options['required'] ?? true
        ]);
    }

    public static function make(string $email)
    {
        self::assert($email);
        return new self($email);
    }

    private function __construct(string $email)
    {
        $this->setEmail($email);
    }

    /**
     * @param string $email
     * @return Email
     * @throws \Assert\AssertionFailedException
     */
    private function setEmail(string $email): Email
    {
        $this->email = $email;
        return $this;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public function __toString()
    {
        return $this->getEmail();
    }

}
