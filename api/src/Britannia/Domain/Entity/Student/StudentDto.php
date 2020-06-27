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

namespace Britannia\Domain\Entity\Student;


use Britannia\Domain\Entity\Attachment\AttachmentList;
use Britannia\Domain\Entity\Course\CourseList;
use Britannia\Domain\Entity\School\School;
use Britannia\Domain\VO\Payment\Payment;
use Britannia\Domain\VO\Student\Alert\Alert;
use Britannia\Domain\VO\Student\ContactMode\ContactMode;
use Britannia\Domain\VO\Student\Job\Job;
use Britannia\Domain\VO\Student\OtherAcademy\OtherAcademy;
use Britannia\Domain\VO\Student\PartOfDay\PartOfDay;
use Carbon\CarbonImmutable;
use DateTimeInterface;
use PlanB\DDD\Domain\Model\Dto;
use PlanB\DDD\Domain\VO\DNI;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\PostalAddress;

final class StudentDto extends Dto
{
    public int $oldId;

    public ?FullName $fullName;

    public ?DateTimeInterface $birthDate;

    public array  $emails = [];

    public ?PostalAddress $address;

    public array $phoneNumbers = [];

    public StudentList  $relatives;

    public AttachmentList $attachments;

    public ?Photo $photo = null;

    public bool $freeEnrollment = false;

    public ?Payment $payment = null;

    public ?PartOfDay $preferredPartOfDay;

    public ?ContactMode $preferredContactMode;

    public ?OtherAcademy $otherAcademy;

    public CourseList $studentHasCourses;

    public ?string $firstContact;

    public ?string $comment = null;

    public ?Alert $alert = null;

    public bool $termsOfUseAcademy = false;

    public bool $termsOfUseStudent = false;

    public bool $termsOfUseImageRigths = false;

    public ?CarbonImmutable $createdAt;

    //Child
    public ?School $school;

    public ?string $schoolCourse;

    public ?string $firstTutorDescription ;

    public ?Tutor $firstTutor;

    public ?string  $secondTutorDescription;

    public ?Tutor $secondTutor;

    //Adult
    public ?Job $job;

    public ?DNI $dni;


    protected function defaults(): array
    {
        return [
            'relatives' => StudentList::collect(),
            'studentHasCourses' => CourseList::collect(),
            'attachments' => AttachmentList::collect()
        ];
    }


}
