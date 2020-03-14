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
use Britannia\Domain\Service\Course\TimeTableUpdater;
use Britannia\Infraestructure\Symfony\Importer\Builder\BuilderInterface;
use Britannia\Infraestructure\Symfony\Importer\Builder\IssueBuilder;
use Britannia\Infraestructure\Symfony\Importer\Console;
use Britannia\Infraestructure\Symfony\Importer\Converter\FullNameConverter;
use Britannia\Infraestructure\Symfony\Importer\DataCollector;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\ChildNormalizer;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\NormalizerInterface;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\StudentNormalizer;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class IssueEtl extends AbstractEtl
{
    /**
     * @var Security
     */
    private Security $security;

    public function __construct(Connection $original, EntityManagerInterface $entityManager, DataPersisterInterface $dataPersister, Security $security)
    {
        parent::__construct($original, $entityManager, $dataPersister);
        $this->security = $security;
    }


    public function clean(): void
    {
        $this->truncate('issues', 'issues_recipients');
    }

    public function configureDataLoader(QueryBuilder $builder): void
    {

        $offset = 0;
        $limit = null;
        $id = null;

        $builder->select('*')
            ->from('observaciones')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        if (is_int($id)) {
            $builder
                ->andWhere('id > ?')
                ->setParameter(0, $id);
        }
    }

    public function createBuilder(array $input, EntityManagerInterface $entityManager): BuilderInterface
    {

        $builder = IssueBuilder::make($input, $entityManager);
        $builder->setSecurity($this->security);

        $builder
            ->withStudent((int)$input['alumno'])
            ->withAuthor((int)($input['idAdmin'] ?? $input['idProfesor']))
                ->withMessage((string)$input['observacion'])
                ->withCreatedAt($input['fecha']);


        return $builder;
    }

}
