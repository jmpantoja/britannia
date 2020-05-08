<?php

namespace Britannia\Infraestructure\Doctrine\Repository;

use Britannia\Domain\Entity\Issue\Issue;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Repository\IssueRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @method Issue|null find($id, $lockMode = null, $lockVersion = null)
 * @method Issue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Issue[]    findAll()
 * @method Issue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IssueRepository extends ServiceEntityRepository implements IssueRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Issue::class);
    }

    public function numOfUnread(StaffMember $staffMember): int
    {
        $query = $this->createQueryBuilder('A')
            ->select('count(A)')
            ->innerJoin('A.issueHasRecipients', 'P')
            ->where('P.recipient = :user AND P.readAt is null')
            ->setParameter('user', $staffMember)
            ->getQuery();

        return $query->getSingleScalarResult();
    }

    public function getMainIssue(Student $student, ?UserInterface $author): ?Issue
    {
        return $this->findOneBy([
            'student' => $student,
            'main' => true
        ]);
    }
}
