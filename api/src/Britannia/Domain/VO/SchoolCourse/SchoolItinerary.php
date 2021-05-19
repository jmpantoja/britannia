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

namespace Britannia\Domain\VO\SchoolCourse;


use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self EPO()
 * @method static self ESO()
 * @method static self BACHILLERATO()
 */
final class SchoolItinerary extends Enum
{
    private const PRE_1 = ['course' => 1, 'level' => 'PRE'];
    private const PRE_2 = ['course' => 2, 'level' => 'PRE'];
    private const PRE_3 = ['course' => 3, 'level' => 'PRE'];

    private const EPO_1 = ['course' => 1, 'level' => 'EPO'];
    private const EPO_2 = ['course' => 2, 'level' => 'EPO'];
    private const EPO_3 = ['course' => 3, 'level' => 'EPO'];
    private const EPO_4 = ['course' => 4, 'level' => 'EPO'];
    private const EPO_5 = ['course' => 5, 'level' => 'EPO'];
    private const EPO_6 = ['course' => 6, 'level' => 'EPO'];

    private const ESO_1 = ['course' => 1, 'level' => 'ESO'];
    private const ESO_2 = ['course' => 2, 'level' => 'ESO'];
    private const ESO_3 = ['course' => 3, 'level' => 'ESO'];
    private const ESO_4 = ['course' => 4, 'level' => 'ESO'];

    private const BACH_1 = ['course' => 1, 'level' => 'BACH'];
    private const BACH_2 = ['course' => 2, 'level' => 'BACH'];

    private const FP_1 = ['course' => 1, 'level' => 'FP'];
    private const FP_2 = ['course' => 2, 'level' => 'FP'];

    public static function byAge(int $age, int $numOfFailedCourses = 0)
    {
        $courses = self::courses();
        $min = $age - $numOfFailedCourses;
        $max = $min + 1;

        return $courses->filter(function (SchoolCourse $course) use ($max, $min) {
            $age = $course->age();
            return $min <= $age && $age <= $max;
        });
    }

    public static function schoolCourseByKey(string $key): SchoolCourse
    {
        $data = static::byName($key)->value;
        return SchoolCourse::fromArray($data);
    }

    /**
     * @return \Tightenco\Collect\Support\Collection
     */
    public static function courses(): \Tightenco\Collect\Support\Collection
    {
        $courses = collect(static::toArray())
            ->map(fn($data) => SchoolCourse::fromArray($data));

        return $courses;
    }
}
