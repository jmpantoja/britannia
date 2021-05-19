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


use Britannia\Domain\Entity\Student\Tutor;
use Britannia\Domain\Repository\TutorRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Tutor|null find($id, $lockMode = null, $lockVersion = null)
 * @method Tutor|null findOneBy(array $criteria, array $orderBy = null)
 * @method Tutor[]    findAll()
 * @method Tutor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class TutorRepository extends ServiceEntityRepository implements TutorRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Tutor::class);
    }
}
