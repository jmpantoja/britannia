<?php

namespace Britannia\Infraestructure\Doctrine\Repository;

use Britannia\Domain\Entity\Attendance\Attendance;
use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Domain\Entity\Record\Record;
use Britannia\Domain\Entity\Record\TypeOfRecord;
use Britannia\Domain\Repository\RecordRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Lesson|null find($id, $lockMode = null, $lockVersion = null)
 * @method Lesson|null findOneBy(array $criteria, array $orderBy = null)
 * @method Lesson[]    findAll()
 * @method Lesson[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RecordRepository extends ServiceEntityRepository implements RecordRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Record::class);
    }

    public function deleteAttendance(Attendance $attendance)
    {
        $query = $this->createQueryBuilder('A')
            ->delete()
            ->where('A.student = :student')
            ->andWhere('A.course = :course')
            ->andWhere('A.day = :day')
            ->andWhere('A.type = :type')
            ->getQuery();

        $query->setParameters([
            'student' => $attendance->student(),
            'course' => $attendance->course(),
            'day' => $attendance->day(),
            'type' => TypeOfRecord::ATTENDANCE()
        ]);

        $query->execute();
    }
}
//
//$records = $this->entityManager->getRepository(Record::class)
//    ->findBy([
//        'student' => $attendance->student(),
//        'course' => $attendance->course(),
//        'day' => $attendance->day(),
//        'type' => TypeOfRecord::ATTENDANCE()
//    ]);
//
//foreach ($records as $record) {
//    $this->persister->remove($record);
//}
