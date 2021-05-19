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


use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method  static self NOTHING()
 * @method  static self STUDENT()
 * @method  static self EMPLOYED()
 * @method  static self UNEMPLOYED()
 * @method  static self RETIRED()
 * @method  static self DISABLED()
 */
class JobStatus extends Enum
{
    private const NOTHING = 'No hace nada';
    private const STUDENT = 'Estudiante';
    private const EMPLOYED = 'En Activo';
    private const UNEMPLOYED = 'En Paro';
    private const RETIRED = 'Pensionista';
    private const DISABLED = 'Minusvalía';

    public static function getDiscountables()
    {
        $values = self::getConstants();

        unset($values['NOTHING']);
        unset($values['EMPLOYED']);

        return $values;
    }
}
