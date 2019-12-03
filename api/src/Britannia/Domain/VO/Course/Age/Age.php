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

namespace Britannia\Domain\VO\Course\Age;


use MabeEnum\Enum;

class Age extends Enum
{
    public const PRESCHOOL = 'Pre Escolar';
    public const CHILD = 'Infantil';
    public const ADULT = 'Adulto';
}
