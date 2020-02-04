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

namespace PlanB\DDD\Domain\Model\Exception;


use Symfony\Component\Form\Exception\InvalidArgumentException;

final class InvalidTypeException extends InvalidArgumentException
{
    public static function make($value, string $expectedType): self
    {
        return new self($value, $expectedType);
    }

    private function __construct($value, string $expectedType)
    {
        parent::__construct(sprintf('Expected argument of type "%s", "%s" given', $expectedType, \is_object($value) ? \get_class($value) : \gettype($value)));
    }
}
