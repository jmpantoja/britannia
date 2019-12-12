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

namespace Britannia\Domain\VO\Student\Job;


use MabeEnum\Enum;


class JobStatus extends Enum
{
    public const NOTHING = 'No hace nada';
    public const STUDENT = 'Estudiante';
    public const EMPLOYED = 'En Activo';
    public const UNEMPLOYED = 'En Paro';
    public const RETIRED = 'Pensionista';
    public const DISABLED = 'Minusvalía';


    public static function getDiscountables()
    {
        $values = self::getConstants();

        unset($values['NOTHING']);
        unset($values['EMPLOYED']);

        return $values;
    }
}
