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

namespace Britannia\Infraestructure\Symfony\Importer\Etl;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Infraestructure\Symfony\Importer\Console;
use Britannia\Infraestructure\Symfony\Importer\DataCollector;
use Britannia\Infraestructure\Symfony\Importer\Report\Reporter;
use Britannia\Infraestructure\Symfony\Importer\Resume;
use Britannia\Infraestructure\Symfony\Importer\Traits\Loggable;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

abstract class AbstractEtl implements EtlInterface
{

    /**
     * @var QueryBuilder
     */
    private $builder;

    /**
     * @var Connection
     */
    private $default;

    /**
     * @var DataPersisterInterface
     */
    private $dataPersister;

    private $entityManager;

    public function __construct(Connection $original, EntityManagerInterface $entityManager, DataPersisterInterface $dataPersister)
    {
        $this->builder = $original->createQueryBuilder();
        $this->entityManager = $entityManager;
        $this->default = $entityManager->getConnection();

        $this->dataPersister = $dataPersister;
    }

    public function run(Reporter $reporter): void
    {
        $data = $this->extract($this->builder);
        foreach ($data as $row) {
            $resume = $this->load($row);
            $reporter->dump($resume);
        }
    }

    protected function extract(QueryBuilder $builder): array
    {
        $this->configureDataLoader($builder);

        $data = $this->builder
            ->execute()
            ->fetchAll();

        return $data;
    }

    protected function load(array $input): Resume
    {
        try {
            $builder = $this->createBuilder($input, $this->entityManager);

        } catch (\Throwable $exception) {

            $id = sprintf('ID: %s', $input['id']);
            dump($exception->getMessage(), $id);
            $trace = sprintf('%s::%s', $exception->getFile(), $exception->getLine());
            dump($trace);
            die;
        }

        if ($builder->isValid()) {
            $entity = $builder->build();
            $this->dataPersister->persist($entity);
            $this->postPersist($entity);

            $this->entityManager->flush();
            $this->entityManager->clear();
        }

        return $builder->resume();
    }

    public function postPersist($entity)
    {

    }

    protected function truncate(string ...$tables): self
    {
        foreach ($tables as $table) {
            $this->default->query('SET FOREIGN_KEY_CHECKS=0');
            $this->default->query('DELETE FROM ' . $table);
            $this->default->query('SET FOREIGN_KEY_CHECKS=1');
        }

        return $this;
    }

    protected function loadSql(string ...$paths)
    {
        foreach ($paths as $path) {
            $sql = file_get_contents($path);
            $this->default->exec($sql);
        }
    }

}
