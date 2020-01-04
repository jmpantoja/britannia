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

namespace Britannia\Domain\VO\Mark;


use ArrayIterator;
use IteratorAggregate;
use MabeEnum\Enum;

class SetOfSkills extends Enum implements IteratorAggregate
{

    public const SET_OF_FOUR = 'Reading, Writing, Listening, Speaking';
    public const SET_OF_SIX = 'Reading, Writing, Listening, Speaking, Grammar, Vocabulary';


    /**
     * @inheritDoc
     */
    public function getIterator()
    {
        $skills = [
            'R' => 'reading',
            'W' => 'writing',
            'L' => 'listening',
            'S' => 'speaking',
        ];

        if ($this->is(self::SET_OF_SIX)) {
            $skills['G'] = 'grammar';
            $skills['V'] = 'vocabulary';
        }

        return new ArrayIterator($skills);
    }

    public function toInt(): int
    {
        if ($this->is(self::SET_OF_SIX)) {
            return 6;
        }

        return 4;

    }
}
