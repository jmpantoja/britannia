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


use Britannia\Domain\Entity\Calendar\Calendar;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Repository\CalendarRepositoryInterface;
use Britannia\Domain\VO\DayOfWeek;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 *
 * @method Calendar|null findOneBy(array $criteria, array $orderBy = null)
 * @method Calendar[]    findAll()
 * @method Calendar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CalendarRepository extends ServiceEntityRepository implements CalendarRepositoryInterface
{
    private $cache = [];

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Calendar::class);
    }

    /**
     * @return int[]
     */
    public function getAvailableYears(): array
    {
        $query = $this->createQueryBuilder('A')
            ->distinct(true)
            ->select('A.year')
            ->groupBy('A.year')
            ->getQuery();

        $result = $query->execute();

        return array_map(function ($value) {
            return $value['year'];
        }, $result);
    }

    public function hasDay(\DateTimeImmutable $dateTime): bool
    {
        $dateTime = $dateTime->setTime(0, 0);
        $days = $this->getYear($dateTime);
        $key = $dateTime->format('U');

        return isset($days[$key]);
    }

    private function getYear(\DateTimeImmutable $dateTime): array
    {
        $year = (int)$dateTime->format('Y');

        if (isset($this->cache[$year])) {
            return $this->cache[$year];
        }

        $query = $this->createQueryBuilder('A')
            ->where('A.year = :year')
            ->setParameter('year', $year)
            ->getQuery();

        $result = $query->execute();
        $values = [];

        foreach ($result as $day) {
            $values[$day->getId()] = $day;
        };

        $this->cache[$year] = $values;

        return $this->cache[$year];
    }

    /**
     * @param Course $course
     * @return Calendar[]
     */
    public function getLessonDaysFromCourse(Course $course): array
    {

        $daysOfWeek = $course->getDaysOfWeek();
        $daysOfWeek = array_values($daysOfWeek);

        $query = $this->createQueryBuilder('A')
            ->where('A.weekday IN (:days)')
            ->andWhere('A.workDay = true')
            ->andWhere('A.date >= :start AND A.date <= :end')
            ->orderBy('A.id', 'ASC')
            ->setParameter('days', $daysOfWeek)
            ->setParameter('start', $course->getStartDate())
            ->setParameter('end', $course->getEndDate())
            ->getQuery();

        return $query->execute();
    }


}
