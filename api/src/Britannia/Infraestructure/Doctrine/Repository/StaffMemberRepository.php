<?php

namespace Britannia\Infraestructure\Doctrine\Repository;

use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Repository\StaffMemberRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 *
 * @method StaffMember|null findOneBy(array $criteria, array $orderBy = null)
 * @method StaffMember[]    findAll()
 * @method StaffMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StaffMemberRepository extends ServiceEntityRepository implements StaffMemberRepositoryInterface
{
    /**
     * @var EncoderFactoryInterface
     */
    private $encoderFactory;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StaffMember::class);

    }


    public function save(StaffMember $member)
    {
        $this->getEntityManager()->persist($member);

        $this->getEntityManager()->flush($member);
    }


    public function remove(StaffMember $member)
    {
        $this->getEntityManager()->remove($member);
    }

    /**
     * Loads the user for the given username.
     *
     * This method must return null if the user is not found.
     *
     * @param string $username The username
     *
     * @return UserInterface|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.userName = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
