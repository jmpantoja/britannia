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
use Britannia\Infraestructure\Symfony\Form\Type\Assessment\AssessmentDefinitionType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\AgeType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\CourseHasStudentsType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\Discount\DiscountListTye;
use Britannia\Infraestructure\Symfony\Form\Type\Course\ExaminerType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\IntensiveType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\LevelType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\PeriodicityType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\SupportType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\TeachersType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\TimeTable\TimeTableType;
use PlanB\DDD\Domain\VO\Price;
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
     * @var Price
     */
    private Price $enrollmentPrice;

    public function configure(Course $course): self
    {
        $this->course = $course;

        $this->dataMapper()->setSubject($course);

        $this->cardTab('Ficha del curso', $course);
        $this->calendarTab('Calendario');
        $this->priceTab('Precio');
        $this->studentsTab('Alumnos y profesores');
        $this->unitsTab('Evaluación');

        return $this;
    }

    /**
     * @param $name
     * @return CourseForm
     */
    private function cardTab($name, Course $course): self
    {
        $this->tab($name);

        $this->group('Curso', ['class' => 'col-md-12 horizontal'])
            ->add('name', TextType::class, [
                'required' => true,
                'label' => 'Nombre',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('support', SupportType::class, [
                'label' => '¿Es de apoyo?',
                'required' => true
            ])
            ->add('numOfPlaces', PositiveIntegerType::class, [
                'label' => 'Plazas',
                'required' => true
            ]);


        if (!$course->isAdult()) {
            $this->group('Descripción', ['class' => 'col-md-12 horizontal'])
                ->add('schoolCourse');
        }

        if ($course->isAdult()) {

            $this->group('Descripción', ['class' => 'col-md-12 horizontal'])
                ->add('intensive', IntensiveType::class, [
                    'label' => '¿Es intensivo?'
                ]);

            $this->group('Titulación', ['class' => 'col-md-12 horizontal'])
                ->add('examiner', ExaminerType::class, [
                    'label' => 'Examinador'
                ])
                ->add('level', LevelType::class, [
                    'required' => false,
                    'label' => 'Nivel'
                ]);
        }

        return $this;
    }

    private function studentsTab(string $name): self
    {
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

    private function calendarTab(string $name): self
    {
        $this->tab($name);

        $this->group('Fechas', ['class' => 'col-md-7 box-with-locked'])
            ->add('timeTable', TimeTableType::class, [
                'label' => false,
                'course' => $this->course
            ]);

        return $this;
    }

    private function unitsTab(string $name): self
    {
        $this->tab($name);

        $this->group('Evaluación', ['class' => 'col-md-6'])
            ->add('assessmentDefinition', AssessmentDefinitionType::class, [
                'label' => false,
                'course' => $this->course
            ]);

        return $this;
    }

    private function priceTab(string $name): self
    {
        $this->tab($name);
        $this->group('Coste', ['class' => 'col-md-6'])
            ->add('enrollmentPayment', PriceType::class, [
                'label' => 'Matrícula',
                'empty_data' => $this->enrollmentPrice,
                'attr' => [
                    'readonly' => true
                ]
                // 'disabled' => true
            ])
            ->add('monthlyPayment', PriceType::class, [
                'label' => 'Mensualidad',
            ])
            ->add('books', null, [
                'label' => 'Material'
            ]);

        $this->group('Descuentos', ['class' => 'col-md-6'])
            ->add('discount', DiscountListTye::class, [
                'label' => false,
            ]);

        return $this;
    }

    public function setEnrollmentPrice(Price $enrollmentPrice): self
    {
        $this->enrollmentPrice = $enrollmentPrice;
        return $this;
    }

}

