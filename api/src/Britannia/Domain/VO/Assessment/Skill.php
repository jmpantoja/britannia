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

namespace Britannia\Domain\VO\Assessment;


use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self IRREGULAR_VERBS()
 * @method static self ALPHABET()
 * @method static self DAYS_OF_THE_WEEK()
 */
class Skill extends Enum
{
    private const IRREGULAR_VERBS = 'Verbos Irregulares';
    private const ALPHABET = 'Alfabeto';
    private const DAYS_OF_THE_WEEK = 'Dias de la semana';
}
