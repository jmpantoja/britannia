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
use League\Tactician\CommandBus;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager as ORMModelManager;
use Symfony\Bridge\Doctrine\RegistryInterface;

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

    public function __construct(RegistryInterface $registry, DataPersisterInterface $dataPersister)
    {
        $this->dataPersister = $dataPersister;
        parent::__construct($registry);

    }

    public function create($object)
    {
        $this->dataPersister->persist($object);
    }

    public function update($object)
    {
        $this->dataPersister->persist($object);
    }

    public function delete($object)
    {
        $this->dataPersister->remove($object);
    }
}
