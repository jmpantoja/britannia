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

namespace Britannia\Domain\Entity\Record;


use MabeEnum\Enum;

/**
 * @method static self CREATED()
 * @method static self COURSE()
 * @method static self ATTENDANCE()
 */
class TypeOfRecord extends Enum
{
    public const CREATED = 'Nuevo Alumno';
    public const COURSE = 'Altas / Bajas';
    public const ATTENDANCE = 'Asistencia';


}
