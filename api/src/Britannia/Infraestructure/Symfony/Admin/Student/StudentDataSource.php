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
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\VO\BankAccount\BankAccount;
use Britannia\Domain\VO\Payment\Payment;
use Britannia\Domain\VO\Student\Job\Job;
use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;

final class StudentDataSource extends AdminDataSource
{
    public function __invoke(Student $student): array
    {
        $data['Tipo'] = $this->parseType($student);
        $data['Estado'] = $this->parse($student->isActive(), ['si' => 'activo', 'no' => 'Inactivo']);
        $data['Nombre'] = $this->parse($student->fullName());
        $data['Cursos'] = $this->parseCourses($student);
        $data['Fec Nacimiento'] = $this->parse($student->birthDate());
        $data['Edad'] = $this->parse($student->age());
        $data['Dirección'] = $this->parse($student->address());
        $data['Emails'] = $this->parse($student->emails());
        $data['Teléfonos'] = $this->parse($student->phoneNumbers());
        $data['Familiares'] = $this->parse($student->relatives());
        $data['Tiene matricula gratis'] = $this->parse($student->isFreeEnrollment());
        $data['Modo de pago'] = $this->parseMode($student->payment());
        $data['Numero de cuenta'] = $this->parseAccount($student->payment());

        $data['Parte del día preferido'] = $this->parse($student->preferredPartOfDay());
        $data['Modo de contacto preferido'] = $this->parse($student->preferredContactMode());
        $data['Otra academia'] = $this->parse($student->academy());
        $data['Tiempo en otra academia'] = $this->parse($student->academyNumOfYears());

        $data['Primer Contacto'] = $this->parse($student->firstContact());
        $data['Observaciones 1'] = $this->parse($student->firstComment());
        $data['Observaciones 2'] = $this->parse($student->secondComment());
        $data['Acepta tárminos academia'] = $this->parse($student->isTermsOfUseAcademy());
        $data['Acepta tárminos estudiante'] = $this->parse($student->isTermsOfUseStudent());
        $data['Derechos de imagen'] = $this->parse($student->isTermsOfUseImageRigths());

        if ($student instanceof Child) {
            $data['Escuela'] = $this->parse($student->school());
            $data['Curso Escolar'] = $this->parse($student->schoolCourse());
            $data['Desc Tutor 1'] = $this->parse($student->firstTutorDescription());
            $data['Tutor 1'] = $this->parse($student->firstTutor());
            $data['Desc Tutor 2'] = $this->parse($student->secondTutorDescription());
            $data['Tutor 2'] = $this->parse($student->secondTutor());
        }

        if ($student instanceof Adult) {
            $data['DNI'] = $this->parse($student->dni());
            $data['Profesión'] = $this->parseJobName($student->job());
            $data['Situación laboral'] = $this->parseJobStatus($student->job());
        }

        return $data;
    }

    private function parseType(Student $student): string
    {
        return $student->isAdult() ? 'Adulto' : 'Niño';
    }

    private function parseCourses(Student $student): string
    {
        return $this->parse($student->studentHasCourses(), [
            'callback' => fn(StudentCourse $studentCourse) => $studentCourse->course()
        ]);
    }

    private function parseMode(?Payment $payment): string
    {
        if (!($payment instanceof Payment)) {
            return '';
        }

        return $this->parse($payment->getMode());
    }

    private function parseAccount(?Payment $payment)
    {
        if (!($payment instanceof Payment)) {
            return '';
        }

        $account = $payment->getAccount();
        if (!($account instanceof BankAccount)) {
            return '';
        }

        return $this->parse($account->getIban()->getPrintedFormat());
    }

    private function parseJobName(?Job $job)
    {
        if (!($job instanceof Job)) {
            return '';
        }

        return $this->parse($job->getName());
    }

    private function parseJobStatus(?Job $job)
    {
        if (!($job instanceof Job)) {
            return '';
        }

        return $this->parse($job->getStatus());
    }


}
