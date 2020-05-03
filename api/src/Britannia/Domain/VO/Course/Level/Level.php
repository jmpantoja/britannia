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


use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self A1()
 * @method static self A2()
 * @method static self B1()
 * @method static self B2()
 * @method static self C1()
 * @method static self C2()
 */
class Level extends Enum
{
    private const A1 = 'A1';
    private const A2 = 'A2';
    private const B1 = 'B1';
    private const B2 = 'B2';
    private const C1 = 'C1';
    private const C2 = 'C2';
}
