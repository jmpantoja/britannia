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

use Respect\Validation\Validator;

class Email
{
    /**
     * @var string
     */
    private $email = '';

    public static function make(string $email)
    {
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

        Validator::email()
            ->setTemplate('Email incorrecto (ej. username@example.com)')
            ->assert($email);

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
