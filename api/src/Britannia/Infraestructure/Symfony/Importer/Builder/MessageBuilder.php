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


use Britannia\Domain\Entity\Message\Message\Sms;
use Britannia\Domain\Entity\Message\Message\SmsDto;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentList;
use Britannia\Infraestructure\Symfony\Importer\Etl\Message\DeliveryFactoryFake;
use Britannia\Infraestructure\Symfony\Importer\Resume;
use Carbon\CarbonImmutable;

final class MessageBuilder extends BuilderAbstract
{
    private const TYPE = 'Message';

    private $subject;
    private $message;
    private $students;
    private $createdBy;
    private $schedule;
    private $createdAt;
    private bool $complete = false;

    private $factory = null;


    private function factory(): DeliveryFactoryFake
    {

        if (is_null($this->factory)) {
            $this->factory = DeliveryFactoryFake::make($this->schedule);
        }

        return $this->factory;
    }


    public function initResume(array $input): Resume
    {
        $title = substr((string)$input['mensaje'], 0, 10);
        return Resume::make((int)$input['id'], self::TYPE, $title);
    }

    public function withSubject(int $index): self
    {
        $this->subject = sprintf('SMS #%03d', $index);

        return $this;
    }

    public function withMessage(string $message): self
    {
        $this->message = trim($message);
        return $this;
    }

    public function withStudents(?string $keys): self
    {
        if (is_null($keys)) {
            $this->students = StudentList::collect([]);
            return $this;
        }

        $students = collect(explode(',', $keys))
            ->map(fn(string $key) => (int)$key)
            ->map(fn(int $oldId) => $this->findOneOrNull(Student::class, ['oldId' => $oldId]))
            ->filter();

        $this->students = StudentList::collect($students);
        return $this;
    }

    public function withAuthor(StaffMember $author): self
    {
        $this->createdBy = $author;
        return $this;
    }

    public function withCreatedAt(string $date): self
    {
        $this->createdAt = CarbonImmutable::make($date);
        return $this;
    }


    public function withDate(string $date, string $time): self
    {
        $this->schedule = CarbonImmutable::make(sprintf('%s %s', $date, $time));
        return $this;
    }

    public function withPhones($phones): self
    {
        $this->factory()->setPhones($phones);
        return $this;
    }

    public function build(): ?object
    {
        $dto = SmsDto::fromArray([
            'subject' => $this->subject,
            'message' => $this->message,
            'students' => $this->students,
            'createdBy' => $this->createdBy,
            'createdAt' => $this->createdAt,
            'schedule' => $this->schedule
        ]);

        return Sms::make($dto)
            ->updateShipments(false)
            ->send($this->factory());
    }


}
