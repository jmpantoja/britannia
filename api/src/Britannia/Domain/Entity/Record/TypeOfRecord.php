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

class TypeOfRecord extends Enum
{
    public const CREATED = 'Altas';
    public const COURSE = 'Cursos';
    public const ATTENDANCE = 'Asistencia';
}
