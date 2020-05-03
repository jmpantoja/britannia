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

namespace Britannia\Domain\VO\Course\Examiner;


use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self CAMBRIDGE()
 * @method static self APTIS()
 * @method static self TRINITY()
 */
class Examiner extends Enum
{
    private const CAMBRIDGE = 'Cambridge';
    private const APTIS = 'Aptis';
    private const TRINITY = 'Trinity';

}
