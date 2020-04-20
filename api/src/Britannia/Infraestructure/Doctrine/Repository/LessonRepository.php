<?php

namespace Britannia\Infraestructure\Doctrine\Repository;

use Britannia\Domain\Entity\Assessment\Term;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourseList;
use Britannia\Domain\Repository\LessonRepositoryInterface;
use Britannia\Domain\VO\Course\TimeRange\TimeRange;
use Britannia\Domain\VO\Course\TimeRange\TimeRangeList;
use Carbon\CarbonImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Lesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lesson[]    findAll()
 * @method Lesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LessonRepository extends ServiceEntityRepository implements LessonRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Lesson::class);
    }

    public function getLastLessonsByCourse(Course $course, CarbonImmutable $day, int $limit = 5): array
    {
        $day->setTime(0, 0);

        $atPastLessons = $this->atPastLessons($course, $day, $limit);

        $atFutureLessons = $this->atFutureLessons($course, $day, $limit - count($atPastLessons));

        $lessons = array_merge($atFutureLessons, $atPastLessons);

        usort($lessons, function (Lesson $lessonA, Lesson $lessonB) {
            return $lessonA->day()->getTimestamp() < $lessonB->day()->getTimestamp();
        });

        return $lessons;

    }

    /**
     * @param Course $course
     * @param CarbonImmutable $day
     * @param int $limit
     * @return array
     */
    protected function atPastLessons(Course $course, CarbonImmutable $day, int $limit): array
    {
        $query = $this->createQueryBuilder('A')
            ->where('A.course = :course')
            ->andWhere('A.day <= :day')
            ->setMaxResults($limit)
            ->orderBy('A.day', 'DESC')
            ->getQuery();

        return $query->execute([
            'course' => $course,
            'day' => $day
        ]);
    }

    /**
     * @param Course $course
     * @param CarbonImmutable $day
     * @param int $limit
     * @return array
     */
    protected function atFutureLessons(Course $course, CarbonImmutable $day, int $limit): array
    {
        $query = $this->createQueryBuilder('A')
            ->where('A.course = :course')
            ->andWhere('A.day > :day')
            ->setMaxResults($limit)
            ->orderBy('A.day', 'ASC')
            ->getQuery();

        return $query->execute([
            'course' => $course,
            'day' => $day
        ]);
    }

    /**
     * @param CarbonImmutable $day
     * @return Lesson[]
     */
    public function findByDay(CarbonImmutable $day): array
    {
        return $this->findBy([
            'day' => $day
        ]);
    }

    public function findByCourseAndDay(Course $course, CarbonImmutable $date): ?Lesson
    {
        return $this->findOneBy([
            'day' => $date,
            'course' => $course
        ]);
    }

    public function countByTerm(Term $term): int
    {
        $periods = $this->calculePeriodsByTerm($term);
        $course = $term->course();

        return $periods->reduce(function (int $carry, TimeRange $timeRange) use ($course) {
            return $carry + $this->countByCourseAndTimeRange($course, $timeRange);
        }, 0);

    }

    public function countByCourseAndStudent(Course $course, Student $student): int
    {
        $query = $this->createQueryBuilder('A')
            ->select('count(A.id)')
            ->where('A.course = :course')
            ->andWhere('A.day >= :start')
            ->andWhere('A.day <= :end')
            ->getQuery();

        $query->setParameters([
            'course' => $course,
            'start' => $course->start(),
            'end' => $course->end(),
        ]);

        return $query->getSingleScalarResult();
    }

    private function countByCourseAndTimeRange(Course $course, TimeRange $timeRange)
    {
        $query = $this->createQueryBuilder('A')
            ->select('count(A.id)')
            ->where('A.course = :course')
            ->andWhere('A.day >= :start')
            ->andWhere('A.day <= :end')
            ->getQuery();

        $query->setParameters([
            'course' => $course,
            'start' => $timeRange->start(),
            'end' => $timeRange->end(),
        ]);

        return $query->getSingleScalarResult();
    }

    private function calculePeriodsByTerm(Term $term): TimeRangeList
    {
        return StudentCourseList::fromTerm($term)
            ->timeRangeList()
            ->limitToRange($term->timeRange());
    }

    private function calculePeriodsByCourseAndStudent(Course $course, Student $student): TimeRangeList
    {

        return StudentCourseList::fromCourseAndStudent($course, $student)
            ->timeRangeList()
            ->limitToRange($course->timeRange());
    }


}
