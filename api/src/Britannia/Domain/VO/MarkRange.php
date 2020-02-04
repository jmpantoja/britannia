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

namespace Britannia\Domain\VO;


use MabeEnum\Enum;

final class MarkRange extends Enum
{
    public const NOT_ASSESSED = 'NOT_ASSESSED';
    public const FAIL = 'FAIL';
    public const BORDERLINE = 'BORDERLINE';
    public const PASS = 'PASS';
    public const PASS_WITH_MERIT = 'PASS_WITH_MERIT';
    public const PASS_WITH_DISTINCTION = 'PASS_WITH_DISTINCTION';

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
