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


use Britannia\Domain\Entity\Issue\Issue;
use Britannia\Domain\Entity\Issue\IssueDto;
use Britannia\Domain\Entity\Staff\StaffList;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Infraestructure\Symfony\Importer\Builder\Traits\StudentMaker;
use Britannia\Infraestructure\Symfony\Importer\Maker\FullNameMaker;
use Britannia\Infraestructure\Symfony\Importer\Resume;
use Carbon\CarbonImmutable;
use Symfony\Component\Security\Core\Security;

class IssueBuilder extends BuilderAbstract
{
    use StudentMaker;

    private const TYPE = 'Issue';
    private const MAX_SUBJECT_LENGTH = 50;

    /**
     * @var Security
     */
    private Security $security;

    /**
     * @var object|null
     */
    private ?Student $student;
    /**
     * @var object|null
     */
    private ?object $author;
    /**
     * @var string
     */
    private string $message;
    /**
     * @var CarbonImmutable|null
     */
    private ?CarbonImmutable $createAt;


    public function setSecurity(Security $security): self
    {

        $this->security = $security;
        return $this;
    }


    public function initResume(array $input): Resume
    {
        $title = (string)$input['observacion'];

        return Resume::make((int)$input['id'], self::TYPE, $title);
    }


    public function build(): ?object
    {
        if (empty($this->message)) {
            return null;
        }

        $input = [
            'subject' => $this->truncate($this->message),
            'message' => $this->message,
            'author' => $this->author,
            'student' => $this->student,
            'createdAt' => $this->createAt,
            'issueHasRecipients' => StaffList::collect()
        ];

        $dto = IssueDto::fromArray($input);
        return Issue::make($dto);
    }

    private function truncate(string $text): string
    {
        $len = strlen($text);
        if ($len <= self::MAX_SUBJECT_LENGTH) {
            return $text;
        }


        $length = strrpos(substr($text, 0, self::MAX_SUBJECT_LENGTH), ' ');
        if ($length === false) {
            $length = self::MAX_SUBJECT_LENGTH;
        }
        return substr($text, 0, $length) . '...';

    }

    public function withStudent(int $studentId): self
    {
        $this->student = $this->findOneOrNull(Student::class, [
            'oldId' => $studentId
        ]);

        return $this;
    }

    public function withAuthor(int $staffId): self
    {
        $this->author = $this->findOneOrNull(StaffMember::class, [
                'oldId' => $staffId
            ]) ?? $this->getUser();


        return $this;
    }

    public function withMessage(string $message): self
    {
        $message = preg_replace('/[[:^print:]]/u', ' ', $message);

        $this->message = $message;
        return $this;
    }

    public function withCreatedAt(string $date): self
    {
        $this->createAt = CarbonImmutable::make($date);
        return $this;
    }

    /**
     * @return \Symfony\Component\Security\Core\User\UserInterface|null
     */
    private function getUser()
    {
        return $this->ensureEntityIsManaged($this->security->getUser());
    }


    protected function ensureEntityIsManaged(object $entity)
    {
        if ($this->entityManager->contains($entity)) {
            return $entity;
        }

        return $this->entityManager->find(get_class($entity), $entity->id());
    }

}
