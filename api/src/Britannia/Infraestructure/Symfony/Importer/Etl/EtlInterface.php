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


use Britannia\Infraestructure\Symfony\Importer\Builder\BuilderInterface;
use Britannia\Infraestructure\Symfony\Importer\Console;
use Britannia\Infraestructure\Symfony\Importer\DataCollector;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\NormalizerInterface;
use Britannia\Infraestructure\Symfony\Importer\Report\Reporter;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;


interface EtlInterface
{

    public function clean(): void;

    public function configureDataLoader(QueryBuilder $builder): void;

    public function createBuilder(array $input, EntityManagerInterface $entityManager): BuilderInterface;

    public function run(Reporter $reporter): void;
}
