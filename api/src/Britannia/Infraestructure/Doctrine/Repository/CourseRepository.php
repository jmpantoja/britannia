<?php

namespace Britannia\Infraestructure\Doctrine\Repository;

use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Repository\CourseRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Course|null find($id, $lockMode = null, $lockVersion = null)
 * @method Course|null findOneBy(array $criteria, array $orderBy = null)
 * @method Course[]    findAll()
 * @method Course[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CourseRepository extends ServiceEntityRepository implements CourseRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Course::class);
    }

    /**
     * @param \DateTime $date
     * @return Course[]
     */
    public function findUpdateStatusPending(): array
    {

        $today = \DateTime::createFromFormat('U', (string)$_SERVER['REQUEST_TIME']);

        $query = $this->createQueryBuilder('A')
            ->where('A.startDate <= :today')
            ->andWhere('A.endDate >= :today')
            ->setParameters(['today' => $today])
            ->getQuery();

        return $query->getResult();

    }
}
