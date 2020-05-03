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


use Countable;
use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self SET_OF_FOUR()
 * @method static self SET_OF_SIX()
 */
class SetOfSkills extends Enum implements Countable
{

    private const SET_OF_FOUR = 'Reading, Writing, Listening, Speaking';
    private const SET_OF_SIX = 'Reading, Writing, Listening, Speaking, Grammar, Vocabulary';

    /**
     * @inheritDoc
     */
    public function toList()
    {
        $skills = [
            'R' => 'reading',
            'W' => 'writing',
            'L' => 'listening',
            'S' => 'speaking',
        ];

        if ($this->is(self::SET_OF_SIX())) {
            $skills['G'] = 'grammar';
            $skills['V'] = 'vocabulary';
        }

        return $skills;
    }

    /**
     * @inheritDoc
     */
    public function count()
    {
        if ($this->is(self::SET_OF_SIX())) {
            return 6;
        }
        return 4;
    }
}
