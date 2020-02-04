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
use Britannia\Domain\Service\Assessment\AssessmentGenerator;
use Britannia\Infraestructure\Symfony\Importer\Builder\BuilderInterface;
use Britannia\Infraestructure\Symfony\Importer\Builder\StudentCoursesBuilder;
use Britannia\Infraestructure\Symfony\Importer\Console;
use Britannia\Infraestructure\Symfony\Importer\Converter\FullNameConverter;
use Britannia\Infraestructure\Symfony\Importer\DataCollector;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\ChildNormalizer;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\NormalizerInterface;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\StudentNormalizer;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

class StudentCoursesEtl extends AbstractEtl
{
    /**
     * @var AssessmentGenerator
     */
    private AssessmentGenerator $assessmentGenerator;

    public function __construct(Connection $original,
                                EntityManagerInterface $entityManager,
                                DataPersisterInterface $dataPersister,
                                AssessmentGenerator $assessmentGenerator

    )
    {
        parent::__construct($original, $entityManager, $dataPersister);
        $this->assessmentGenerator = $assessmentGenerator;
    }


    public function clean(): void
    {
        $this->truncate('students_courses', 'assessment_term', 'assessment_unit');
    }

    public function configureDataLoader(QueryBuilder $builder): void
    {
        $offset = 0;
        $limit = null;
        $id = 1688;

        $builder->select('*')
            ->from('alumnos')
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
        /** @var StudentCoursesBuilder $builder */
        $builder = StudentCoursesBuilder::make($input, $entityManager);

        $builder->withCourses((string)$input['curso']);
        $builder->withStudent((int)$input['id']);
        $builder->withGenerator($this->assessmentGenerator);

        return $builder;
    }

}
