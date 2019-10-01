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

namespace PlanB\DDD\Domain\VO\Exceptions;


use Respect\Validation\Exceptions\ValidationException;

class PersonNameRuleException extends ValidationException
{
    public static $defaultTemplates = [
        self::MODE_DEFAULT => [
            self::STANDARD => 'Se necesitan al menos tres letras (a-z), guiones (-) o puntos (.)',
        ],
        self::MODE_NEGATIVE => [
            self::STANDARD => 'No se admite letras (a-z), guiones (-) ni puntos (.)',
        ]
    ];

}
