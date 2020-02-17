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

namespace Britannia\Domain\Service\Assessment;


use Britannia\Domain\Entity\Assessment\Term;
use Britannia\Domain\Entity\Assessment\TermList;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\Entity\Student\StudentCourseList;
use Britannia\Domain\VO\Course\Assessment\Assessment;

final class AssessmentGenerator
{

    public function generateTerms(StudentCourseList $courseHasStudentList, Assessment $definition): TermList
    {
        $definition->skills();

        $data = [];
        foreach ($courseHasStudentList as $studentCourse) {
            $data[] = $this->generateTermsForStudent($studentCourse, $definition);
        }

        $input = array_merge(...$data);
        return TermList::collect($input);
    }

    private function generateTermsForStudent(StudentCourse $studentCourse, Assessment $definition): array
    {
        $data = [];

        $course = $studentCourse->course();

        foreach ($definition->termNames() as $termName) {
            $termDefinition = $course->termDefinition($termName);
            $data[] = Term::make($studentCourse, $termName, $definition->skills())
                ->updateDefintion($termDefinition);
        }

        return $data;
    }

}
