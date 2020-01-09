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
use Britannia\Domain\VO\Discount\StudentDiscount;

final class CourseInformation extends Report
{
    /**
     * @var array
     */
    private array $params;

    /**
     * @var array
     */
    private array $options;

    /**
     * @var string
     */
    private string $title;


    private function __construct(array $params, string $title)
    {
        $this->params = $params;
        $this->title = $title;
        $this->options = [
            'page-size' => 'A5'
        ];
    }

    public static function make(Course $course, ?StudentDiscount $discount, CourseInformationParamsGenerator $generator)
    {
        $params = $generator->generate($course, $discount);
        return new self($params, $course->name());
    }

    /**
     * @return array
     */
    public function params(): array
    {
        return $this->params;
    }

    /**
     * @return array
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

}
