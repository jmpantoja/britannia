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

namespace Britannia\Infraestructure\Doctrine\Repository;


use Britannia\Domain\Entity\Assessment\Term;
use Britannia\Domain\Entity\Attendance\Attendance;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Repository\AttendanceRepositoryInterface;
use Carbon\CarbonImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 *
 * @method Attendance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Attendance[]    findAll()
 * @method Attendance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AttendanceRepository extends ServiceEntityRepository implements AttendanceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Attendance::class);
    }

    /**
     * @param Student $student
     * @param Course $course
     * @param CarbonImmutable $date
     * @param int|null $limit
     * @return Attendance[]
     */
    public function findByStudent(Student $student, Course $course, CarbonImmutable $date, int $limit = null): array
    {

        $query = $this->createQueryBuilder('A')
            ->where('A.student = :student')
            ->andWhere('A.course = :course')
            ->andWhere('A.day <= :today')
            ->orderBy('A.number', 'DESC')
            ->setMaxResults($limit)
            ->getQuery();

        return $query->execute([
            'student' => $student,
            'course' => $course,
            'today' => $date
        ]);

    }

    public function countByTerm(Term $term): int
    {
        $query = $this->createQueryBuilder('A')
            ->select('count(A.id)')
            ->where('A.student = :student')
            ->andWhere('A.course = :course')
            ->andWhere('A.day >= :start')
            ->andWhere('A.day <= :end')
            ->getQuery();

        $query->setParameters([
            'course' => $term->course(),
            'student' => $term->student(),
            'start' => $term->start(),
            'end' => $term->end()
        ]);

        return (int)$query->getSingleScalarResult();
    }

    public function countByCourse(Course $course, Student $student): int
    {
        $query = $this->createQueryBuilder('A')
            ->select('count(A.id)')
            ->where('A.student = :student')
            ->andWhere('A.course = :course')
            ->andWhere('A.day >= :start')
            ->andWhere('A.day <= :end')
            ->getQuery();

        $query->setParameters([
            'course' => $course,
            'student' => $student,
            'start' => $course->start(),
            'end' => $course->end()
        ]);

        return (int)$query->getSingleScalarResult();
    }
}
