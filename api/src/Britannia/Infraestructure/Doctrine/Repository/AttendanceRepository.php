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


use Britannia\Domain\Entity\Course\Attendance;
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


}
