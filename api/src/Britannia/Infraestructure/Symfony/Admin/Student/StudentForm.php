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
use Britannia\Domain\Entity\Student\Child;
use Britannia\Domain\Entity\Student\Photo;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentId;
use Britannia\Infraestructure\Symfony\Form\Type\Date\DateType;
use Britannia\Infraestructure\Symfony\Form\Type\Photo\PhotoType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\AlertType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\AttachmentListType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\ContactModeType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\DocumentListType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\JobType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\OtherAcademyType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\PartOfDayType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\PaymentType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\RelativesType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\SchoolCourseType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\SchoolType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\StudentHasCoursesType;
use Britannia\Infraestructure\Symfony\Form\Type\Tutor\ChoiceTutorType;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Symfony\Form\Type\DNIType;
use PlanB\DDDBundle\Symfony\Form\Type\EmailListType;
use PlanB\DDDBundle\Symfony\Form\Type\FullNameType;
use PlanB\DDDBundle\Symfony\Form\Type\PhoneNumberListType;
use PlanB\DDDBundle\Symfony\Form\Type\PostalAddressType;
use PlanB\DDDBundle\Symfony\Form\Type\WYSIWYGType;
use Sonata\Form\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

final class StudentForm extends AdminForm
{
    public function configure(Student $student)
    {
        $isAdult = $student instanceof Adult;

        if ($this->dataMapper() instanceof StudentMapper) {
            $this->dataMapper()->setAdult($isAdult);
        }

        $this->contactTab('Contacto', $student);
        $this->personalTab('Personal', $student);

        $this->coursesTab('Cursos', $student);
        $this->paymentTab('Pago', $student);

        if ($student instanceof Child) {
            $this->firstTutorTab('Tutor 1', $student);
            $this->secondTutorTab('Tutor 2', $student);
        }

        $this->attachedTab('Documentos', $student);
        $this->issuesTab('Observaciones');
    }

    protected function contactTab(string $name, Student $student): void
    {
        $this->tab($name);
        $isAdult = $student instanceof Adult;

        $this->group('Foto', ['class' => 'col-md-3'])
            ->add('photo', PhotoType::class, [
                'label' => false,
                'owner' => $student,
                'data_class' => Photo::class
            ]);

        $group = $this->group('Datos personales', ['class' => 'col-md-4'])
            ->add('fullName', FullNameType::class, [
                'sonata_help' => 'Escriba el nombre y apellidos del alumno',
                'label' => 'Nombre y apellidos',
                'required' => true
            ])
            ->add('birthDate', DateType::class, [
                'label' => 'Fecha de nacimiento',
                'required' => true,
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
    }

    protected function personalTab(string $name, Student $student): void
    {
        $isAdult = $student instanceof Adult;

        $this->tab($name);

        $this->group('Preferencias', ['class' => 'col-md-2'])
            ->add('preferredPartOfDay', PartOfDayType::class, [
                'label' => 'Horario'
            ])
            ->add('preferredContactMode', ContactModeType::class, [
                'label' => 'Contacto'
            ]);

        $this->group('Estadísticas', ['class' => 'col-md-3'])
            ->add('otherAcademy', OtherAcademyType::class, [
                'required' => false,
                'label' => 'Ha estudiado antes en...',
                'sonata_admin' => $this
            ])
            ->add('firstContact', TextType::class, [
                'required' => false,
                'label' => '¿Como nos conociste?.'
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
                ->add('school', SchoolType::class, [
                    'label' => 'Escuela',
                    'btn_add' => false
                ])
                ->add('schoolHistory', SchoolCourseType::class, [
                    'label' => 'Curso escolar',
                    'required' => true,
                    'data' => $student->schoolHistory(),
                    'birthDay' => $student->birthDate()
                ]);
        }
    }

    /**
     * @param Student $student
     */
    protected function coursesTab(string $name, Student $student): void
    {
        if (!($student->id() instanceof StudentId)) {
            return;
        }

        $this->tab($name);

        $this->group('Cursos en Activo ', ['class' => 'col-md-12'])
            ->add('studentHasCourses', StudentHasCoursesType::class, [
                'label' => false,
                'student' => $student
            ]);
    }

    /**
     * @param Student $student
     */
    protected function paymentTab(string $name, Student $student): void
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
    protected function firstTutorTab(string $name, Child $student): void
    {
        $this->tab($name);
        $this
            ->group($name)
            ->add('firstTutor', ChoiceTutorType::class, [
                'label' => false,
                'tutor' => $student->firstTutor(),
                'description' => $student->firstTutorDescription(),
                'required' => true
            ]);
    }

    protected function secondTutorTab(string $tabName, Child $student)
    {
        $this->tab($tabName);
        $this
            ->group($tabName)
            ->add('secondTutor', ChoiceTutorType::class, [
                'label' => false,
                'tutor' => $student->secondTutor(),
                'description' => $student->secondTutorDescription(),
                'required' => false
            ]);
    }

    private function attachedTab(string $name, Student $student): void
    {
        if ($student->id() == null) {
            return;
        }
        $this->tab($name);

        $this->group('Adjuntos', ['class' => 'col-md-7'])
            ->add('attachments', AttachmentListType::class, [
                'student' => $student,
                'label' => false
            ]);

        $this->group('Documentos', ['class' => 'col-md-3 col-md-push-1'])
            ->add('documents', DocumentListType::class, [
                'student' => $student,
                'label' => false
            ]);
    }

    private function issuesTab(string $name)
    {
        $this->tab($name);

        $this->group('¡Cuidado!', ['class' => 'col-md-4'])
            ->add('alert', AlertType::class, [
                'label' => false,
                'required' => false
            ]);

        $this->group('Otros datos de Interes', ['class' => 'col-md-5'])
            ->add('comment', WYSIWYGType::class, [
                'label' => false
            ]);

        $this->group('Condiciones', ['class' => 'col-md-3'])
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
