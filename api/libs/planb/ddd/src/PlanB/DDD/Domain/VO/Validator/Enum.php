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

namespace PlanB\DDD\Domain\VO\Validator;


final class Enum extends Constraint
{

    public $enumClass;

    public function __construct(string $enumClass, $options = null)
    {
        if (!(is_a($enumClass, \PlanB\DDD\Domain\Enum\Enum::class, true))) {
            $message = sprintf('"%s" debería ser un subtipo de "%s"', ...[
                $enumClass,
                \PlanB\DDD\Domain\Enum\Enum::class
            ]);
            throw new \Exception($message);
        }

        $this->enumClass = $enumClass;
        parent::__construct($options);
    }


    public function isValidType($value): bool
    {
        return is_scalar($value) || is_a($value, $this->enumClass, true);
    }
}
