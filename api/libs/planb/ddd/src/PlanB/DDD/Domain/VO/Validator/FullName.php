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


use PlanB\DDD\Domain\Filter\ProperName;

class FullName extends Constraint
{
    public $requiredMessage = 'Se necesitan un nombre y apellidos';

    public $firstNameRequiredMessage = 'Se necesita un nombre';
    public $lastNameRequiredMessage = 'Se necesitan unos apellidos';

    public $lengthMessage = 'Se necesita {{ limit }} caracter o más.|Se necesitan {{ limit }} o más caracteres.';
    public $regexMessage = 'Solo se admiten letras, guiones (-) o puntos (.)';


    public function isValidType($value): bool
    {
        return is_array($value) || $value instanceof \PlanB\DDD\Domain\VO\FullName;
    }

    public function normalize($value)
    {
        $filter = new ProperName();

        return array_map(static function ($word) use ($filter) {
            return $filter->filter($word);
        }, $value);

        return $value;
    }
}
