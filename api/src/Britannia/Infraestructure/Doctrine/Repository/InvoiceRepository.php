<?php

namespace Britannia\Infraestructure\Doctrine\Repository;

use Britannia\Domain\Entity\Invoice\Invoice;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Repository\InvoiceRepositoryInterface;
use Carbon\CarbonImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Invoice|null find($id, $lockMode = null, $lockVersion = null)
 * @method Invoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoice[]    findAll()
 * @method Invoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InvoiceRepository extends ServiceEntityRepository implements InvoiceRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Invoice::class);
    }

    public function existsByStudentAndMonth(Student $student, CarbonImmutable $date): bool
    {
        $invoice = $this->findOneBy([
            'student' => $student,
            'month' => $date->format('Ym')
        ]);

        return $invoice instanceof Invoice;
    }

    public function findUnPaidByStudentAndMonth(Student $student, CarbonImmutable $date): ?Invoice
    {
        return $this->findOneBy([
            'student' => $student,
            'month' => $date->format('Ym'),
            'paid' => false
        ]);
    }

}
