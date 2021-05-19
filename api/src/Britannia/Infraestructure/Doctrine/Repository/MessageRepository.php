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


use Britannia\Domain\Entity\Message\Message;
use Britannia\Domain\Repository\MessageRepositoryInterface;
use Carbon\CarbonImmutable;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Message|null find($id, $lockMode = null, $lockVersion = null)
 * @method Message|null findOneBy(array $criteria, array $orderBy = null)
 * @method Message[]    findAll()
 * @method Message[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class MessageRepository extends ServiceEntityRepository implements MessageRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * @param CarbonImmutable $date
     * @param int $daysLimit
     * @return Message[]
     */
    public function pendingShipping(CarbonImmutable $date, int $daysLimit = 7): array
    {

        $query = $this->createQueryBuilder('A')
            ->where('A.completed < A.total')
            ->andWhere('A.schedule >= :start ')
            ->andWhere('A.schedule <= :end')
            ->setParameters([
                'start' => $date->subDays($daysLimit),
                'end' => $date,
            ])
            ->getQuery();

        return $query->execute();
    }
}
