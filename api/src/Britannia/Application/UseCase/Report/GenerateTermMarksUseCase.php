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

namespace Britannia\Application\UseCase\Report;


use Britannia\Domain\Entity\Assessment\Term;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Repository\TermsParametersInterface;
use Britannia\Domain\Service\Report\CourseTermMarks;
use Britannia\Domain\Service\Report\ReportList;
use Britannia\Infraestructure\Symfony\Service\Assessment\TermParameters;
use PlanB\DDD\Application\UseCase\UseCaseInterface;

class GenerateTermMarksUseCase implements UseCaseInterface
{

    /**
     * @var TermsParametersInterface
     */
    private TermsParametersInterface $termsParameters;

    public function __construct(TermsParametersInterface $termsParameters)
    {
        $this->termsParameters = $termsParameters;

//        dump(...[
//            'VOY POR AQUI',
//            'este servicio (termsParameters) me dice cuando empieza y cuando termina un trimestre'
//        ]);
//
//        die(__METHOD__);
    }

    public function handle(GenerateTermMarks $command)
    {
        /** @var Course $course */
        $course = $command->course();
        $termName = $command->termName();
        $students = $command->students();

        $studentsKeys = collect($students)
            ->map(fn(Student $student) => (string)$student->id())
            ->toArray();

        $terms = collect($course->terms())
            ->filter(function (Term $term) use ($termName, $studentsKeys) {
                $studentsKey = (string)$term->student()->id();
                return $term->termName()->is($termName) && in_array($studentsKey, $studentsKeys);
            })
            ->map(fn(Term $term) => CourseTermMarks::make($term))
            ->toArray();

        return ReportList::make($course->name(), $terms);
    }

}
