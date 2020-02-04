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


use MabeEnum\Enum;

class Skill extends Enum
{
    public const IRREGULAR_VERBS = 'Verbos Irregulares';
    public const ALPHABET = 'Alfabeto';
    public const DAYS_OF_THE_WEEK = 'Dias de la semana';
}
