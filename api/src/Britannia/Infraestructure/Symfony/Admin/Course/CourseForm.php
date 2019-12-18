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
use Britannia\Infraestructure\Symfony\Form\Type\Unit\UnitsDefinitionType;
use PlanB\DDD\Domain\VO\Price;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Symfony\Form\Type\PositiveIntegerType;
use PlanB\DDDBundle\Symfony\Form\Type\PriceType;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints\NotBlank;

class CourseForm extends AdminForm
{

    /**
     * @var Course
     */
    private $course;

    public static function make(FormMapper $mapper): self
    {
        return new self($mapper);
    }

    private function __construct(FormMapper $mapper)
    {
        parent::__construct($mapper);
    }

    public function configure(Course $course): self
    {
        $this->course = $course;

        $this->firstTab('Ficha del curso');
        $this->secondTab('Alumnos y profesores');
        $this->thirdTab('Calendario');
        $this->fourthTab('Unidades');
//        $this->fifthTab('Precio')
        ;

        return $this;
    }

    /**
     * @param $name
     * @return CourseForm
     */
    private function firstTab($name): self
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
            ]);

        $this->group('Descripción', ['class' => 'col-md-12 horizontal'])
            ->add('schoolCourse')
            ->add('periodicity', PeriodicityType::class, [
                'label' => 'Periocidad'
            ])
            ->add('intensive', IntensiveType::class, [
                'label' => '¿Es intensivo?'
            ])
            ->add('age', AgeType::class, [
                'label' => 'Grupo de Edad'
            ]);

        $this->group('Titulación', ['class' => 'col-md-12 horizontal'])
            ->add('examiner', ExaminerType::class, [
                'label' => 'Examinador'
            ])
            ->add('level', LevelType::class, [
                'required' => false,
                'label' => 'Nivel'
            ]);

        return $this;
    }

    private function secondTab(string $name): self
    {
        $this->tab($name);

        $this->group('Profesores', ['class' => 'col-md-5'])
            ->add('teachers', TeachersType::class);

        $this->group('Alumnos', ['class' => 'col-md-7'])
            ->add('courseHasStudents', CourseHasStudentsType::class, [
                'course' => $this->course
            ]);

        return $this;
    }

    private function thirdTab(string $name): self
    {
        $this->tab($name);

        $this->group('Fechas', ['class' => 'col-md-7 box-with-locked'])
            ->add('timeTable', TimeTableType::class, [
                'label' => false,
                'course' => $this->course
            ]);

        return $this;
    }

    private function fourthTab(string $name): self
    {
        $this->tab($name);

        $this->group('Unidades', ['class' => 'col-md-7  box-with-locked'])
            ->add('unitsDefinition', UnitsDefinitionType::class, [
                'label' => false,
                'course' => $this->course
            ]);

        return $this;
    }

    private function fifthTab(string $name): self
    {
        $this->tab($name);


        $this->group('Coste', ['class' => 'col-md-6'])
            ->add('enrollmentPayment', PriceType::class, [
                'label' => 'Matrícula',
                'empty_data' => Price::make(50)
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

}

