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

namespace Britannia\Application\UseCase\Student;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Domain\Entity\Issue\Issue;
use Britannia\Domain\Entity\Issue\IssueDto;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Repository\IssueRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use PlanB\DDD\Application\UseCase\UseCaseInterface;
use PlanB\DDDBundle\ApiPlattform\DataPersister;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

final class StudentUpdatedUseCase implements UseCaseInterface
{
    /**
     * @var Security
     */
    private Security $security;
    /**
     * @var DataPersister
     */
    private DataPersisterInterface $dataPersister;
    /**
     * @var IssueRepositoryInterface
     */
    private IssueRepositoryInterface $issueRepository;
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;


    /**
     * StudentUpdatedUseCase constructor.
     */
    public function __construct(Security $security,
                                IssueRepositoryInterface $issueRepository,
                                DataPersisterInterface $dataPersister,
                                EntityManagerInterface $entityManager)
    {
        $this->security = $security;
        $this->issueRepository = $issueRepository;

        $this->dataPersister = $dataPersister;
        $this->entityManager = $entityManager;
    }

    public function handle(StudentUpdated $command)
    {
        $student = $command->student();
        $user = $this->security->getUser();
        $author = $this->ensureEntityIsManaged($user);

        $this->persistIssue($student, $author);
    }

    private function persistIssue(Student $student, ?StaffMember $author)
    {
        $main = $this->issueRepository->getMainIssue($student, $author);
        $dto = $this->makeDto($student, $author);

        if ($this->shouldNotBeUpdated($main, $dto)) {
            return;
        }

        $issue = $this->getIssue($dto, $main);
        $this->dataPersister->persist($issue);

    }

    /**
     * @param Student $student
     * @param UserInterface|null $author
     * @return Issue|null
     */
    private function getIssue(IssueDto $dto, ?Issue $issue)
    {
        if ($issue instanceof Issue) {
            return $issue->update($dto);
        }

        return Issue::make($dto);
    }

    private function shouldNotBeUpdated(?Issue $issue, IssueDto $dto): bool
    {
        $message = $this->stripMessage($dto->message);
        if (is_null($issue)) {
            return empty($message);
        }

        $current = $this->stripMessage($issue->message());

        return $current === $message;
    }

    private function stripMessage(?string $subject): ?string
    {
        if (is_null($subject)) {
            return null;
        }

        return trim(strip_tags($subject));
    }

    /**
     * @param Student $student
     * @param $author
     * @return IssueDto
     */
    private function makeDto(Student $student, StaffMember $author): IssueDto
    {
        $comment = $this->getComment($student);

        return IssueDto::fromArray([
            'main' => true,
            'subject' => 'Observaciones',
            'message' => $comment,
            'author' => $author,
            'student' => $student
        ]);
    }

    /**
     * @param Student $student
     * @return string
     */
    private function getComment(Student $student): string
    {
        $alert = $student->alert();

        if ($alert->isAlert()) {
            return sprintf('<h3><strong>%s</strong></h3>%s', ...[
                $alert->description(),
                $student->comment(),
            ]);
        }
        return $student->comment();
    }

    protected function ensureEntityIsManaged(StaffMember $entity)
    {
        if ($this->entityManager->contains($entity)) {
            return $entity;
        }

        $managed = $this->entityManager->find(get_class($entity), $entity->id());

        if (!is_null($managed)) {
            return $managed;
        }

        $this->persister->persist($entity);
        return $entity;
    }
}
