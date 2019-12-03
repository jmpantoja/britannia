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

namespace Britannia\Infraestructure\Symfony\DataFixtures;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Driver\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;

abstract class BaseFixture extends Fixture
{

    /**
     * @var DataPersister
     */
    private $dataPersister;

    /**
     * @var Generator
     */
    protected $faker;

    private $olderConnection;

    public function __construct(DataPersisterInterface $dataPersister)
    {
        $this->dataPersister = $dataPersister;
    }


    /**
     * Load data fixtures with the passed EntityManager
     *
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        $this->faker = Factory::create('es_ES');

        $this->import($manager);
        $this->loadData($this->dataPersister);
    }

    public function import(ObjectManager $manager): void
    {
        $paths = $this->getBackupFiles();

        foreach ($paths as $path) {

            $sql = file_get_contents($path);
            $response = $manager->getConnection()->exec($sql);

        }
        $manager->flush();
    }

    abstract public function loadData(DataPersisterInterface $dataPersister): void;


    abstract public function getBackupFiles(): array;

    protected function createMany(string $className, int $count, callable $callback)
    {
        for ($i = 0; $i < $count; $i++) {
            $entity = new $className();

            $reference = $callback($entity, $i) ?? $i;

            $this->dataPersister->persist($entity);

            $this->addReference($className . '_' . $reference, $entity);
        }
    }

}
