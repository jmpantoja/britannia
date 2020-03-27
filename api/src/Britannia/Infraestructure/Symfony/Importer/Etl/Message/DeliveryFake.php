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

namespace Britannia\Infraestructure\Symfony\Importer\Etl\Message;


use Britannia\Domain\Entity\Student\Child;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Service\Message\DeliveryInterface;
use Carbon\CarbonImmutable;
use Tightenco\Collect\Support\Collection;

final class DeliveryFake implements DeliveryInterface
{

    /**
     * @var array
     */
    private $failures = [];
    /**
     * @var CarbonImmutable
     */
    private CarbonImmutable $date;

    public static function make(CarbonImmutable $date, array $phones): self
    {
        return new self($date, $phones);
    }

    private function __construct(CarbonImmutable $date, $phones)
    {
        $this->date = $date;
        $this->phones = $phones;

    }

    public function send(Student $student, string $message, string $subject): bool
    {
        $phone = $this->findPhoneByStudent($student);

        if (is_null($phone)) {
            return true;
        }

        return $phone['state'];
    }


    public function date(): ?CarbonImmutable
    {
        return $this->date;
    }

    public function recipient(Student $student): ?string
    {
        $phone = $this->findPhoneByStudent($student);

        if (is_null($phone)) {
            return null;
        }

        return $phone['phone'];
    }

    private function findPhoneByStudent(Student $student): ?array
    {
        $candidates = $this->mobilePhoneNumberByStudent($student);

        foreach ($candidates as $candidate) {
            foreach ($this->phones as $phone) {
                if (false !== strpos($phone['raw'], $candidate->getRaw())) {
                    return $phone;
                }
            }
        }

        return null;
    }


    public function mobilePhoneNumberByStudent(Student $student): Collection
    {
        $candidates = [];
        $candidates[] = $student->phoneNumbers();

        if ($student instanceof Child) {
            $candidates[] = $student->firstTutor()->phoneNumbers();
            $candidates[] = $student->secondTutor()->phoneNumbers();
        }

        $phoneNumbers = array_merge(...$candidates);

        return collect($phoneNumbers);
    }

}
