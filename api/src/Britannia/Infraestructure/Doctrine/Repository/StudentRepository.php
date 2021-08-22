<?php

namespace Britannia\Infraestructure\Doctrine\Repository;

use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\Repository\StudentRepositoryInterface;
use Carbon\CarbonImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StudentRepository extends ServiceEntityRepository implements StudentRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Student::class);
    }

    public function findByIdList(array $list): array
    {
        $query = $this->createQueryBuilder('A')
            ->where('A.id in (:list)')
            ->setParameter('list', $list)
            ->getQuery();

        return $query->execute();
    }

    public function findByBirthDay(CarbonImmutable $day): array
    {

        $months = [];
        for ($step = 0; $step < 3; $step++) {
            $months[] = $day->get('month');
            $day = $day->subMonth();
        }

        $query = $this->createQueryBuilder('A')
            ->where('A.birthMonth IN (:months)')
            ->getQuery();

        return $query->execute([
            'months' => $months,
        ]);
    }

    public function findActives(): array
    {
        $query = $this->createQueryBuilder('A')
            ->where('A.active = :active')
            ->getQuery();

        return $query->execute([
            'active' => true,
        ]);
    }

    public function disableStudentsWithoutActiveCourses()
    {
        $active = $this->getEntityManager()
            ->createQueryBuilder()
            ->select('C.id')
            ->from(StudentCourse::class, 'A')
            ->innerJoin(Course::class, 'B', Join::WITH, 'A.course = B.id')
            ->innerJoin(Student::class, 'C', Join::WITH, 'A.student = C.id')
            ->where("A.leavedAt is null")
            ->andWhere("B.timeRange.status like 'ACTIVE'")
            ->getQuery();

        $query = $this->createQueryBuilder('A')
            ->update(Student::class, 'A')
            ->set('A.active', 0)
            ->where('A.id NOT in (:list)')
            ->setParameter('list', $active->execute())
            ->getQuery();

        return $query->execute();
    }

    public function findStudentsOfTheCorrectAge(Course $course): array
    {
        $builder = $this->createQueryBuilder('A')
            ->orderBy('A.fullName.lastName', 'ASC');

        if ($course->isAdult()) {
            $builder->where('A.age >= 17');
        }

        if ($course->isSchool() or $course->isSupport()) {
            $builder->where('A.age >= 6 AND A.age <= 20');
        }

        if ($course->isPreSchool()) {
            $builder->where('A.age <= 6');
        }

        $query = $builder->getQuery();
        return $query->execute();
    }
}
