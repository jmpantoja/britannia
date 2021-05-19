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

namespace Britannia\Infraestructure\Symfony\Admin\TeacherCourse;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Repository\LessonRepositoryInterface;
use Britannia\Infraestructure\Symfony\Form\Type\Attendance\AttendanceListType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\AgeType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\PeriodicityType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\SupportType;
use Carbon\CarbonImmutable;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;

final class TeacherCourseForm extends AdminForm
{

    /**
     * @var Course
     */
    private $course;
    /**
     * @var CarbonImmutable
     */
    private CarbonImmutable $date;
    /**
     * @var LessonRepositoryInterface
     */
    private LessonRepositoryInterface $lessonRepository;


    public function setDate(CarbonImmutable $date): self
    {
        $this->date = $date;
        return $this;
    }

    public function setLessonRepository(LessonRepositoryInterface $lessonRepository): self
    {
        $this->lessonRepository = $lessonRepository;
        return $this;
    }

    public function configure(Course $course): self
    {
        $this->course = $course;
        $lesson = $this->lessonRepository->findByCourseAndDay($this->course, $this->date);

        $this->dataMapper()->setSubject($lesson);

        $this->attendanceTab('Asistencia', $lesson);

        return $this;
    }

    private function attendanceTab(string $name, ?Lesson $lesson): self
    {
        $this->tab($name);

        $label = $this->getLabel($lesson);
        $this->group('grid', [
            'label' => $label
        ])
            ->add('attendances', AttendanceListType::class, [
                'mapped' => false,
                'lesson' => $lesson,
                'course' => $this->course,
                'date' => $this->date,
                'label' => false,
            ]);

        return $this;
    }

    /**
     * @param Lesson|null $lesson
     * @return string
     */
    private function getLabel(?Lesson $lesson): string
    {
        $desc = '(No hay clase)';
        if ($lesson instanceof Lesson) {
            $desc = sprintf('(de %s a %s)', ...[
                $lesson->startTime()->format('H:i'),
                $lesson->endTime()->format('H:i')
            ]);
        }

        $today = date_to_string($this->date, \IntlDateFormatter::FULL);
        $label = sprintf('%s %s', $today, $desc);
        return $label;
    }

}

