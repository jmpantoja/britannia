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
use Britannia\Domain\Entity\Course\Course\OneToOne;
use Britannia\Domain\Entity\Course\CourseAssessmentInterface;
use Britannia\Domain\Entity\Course\CourseCalendarInterface;
use Britannia\Domain\Entity\Course\CourseId;
use Britannia\Domain\Entity\Course\CoursePaymentInterface;
use Britannia\Domain\Entity\Level\Level;
use Britannia\Domain\Entity\Setting\Setting;
use Britannia\Infraestructure\Symfony\Form\Type\Assessment\AssessmentType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\AgeType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\CourseHasBooksType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\CourseHasStudentsType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\Discount\JobStatusDiscountListType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\EnrollmentPaymentType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\ExaminerType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\IntensiveType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\LevelType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\OneToOne\PassListType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\PeriodicityType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\SchoolCourseListType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\SupportType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\TeachersType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\TimeTable\TimeTableType;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Symfony\Form\Type\PositiveIntegerType;
use PlanB\DDDBundle\Symfony\Form\Type\PriceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

final class CourseForm extends AdminForm
{

    /**
     * @var Course
     */
    private $course;
    /**
     * @var Setting
     */
    private Setting $setting;


    public function setSettings(Setting $setting): self
    {
        $this->setting = $setting;
        return $this;

    }

    public function configure(Course $course): self
    {
        $this->course = $course;

        $this->dataMapper()->setSubject($course);

        $this->cardTab('Ficha del curso', $course);
        $this->calendarTab('Calendario', $course);
        $this->priceTab('Precio', $course);
        $this->passTab('Bonos', $course);
        $this->studentsTab('Alumnos y profesores', $course);

        return $this;
    }

    /**
     * @param $name
     * @return CourseForm
     */
    private function cardTab($name, Course $course): self
    {
        $this->tab($name);

        $this->group('Nombre', ['class' => 'col-md-3 '])
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nombre',
                'attr' => [
                    'style' => 'width:250px'
                ],
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('description', TextType::class, [
                'required' => false,
                'label' => 'Descripción',
            ]);

        $this->group('Curso', ['class' => 'col-md-3'])
            ->add('numOfPlaces', PositiveIntegerType::class, [
                'label' => 'Plazas',
                'required' => true
            ]);

        if ($course->isOnetoOne()) {
            $this->group('Coste', ['class' => 'col-md-3'])
                ->add('enrollmentPayment', EnrollmentPaymentType::class, [
                    'label' => 'Matrícula'
                ]);
        }


        if ($course->isSchool()) {
            $this->group('Curso', ['class' => 'col-md-3'])
                ->add('schoolCourses', SchoolCourseListType::class, [
                    'label' => 'Curso Escolar'
                ]);
        }

        if ($course->isAdult()) {
            $this->group('Curso', ['class' => 'col-md-3'])
                ->add('intensive', IntensiveType::class, [
                    'label' => 'Intensivo'
                ])
                ->add('examiner', ExaminerType::class, [
                    'label' => 'Examinador'
                ])
                ->add('level', LevelType::class, [
                    'required' => false,
                    'label' => 'Nivel',
                ]);
        }

        if (!($course instanceof CourseAssessmentInterface)) {
            return $this;
        }

        $this->tab($name);

        $this->group('Evaluación', ['class' => 'col-md-6'])
            ->add('assessment', AssessmentType::class, [
                'label' => false,
                'data' => $course->assessment()
            ]);

        return $this;
    }

    private function studentsTab(string $name, Course $course): self
    {
        if(!($course->id() instanceof CourseId)){
            return $this;
        }

        $this->tab($name);

        $this->group('Profesores', ['class' => 'col-md-5'])
            ->add('teachers', TeachersType::class, [
                'btn_add' => false,
                'label' => false
            ]);

        $this->group('Alumnos', ['class' => 'col-md-7'])
            ->add('courseHasStudents', CourseHasStudentsType::class, [
                'course' => $this->course,
                'label' => false
            ]);

        return $this;
    }

    private function calendarTab(string $name, Course $course): self
    {
        if (!($course instanceof CourseCalendarInterface)) {
            return $this;
        }

        $this->tab($name);

        $this->group('Fechas', ['class' => 'col-md-7 box-with-locked'])
            ->add('timeTable', TimeTableType::class, [
                'mapped' => false,
                'label' => false,
                'course' => $this->course
            ]);

        return $this;
    }

    private function priceTab(string $name, Course $course): self
    {
        if (!($course instanceof CoursePaymentInterface) or $course instanceof OneToOne) {
            return $this;
        }

        $this->tab($name);
        $this->group('Coste', ['class' => 'col-md-6'])
            ->add('enrollmentPayment', EnrollmentPaymentType::class, [
                'label' => 'Matrícula',
                'empty_data' => $this->setting->enrollmentPayment()
            ])
            ->add('monthlyPayment', PriceType::class, [
                'label' => 'Mensualidad',
                'empty_data' => $this->setting->monthlyPayment()

            ])
            ->add('books', CourseHasBooksType::class, [
                'label' => 'Material',
            ]);

        $this->group('Descuentos', ['class' => 'col-md-6'])
            ->add('discount', JobStatusDiscountListType::class, [
                'label' => false
            ]);

        return $this;
    }

    private function passTab(string $name, Course $course): self
    {
        if (!($course instanceof OneToOne)) {
            return $this;
        }

        if(!($course->id() instanceof CourseId)){
            return $this;
        }


        $this->tab($name);
        $this->group('Bonos', ['class' => 'col-md-8'])
            ->add('passes', PassListType::class, [
                'label' => false,
                'course' => $course
            ]);

        return $this;
    }
}
