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

namespace Britannia\Domain\Repository;

use Britannia\Domain\Entity\Assessment\Term;
use Britannia\Domain\Entity\Attendance\Attendance;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Carbon\CarbonImmutable;

/**
 *
 * @method Attendance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Attendance[]    findAll()
 * @method Attendance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface AttendanceRepositoryInterface
{
    /**
     * @param Student $student
     * @param Course $course
     * @param CarbonImmutable $date
     * @param int $limit
     * @return Attendance[]
     */
    public function findByStudent(Student $student, Course $course, CarbonImmutable $date, int $limit = null): array;

    public function countByTerm(Term $term): int;

    public function countByCourse(Course $course, Student $student): int;
}
