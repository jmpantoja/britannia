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

namespace Britannia\Infraestructure\Symfony\Admin\Course;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\Course\Adult;
use Britannia\Domain\Entity\Course\Course\School;
use Britannia\Domain\Entity\Course\CourseAssessmentInterface;
use Britannia\Domain\Entity\Course\CourseCalendarInterface;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\VO\Course\TimeTable\Schedule;
use Britannia\Infraestructure\Symfony\Service\Schedule\ScheduleService;
use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;

final class CourseDataSource extends AdminDataSource
{
    /**
     * @var ScheduleService
     */
    private ScheduleService $scheduleService;

    public function setScheduleService(ScheduleService $scheduleService): self
    {
        $this->scheduleService = $scheduleService;
        return $this;
    }

    public function __invoke(Course $course)
    {
        $data['Tipo'] = $this->parseType($course);
        $data['Nombre'] = $this->parse($course->name());
        $data['Estado'] = $this->parse($course->status());
        $data['Núm. de plazas'] = $this->parse($course->numOfPlaces());
        $data['Mensualidad'] = $this->parse($course->monthlyPayment());
        $data['Matrícula'] = $this->parse($course->enrollmentPayment());
        $data['Profesores'] = $this->parseTeachers($course);
        $data['Alumnos'] = $this->parseStudents($course);
        $data['Material'] = $this->parse($course->books());
        $data['Inicio'] = $this->parse($course->start());
        $data['Fin'] = $this->parse($course->end());
        $data['Núm. de alumnos'] = $this->parse($course->numOfStudents());


        if ($course instanceof CourseCalendarInterface) {
            $data['Horario'] = $this->parseSchedule($course->schedule());
        }

        if($course instanceof CourseAssessmentInterface){
            $data['Destrezas'] = $this->parse($course->skills());
            $data['Destrezas Extra'] = $this->parse($course->otherSkills());
            $data['Num. Trimestres'] = $this->parse($course->numOfTerms());
            $data['Prueba de diagnóstico'] = $this->parse($course->hasDiagnosticTest());
            $data['Examen Final'] = $this->parse($course->hasFinalTest());
        }

        if ($course instanceof School) {
            $data['schoolCourse'] = $this->parse($course->schoolCourse());
        }

        if ($course instanceof Adult) {

            $data['intensive'] = $this->parse($course->intensive());
            $data['examiner'] = $this->parse($course->examiner());
            $data['level'] = $this->parse($course->level());
        }

        return $data;
    }

    private function parseType(Course $course): string
    {
        if ($course->isAdult()) {
            return 'Adultos';
        }

        if ($course->isSchool()) {
            return 'Escolar';
        }

        return 'PreEscolar';
    }

    private function parseTeachers(Course $course): string
    {
        return $this->parse($course->teachers(), [
            'callback' => fn(StaffMember $staffMember) => $staffMember->fullName()
        ]);
    }

    private function parseStudents(Course $course): string
    {
        return $this->parse($course->courseHasStudents(), [
            'callback' => fn(StudentCourse $studentCourse) => $studentCourse->student()
        ]);

    }

    private function parseSchedule(Schedule $schedule): string
    {
        $items = $this->scheduleService->resume($schedule);

        $schedule = [];
        foreach ($items as $name => $item) {
            $schedule[] = sprintf('%s %s', ...[
                $name,
                $item
            ]);
        }


        return $this->parse($schedule);
    }
}
