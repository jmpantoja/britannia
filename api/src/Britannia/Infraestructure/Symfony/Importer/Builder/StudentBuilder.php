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

namespace Britannia\Infraestructure\Symfony\Importer\Builder;


use Britannia\Domain\Entity\Student\Adult;
use Britannia\Domain\Entity\Student\Child;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\ContactMode;
use Britannia\Domain\VO\Job;
use Britannia\Domain\VO\OtherAcademy;
use Britannia\Domain\VO\PartOfDay;
use Britannia\Infraestructure\Symfony\Importer\Builder\Traits\StudentMaker;
use Britannia\Infraestructure\Symfony\Importer\Maker\FullNameMaker;
use Britannia\Infraestructure\Symfony\Importer\Resume;

class StudentBuilder extends BuilderAbstract
{
    use StudentMaker;

    private const TYPE = 'Alumno';

    private $id;

    private $className;

    private $fullName;

    private $dni;
    private $payment;
    private $birthDate;
    private $emails = [];
    private $address;

    private $phoneNumbers = [];
    private $preferredPartOfDay;

    private $preferredContactMode;

    private $otherAcademy;

    private $firstContact;
    private $termsOfUseStudent;
    private $termsOfUseAcademy;
    private $termsOfUseImage;

    private $job;

    private $school;

    private $schoolCourse;
    private $firstTutor;
    private $secondTutor;

    private $firstComment;
    private $secondComment;

    private $firstTutorDescription;
    private $secondTutorDescription;


    public function initResume(array $input): Resume
    {
        $title = sprintf('%s %s', ...[
            $input['nombre'],
            $input['apellidos'],
        ]);

        return Resume::make((int)$input['id'], self::TYPE, $title);
    }

    public function withId($id): self
    {
        $this->id = $id;
        return $this;
    }

    public function withType(string $type): self
    {
        if ($type === 'Adulto') {
            $this->className = Adult::class;
            return $this;
        }

        $this->className = Child::class;
        return $this;
    }

    public function isAdult(): bool
    {
        return $this->className === Adult::class;
    }

    public function withDNI(string $dni): self
    {
        if (!$this->isAdult()) {
            return $this;
        }

        $this->dni = $this->toDni($dni);
        return $this;
    }


    public function withFullName(array $input): self
    {
        $this->fullName = $this->toFullName($input);
        return $this;
    }

    public function withBirthDate(string $input): self
    {
        $this->birthDate = $this->toDate($input);
        return $this;
    }


    public function withEmail(string $input, string $phone): self
    {
        $emails = [];
        $emails[] = $this->toEmail($input);

        if (false !== strpos($phone, '@')) {
            $emails[] = $this->toEmail($phone);
        }

        $this->emails = array_filter($emails);
        return $this;
    }

    public function withAddress(string $address, string $postalCode): self
    {
        $this->address = $this->toPostalAddress($address, $postalCode);
        return $this;
    }

    public function withPhoneNumbers(string ...$phones): self
    {
        $this->phoneNumbers = $this->toPhoneNumbers(...$phones);

        return $this;
    }

    public function withPreferredPartOfDay(string $partOfTheDay): self
    {
        $this->preferredPartOfDay = null;

        switch ($partOfTheDay) {
            case 'Tarde':
                $this->preferredPartOfDay = PartOfDay::AFTERNOON();
                break;
            case 'Mañana':
                $this->preferredPartOfDay = PartOfDay::MORNING();
                break;
        }

        return $this;

    }

    public function withPreferredContactMode(string $contactMode): self
    {

        $this->preferredContactMode = null;

        switch ($contactMode) {
            case 'Enviar e-mail':
                $this->preferredContactMode = ContactMode::EMAIL();
                break;
            case 'Realizar llamadas':
                $this->preferredContactMode = ContactMode::TELEPHONE();
                break;
            case 'Enviar Whatsapp':
                $this->preferredContactMode = ContactMode::WHATSAPP();
                break;
            case 'Correo ordinario':
                $this->preferredContactMode = ContactMode::POSTMAIL();
                break;
        }

        return $this;
    }

    public function withOtherAcademy(string $name, string $time): self
    {

        if (empty($name)) {
            return $this;
        }

        $academy = $this->toAcademy($name);

        $numOfYears = $this->toNumOfYears($time);

        $this->otherAcademy = OtherAcademy::make($academy, $numOfYears);
        return $this;
    }

    public function withPayment(array $input): self
    {
        $this->payment = $this->toPayment($input);
        return $this;
    }

    public function withFirstContact(string $contact): self
    {

        $this->firstContact = $contact;
        return $this;

    }

    public function withComments(string $firstComment, string $secondComment): self
    {
        $this->firstComment = $firstComment;
        $this->secondComment = $secondComment;
        return $this;
    }

    public function withTerms(string $academia, string $alumno, string $imagen): self
    {
        $this->termsOfUseAcademy = (bool)$academia;
        $this->termsOfUseStudent = (bool)$alumno;
        $this->termsOfUseImage = (bool)$imagen;
        return $this;
    }

    public function withJob(string $profesion, string $situacion): self
    {
        $status = $this->toJobStatus($situacion);

        if (!is_null($status)) {
            $this->job = Job::make($profesion, $status);
        }

        return $this;
    }

    public function withSchool(string $name, string $course): self
    {
        $this->school = $this->toSchool($name);
        $this->schoolCourse = $course;

        return $this;
    }

    public function withFirstTutor(array $data): self
    {
        $this->firstTutorDescription = $data['texto'];
        $this->firstTutor = $this->toTutor($data);
        return $this;
    }

    public function withSecondTutor(array $data): self
    {
        $this->secondTutorDescription = $data['texto'];
        $this->secondTutor = $this->toTutor($data);
        return $this;
    }


    public function build(): ?object
    {

        /** @var Student $child */
        $child = new $this->className();

        $child->setOldId(1 * $this->id);

        $child->setFullName($this->fullName);
        $child->setBirthDate($this->birthDate);
        $child->setEmails($this->emails);
        $child->setAddress($this->address);

        $child->setPhoneNumbers($this->phoneNumbers);
        $child->setPreferredPartOfDay($this->preferredPartOfDay);
        $child->setPreferredContactMode($this->preferredContactMode);

        $child->setOtherAcademy($this->otherAcademy);
        $child->setFirstContact($this->firstContact);

        $child->setFirstComment($this->firstComment);
        $child->setSecondComment($this->secondComment);

        $child->setTermsOfUseStudent($this->termsOfUseStudent);
        $child->setTermsOfUseAcademy($this->termsOfUseAcademy);
        $child->setTermsOfUseImageRigths($this->termsOfUseImage);

        $child->setPayment($this->payment);

        if ($child instanceof Adult) {

            $child->setDni($this->dni);
            $child->setJob($this->job);
        }

        if ($child instanceof Child) {
            $child->setSchool($this->school);
            $child->setSchoolCourse($this->schoolCourse);

            $child->setFirstTutorDescription($this->firstTutorDescription);
            $child->setFirstTutor($this->firstTutor);

            $child->setSecondTutorDescription($this->secondTutorDescription);
            $child->setSecondTutor($this->secondTutor);

        }

        return $child;
    }
}
