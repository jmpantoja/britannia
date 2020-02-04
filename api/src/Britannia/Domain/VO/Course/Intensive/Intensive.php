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

namespace Britannia\Domain\VO\Course\Intensive;


use MabeEnum\Enum;

class Intensive extends Enum
{
    public const INTENSIVE = 'Intensivo';
    public const NOT_INTENSIVE = 'No Intensivo';

}
