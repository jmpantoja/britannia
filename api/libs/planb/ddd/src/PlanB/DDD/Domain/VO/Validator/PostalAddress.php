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


class PostalAddress extends Constraint
{

    public $addressRequiredMessage = 'Se necesita una dirección';
    public $addressLenghtMessage = 'Se necesita {{ limit }} caracter o más.|Se necesitan {{ limit }} o más caracteres.';
    public $postalCodeRequiredMessage = 'Se necesita un código postal';

    public function isValidType($value): bool
    {
        return is_array($value) || $value instanceof \PlanB\DDD\Domain\VO\PostalAddress;
    }
}
