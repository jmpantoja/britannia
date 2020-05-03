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
 * @method static self NOT_ASSESSED()
 * @method static self FAIL()
 * @method static self BORDERLINE()
 * @method static self PASS()
 * @method static self PASS_WITH_MERIT()
 * @method static self PASS_WITH_DISTINCTION()
 */
final class MarkRange extends Enum
{
    private const NOT_ASSESSED = 'NOT_ASSESSED';
    private const FAIL = 'FAIL';
    private const BORDERLINE = 'BORDERLINE';
    private const PASS = 'PASS';
    private const PASS_WITH_MERIT = 'PASS_WITH_MERIT';
    private const PASS_WITH_DISTINCTION = 'PASS_WITH_DISTINCTION';

    public static function make(?float $mark): self
    {
        if (is_null($mark)) {
            return self::NOT_ASSESSED();
        }
        if ($mark < 5) {
            return self::FAIL();
        }
        if ($mark < 7) {
            return self::BORDERLINE();
        }
        if ($mark < 8.5) {
            return self::PASS();
        }
        if ($mark < 9.5) {
            return self::PASS_WITH_MERIT();
        }

        return self::PASS_WITH_DISTINCTION();
    }

}
