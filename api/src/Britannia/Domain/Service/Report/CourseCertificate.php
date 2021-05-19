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
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourse;
use Carbon\CarbonImmutable;

final class CourseCertificate extends TemplateBasedPdfReport
{
    /**
     * @var string
     */
    private string $title;
    /**
     * @var Student
     */
    private Student $student;
    /**
     * @var Course
     */
    private Course $course;
    /**
     * @var \Britannia\Domain\VO\Assessment\MarkReport
     */
    private \Britannia\Domain\VO\Assessment\MarkReport $marks;


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
        $this->title = (string)$studentCourse->student();

        $this->student = $studentCourse->student();
        $this->course = $studentCourse->course();
        $this->marks = $studentCourse->marks();
        $this->final = $studentCourse->final();

    }

    /**
     * @return array
     */
    public function params(): array
    {
        $params = [
            'Name' => $this->student,
            'Text2' => ucwords(strtolower((string)$this->course)),
            'Text3' => $this->length($this->course),
            'Text4' => $this->dateFormat($this->course->start()),
            'Text5' => $this->dateFormat($this->course->end()),
            'Text6' => $this->final,
            'Text7' => $this->dateFormat(CarbonImmutable::today()),

            '47' => $this->mark('grammar'),
            '68' => $this->mark('vocabulary'),
            'Text12' => $this->mark('speaking'),
            'Text13' => $this->mark('listening'),
            'Text14' => $this->mark('reading'),
            'Text15' => $this->mark('writing'),

            'TRTE' => $this->range('grammar'),
            'GWEGEW' => $this->range('vocabulary'),
            'Text8' => $this->range('speaking'),
            'Text9' => $this->range('listening'),
            'Text10' => $this->range('reading'),
            'Text11' => $this->range('writing'),
        ];

        return $params;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }

    private function dateFormat(CarbonImmutable $date)
    {
        return $date->format('F Y');
    }

    private function length(Course $course)
    {
        $numOfMonths = $course->end()->diffInMonths($course->start());
        if ($numOfMonths >= 2) {
            return sprintf('%s months', $numOfMonths);
        }
        return '1 month';
    }

    private function mark(string $name): ?string
    {
        if (!$this->availableSkill($name)) {
            return null;
        }

        return $this->marks->get($name)->format();
    }

    private function range(string $name): ?string
    {
        if (!$this->availableSkill($name)) {
            return null;
        }
        $range = $this->marks->get($name)->range()->getValue();

        return ucwords(strtolower($range));
    }

    /**
     * @param string $name
     * @return mixed
     */
    private function availableSkill(string $name): bool
    {
        return collect($this->course->skills()->toList())
            ->filter(fn(string $skill) => $skill === $name)
            ->isNotEmpty();

    }

}
