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

use Britannia\Domain\VO\Course\Locked\Locked;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

if (!function_exists('date_to_format')) {
    function date_to_format(DateTimeInterface $date,
                            string $format = ''

    ): string
    {
        return date_to_string($date, \IntlDateFormatter::NONE, \IntlDateFormatter::NONE, $format);
    }
}

if (!function_exists('date_to_string')) {
    function date_to_string(DateTimeInterface $date,
                            int $dateType = \IntlDateFormatter::LONG,
                            int $timeType = \IntlDateFormatter::NONE,
                            string $format = ''

    ): string
    {
        $date = Carbon::make($date);

        $formatter = \IntlDateFormatter::create(Locale::getDefault(), $dateType, $timeType, null, null, $format);

        return $formatter->format($date);
    }
}


if (!function_exists('string_to_date')) {
    function string_to_date(string $value,
                            int $dateType = \IntlDateFormatter::LONG,
                            int $timeType = \IntlDateFormatter::NONE,
                            string $format = ''
    ): CarbonImmutable
    {
        $formatter = \IntlDateFormatter::create(Locale::getDefault(), $dateType, $timeType, null, null, $format);
        $timeStamp = $formatter->parse($value);

        return CarbonImmutable::parse($timeStamp);
    }
}
