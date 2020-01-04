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


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Staff\StaffMemberDto;
use Britannia\Infraestructure\Symfony\Importer\Maker\FullNameMaker;
use Britannia\Infraestructure\Symfony\Importer\Resume;
use PlanB\DDD\Domain\VO\DNI;
use PlanB\DDD\Domain\VO\Email;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\PhoneNumber;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class StaffBuilder extends BuilderAbstract
{
    private const TYPE = 'Staff';
    private $userName;
    private $password;
    private $fullName;
    private $emails;
    private $phoneNumbers;
    private $dni;
    private $teacherId;
    private $roles;
    private $courses;
    /**
     * @var int
     */
    private int $id;
    /**
     * @var EncoderFactoryInterface
     */
    private EncoderFactoryInterface $encoder;


    public function initResume(array $input): Resume
    {
        $title = sprintf('%s', ...[
            $input['user']
        ]);

        return Resume::make((int)$input['id'], self::TYPE, $title);
    }

    public function withId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function withTeacherId(?int $teacherId): self
    {
        $this->teacherId = $teacherId;
        return $this;
    }


    public function withRoles(bool $teacher, bool $admin): self
    {
        $this->roles = [];
        if ($teacher) {
            $this->roles[] = 'ROLE_TEACHER';
        }

        if ($admin) {
            $this->roles[] = 'ROLE_MANAGER';
        }

        return $this;
    }

    public function withCourses(string $courses): self
    {
        $courses = explode(',', $courses);
        $courses = array_filter($courses);


        $this->courses = array_map(function ($course) {
            return $this->findOneOrNull(Course::class, [
                'oldId' => $course * 1
            ]);
        }, $courses);

        $this->courses = array_filter($this->courses);

        return $this;
    }

    public function withUserName(string $userName): self
    {
        $this->userName = $userName;
        return $this;
    }

    public function withPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function withFullName(string $name): self
    {

        $name = preg_replace('/[[:punct:]]|\d/', '', $name);

        $this->fullName = FullName::make($name, 'britannia');
        return $this;
    }

    public function withEmail(string $email, string $phone, string $dni): self
    {
        $emails = array_merge($this->toEmails($email), $this->toEmails($phone), $this->toEmails($dni));
        $emails = array_unique($emails);

        $temp = [];
        foreach ($emails as $address) {
            $temp[] = Email::make($address);
        }

        $this->emails = $temp;
        return $this;
    }

    private function toEmails(string $text): array
    {
        $text = str_replace(['á', 'é', 'í', 'ó', 'ú'], ['a', 'e', 'i', 'o', 'u'], $text);

        $matches = [];
        if (!preg_match_all('/([\w|\.]+@[\w|\.]+)/', $text, $matches)) {
            return [];
        }

        return $matches[1];
    }

    public function withDni(string $dni): self
    {
        $matches = [];
        if (!preg_match('/\d{8}[aA-zZ]{1}/', $dni, $matches)) {
            return $this;
        }

        if (!Dni::isValid($matches[0])) {
            return $this;
        }

        $this->dni = DNI::make($matches[0]);
        return $this;
    }

    public function withPhone(string $dni): self
    {
        $matches = [];
        if (!preg_match('/\d{9}/', $dni, $matches)) {
            return $this;
        }

        if (!PhoneNumber::isValid(['phoneNumber' => $matches[0]])) {
            return $this;
        }

        $this->phoneNumbers = [
            PhoneNumber::make($matches[0])
        ];

        return $this;
    }


    public function withEncoder(EncoderFactoryInterface $encoderFactory): self
    {
        $this->encoder = $encoderFactory;
        return $this;
    }

    public function build(): ?object
    {
        $dto = StaffMemberDto::fromArray([
            'oldId' => $this->id,
            'userName' => $this->userName,
            'password' => 1234,
            'encoder' => $this->encoder,
            'fullName' => $this->fullName,
            'emails' => $this->emails,
            'phoneNumbers' => (array)$this->phoneNumbers,
            'dni' => $this->dni,
            'roles' => $this->roles,
        ]);

        return StaffMember::make($dto);
    }


}
