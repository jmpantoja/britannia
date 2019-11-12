<?php

namespace Britannia\Infraestructure\Doctrine\Repository;

use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\Lesson;
use Britannia\Domain\Repository\CourseRepositoryInterface;
use Britannia\Domain\Repository\LessonRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\Criteria;
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

    public function getLastByCourse(Course $course, \DateTime $day, int $limit = 5): array
    {

        $day->setDate(2019, 9, 15);
        $day->setTime(0, 0);

        $inPastLessons = $this->getInPastLessons($course, $day, $limit);

        $inFutureLessons = $this->getInFutureLessons($course, $day, $limit - count($inPastLessons));

        $lessons = array_merge($inFutureLessons, $inPastLessons);

        usort($lessons, function (Lesson $lessonA, Lesson $lessonB) {
            return $lessonA->getDay()->getTimestamp() < $lessonB->getDay()->getTimestamp();
        });

        return $lessons;

    }

    /**
     * @param Course $course
     * @param \DateTime $day
     * @param int $limit
     * @return array
     */
    protected function getInPastLessons(Course $course, \DateTime $day, int $limit): array
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
     * @param \DateTime $day
     * @param int $limit
     * @return array
     */
    protected function getInFutureLessons(Course $course, \DateTime $day, int $limit): array
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
}
