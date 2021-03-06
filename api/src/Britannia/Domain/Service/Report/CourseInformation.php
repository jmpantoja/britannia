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

namespace Britannia\Domain\Service\Report;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Setting\Setting;
use Britannia\Domain\VO\Discount\StudentDiscount;

final class CourseInformation extends HtmlBasedPdfReport
{
    public static function make(Course $course,
                                StudentDiscount $discount,
                                CourseInformationParamsGenerator $generator,
                                Setting $setting)
    {
        $params = $generator->generate($course, $discount);
        $params['setting'] = $setting;

        return new self($course->name(), $params, [
            'page-size' => 'A5'
        ]);
    }

}
