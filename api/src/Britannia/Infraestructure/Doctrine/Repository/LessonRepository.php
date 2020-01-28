<?php

namespace Britannia\Infraestructure\Doctrine\Repository;

use Britannia\Domain\Entity\Assessment\Term;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Repository\LessonRepositoryInterface;
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

    public function countByTerm(Term $term): int
    {
        $query = $this->createQueryBuilder('A')
            ->select('count(A.id)')
            ->where('A.course = :course')
            ->andWhere('A.day >= :start')
            ->andWhere('A.day <= :end')
            ->getQuery();

        $query->setParameters([
            'course' => $term->course(),
            'start' => $term->start(),
            'end' => $term->end(),
        ]);

        return $query->getSingleScalarResult();
    }
}
