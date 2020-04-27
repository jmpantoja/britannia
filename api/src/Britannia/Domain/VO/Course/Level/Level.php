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

namespace Britannia\Domain\VO\Course\Level;


use MabeEnum\Enum;

class Level extends Enum
{
    public const A1 = 'A1';
    public const A2 = 'A2';
    public const B1 = 'B1';
    public const B2 = 'B2';
    public const C1 = 'C1';
    public const C2 = 'C2';
}
