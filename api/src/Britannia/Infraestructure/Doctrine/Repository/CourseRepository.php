<?php

namespace Britannia\Infraestructure\Doctrine\Repository;

use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Repository\CourseRepositoryInterface;
use Britannia\Domain\VO\Course\CourseStatus;
use Carbon\CarbonImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
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

    public function findCoursesForUpdateStatus(): array
    {

        $today = CarbonImmutable::now();

        $query = $this->createQueryBuilder('o')
            ->where('o.timeTable.end <= :today and o.status != :finalized')
            ->orWhere('o.timeTable.start <= :today and o.timeTable.end >= :today and o.status != :active')
            ->orWhere('o.timeTable.start >= :today and o.status != :pending')
            ->getQuery();

        return $query->execute([
            'today' => $today,
            'finalized' => CourseStatus::FINALIZED(),
            'active' => CourseStatus::ACTIVE(),
            'pending' => CourseStatus::PENDING(),
        ]);
    }

}
