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

namespace PlanB\DDDBundle\Sonata;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use League\Tactician\CommandBus;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager as ORMModelManager;
use Symfony\Component\Cache\Adapter\AdapterInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

class ModelManager extends ORMModelManager
{
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var CommandFactoryInterface
     */
    private $commandFactory;
    /**
     * @var DataPersisterInterface
     */
    private $dataPersister;

    /** @var TagAwareCacheInterface */
    private $adapter;

    public function __construct(ManagerRegistry $registry, DataPersisterInterface $dataPersister, TagAwareCacheInterface $cache)
    {
        $this->dataPersister = $dataPersister;

        parent::__construct($registry);
        $this->adapter = $cache;
    }

    public function create($object)
    {
        $this->dataPersister->persist($object);
        $this->cleanCache($object);
    }

    public function update($object)
    {
        $this->dataPersister->persist($object);
        $this->cleanCache($object);
    }

    public function delete($object)
    {
        $this->dataPersister->remove($object);
        $this->cleanCache($object);
    }

    /**
     * @param object $object
     */
    private function cleanCache(object $object): void
    {
        $className = get_class($object);
        do {
            $key = normalize_key($className);
            $this->adapter->delete($key);
            $this->adapter->invalidateTags([$key]);
            $className = get_parent_class($className);
        } while ($className);
    }
}
