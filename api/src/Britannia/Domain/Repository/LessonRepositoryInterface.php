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
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Entity\Student\Student;
use Carbon\CarbonImmutable;

/**
 * @method Lesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lesson[]    findAll()
 * @method Lesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface LessonRepositoryInterface
{

    /**
     * @param Course $course
     * @param CarbonImmutable $day
     * @param int $limit
     * @return Lesson[]
     */
    public function getLastLessonsByCourse(Course $course, CarbonImmutable $day, int $limit = 5): array;

    /**
     * @param CarbonImmutable $day
     * @return Lesson[]
     */
    public function findByDay(CarbonImmutable $day): array;

    public function countByTerm(Term $term): int;

    public function countByCourseAndStudent(Course $course, Student $student): int;

    public function findByCourseAndDay(Course $course, CarbonImmutable $date): ?Lesson;

}
