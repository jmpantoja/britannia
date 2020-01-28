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


use Britannia\Domain\Entity\Assessment\Term;
use Britannia\Domain\Entity\Student\StudentCourse;

final class CourseCertificate extends Report
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


    /**
     * @param StudentCourse $studentCourse
     * @return CourseCertificate
     */
    public static function make(StudentCourse $studentCourse): self
    {
        return new self($studentCourse);
    }

    private function __construct(StudentCourse $studentCourse)
    {
        $this->params = [
            'courseHasStudent' => $studentCourse
        ];

        $this->title = (string)$studentCourse->student();
        $this->options = [
            'page-size' => 'A4',
            'orientation' => 'Landscape',
        ];
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
