<?php

namespace Britannia\Infraestructure\Doctrine\Repository;

use Britannia\Domain\Entity\Setting\Setting;
use Britannia\Domain\Entity\Setting\SettingId;
use Britannia\Domain\Repository\SettingRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Setting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Setting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Setting[]    findAll()
 * @method Setting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SettingRepository extends ServiceEntityRepository implements SettingRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Setting::class);
    }

    public function getMain(): Setting
    {
        $setting = $this->createQueryBuilder('A')
            ->where('A.id = :id')
            ->setParameter('id', SettingId::ID)
            ->getQuery()
            ->setCacheable(true)
            //->enableResultCache(WEEK_IN_SECONDS, 'hola')
            ->getOneOrNullResult();

        return $setting;
    }
}
