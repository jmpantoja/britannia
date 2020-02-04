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

namespace Britannia\Infraestructure\Symfony\Admin\Student;


use Britannia\Domain\Entity\Student\Adult;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Infraestructure\Symfony\Form\Type\Student\ContactModeType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\JobType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\OtherAcademyType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\PartOfDayType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\PaymentType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\RelativesType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\StudentHasCoursesType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\TutorType;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Symfony\Form\Type\DNIType;
use PlanB\DDDBundle\Symfony\Form\Type\EmailListType;
use PlanB\DDDBundle\Symfony\Form\Type\FullNameType;
use PlanB\DDDBundle\Symfony\Form\Type\PhoneNumberListType;
use PlanB\DDDBundle\Symfony\Form\Type\PostalAddressType;
use PlanB\DDDBundle\Symfony\Form\Type\WYSIWYGType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Sonata\Form\Type\BooleanType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class StudentForm extends AdminForm
{
    public function configure(Student $student)
    {
        $isAdult = $student instanceof Adult;

        if($this->dataMapper() instanceof StudentMapper){
            $this->dataMapper()->setAdult($isAdult);
        }

        $this->fistTab('Personal', $isAdult);
        $this->secondTab('Cursos', $student);
        $this->thirdTab('Pago', $student);
        $this->fourthTab('Tutores', $isAdult);
        $this->fifthTab('Observaciones');
        $this->sixthTab('Información Extra');
    }

    /**
     * @param bool $isAdult
     */
    protected function fistTab(string $name, bool $isAdult): void
    {
        $this->tab($name);

        $group = $this->group('Datos personales', ['class' => 'col-md-4'])
            ->add('fullName', FullNameType::class, [
                'sonata_help' => 'Escriba el nombre y apellidos del alumno',
                'label' => 'Nombre y apellidos',
                'required' => true
            ])
            ->add('birthDate', DatePickerType::class, [
                'label' => 'Fecha de nacimiento',
                'format' => \IntlDateFormatter::LONG,
                'required' => false
            ]);

        if ($isAdult) {
            $group->add('dni', DNIType::class, [
                'empty_data' => null,
                'required' => false
            ]);
        }

        $this->group('Contacto', ['class' => 'col-md-4'])
            ->add('address', PostalAddressType::class, [
                'sonata_help' => 'Escriba una dirección postal',
                'label' => 'Dirección Postal',
                'required' => false,
            ])
            ->add('emails', EmailListType::class, [
                'required' => false,
                'label' => 'Correos Electrónicos',
            ])
            ->add('phoneNumbers', PhoneNumberListType::class, [
                'required' => false,
                'label' => 'Números de teléfono',
            ]);


        $group = $this->group($isAdult ? 'Profesión' : 'Colegio', ['class' => 'col-md-4']);

        if ($isAdult) {
            $group->add('job', JobType::class, [
                'sonata_help' => 'Escriba una ocupación y una situación laboral',
                'required' => false,
                'label' => 'Ocupación'
            ]);
        }

        if (!$isAdult) {
            $group
                ->add('school', ModelType::class, [
                    'label' => 'Colegio',
                    'btn_add' => 'Nuevo colegio',
                    'placeholder' => ''
                ])
                ->add('schoolCourse', TextType::class, [
                    'label' => 'Próximo curso escolar',
                    'required' => false
                ]);
        }
    }

    /**
     * @param Student $student
     */
    protected function secondTab(string $name, Student $student): void
    {
        $this->tab($name);

        $this->group('Cursos en Activo ', ['class' => 'col-md-12'])
            ->add('studentHasCourses', StudentHasCoursesType::class, [
                'student' => $student,
            ]);
    }

    /**
     * @param Student $student
     */
    protected function thirdTab(string $name, Student $student): void
    {
        $this->tab($name);
        $this->group('Descuento', ['class' => 'col-md-6'])
            ->add('freeEnrollment', BooleanType::class, [
                'transform' => true,
                'attr' => [
                    'style' => 'width:100px'
                ]
            ])
            ->add('relatives', RelativesType::class, [
                'btn_add' => false,
                'label' => 'Familiares',
                'studentId' => $student->id(),
                'required' => false
            ]);

        $this->group('Forma de pago', ['class' => 'col-md-6'])
            ->add('payment', PaymentType::class, [
                'label' => false,
                'required' => true
            ]);
    }

    /**
     * @param bool $isAdult
     */
    protected function fourthTab(string $name, bool $isAdult): void
    {
        if (!$isAdult) {
            $this->tab($name);

            $this->group('Tutores')
                ->add('firstTutorDescription', TextType::class, [
                    'label' => 'Tipo',
                    'sonata_help' => 'padre/madre/abuelo/hermano/...',
                    'required' => false
                ])
                ->add('firstTutor', TutorType::class, [
                    'label' => 'Tutor A',
                    'sonata_help' => 'seleccione un tutor',
                    'required' => true
                ])
                ->add('secondTutorDescription', TextType::class, [
                    'label' => 'Tipo',
                    'sonata_help' => 'padre/madre/abuelo/hermano/...',
                    'required' => false
                ])
                ->add('secondTutor', TutorType::class, [
                    'label' => 'Tutor B',
                    'sonata_help' => 'seleccione un tutor',
                    'required' => false
                ]);
        }
    }

    protected function fifthTab($name): void
    {
        $this->tab($name);

        $this->group('Observaciones A', ['class' => 'col-md-6'])
            ->add('firstComment', WYSIWYGType::class, [
                'label' => false
            ]);
        $this->group('Observaciones B', ['class' => 'col-md-6'])
            ->add('secondComment', WYSIWYGType::class, [
                'label' => false
            ]);
    }

    protected function sixthTab($name): void
    {
        $this->tab($name);

        $this->group('Preferencias', ['class' => 'col-md-4'])
            ->add('preferredPartOfDay', PartOfDayType::class, [
                'label' => 'Horario'
            ])
            ->add('preferredContactMode', ContactModeType::class, [
                'label' => 'Contacto'
            ]);

        $this->group('Estadísticas', ['class' => 'col-md-4'])
            ->add('otherAcademy', OtherAcademyType::class, [
                'required' => false,
                'label' => 'Ha estudiado antes en...',
                'sonata_admin' => $this
            ])
            ->add('firstContact', TextType::class, [
                'required' => false,
                'label' => '¿Como nos conociste?.'
            ]);

        $this->group('Condiciones', ['class' => 'col-md-4'])
            ->add('termsOfUseAcademy', null, [
                'label' => 'Acepta las condiciones de uso de la academia'
            ])
            ->add('termsOfUseStudent', null, [
                'label' => 'Acepta las condiciones de uso de la academia'
            ])
            ->add('termsOfUseImageRigths', null, [
                'label' => 'Consentimiento de Imagen'
            ]);
    }
}
