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
use Britannia\Domain\Service\Course\LessonGenerator;
use Britannia\Domain\Service\Course\TimeTableUpdater;
use Britannia\Infraestructure\Symfony\Importer\Builder\BuilderInterface;
use Britannia\Infraestructure\Symfony\Importer\Builder\CourseBuilder;
use Britannia\Infraestructure\Symfony\Importer\Console;
use Britannia\Infraestructure\Symfony\Importer\Converter\FullNameConverter;
use Britannia\Infraestructure\Symfony\Importer\DataCollector;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\ChildNormalizer;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\NormalizerInterface;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\StudentNormalizer;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

class CourseEtl extends AbstractEtl
{

    /**
     * @var LessonGenerator
     */
    private LessonGenerator $lessonGenerator;
    /**
     * @var AssessmentGenerator
     */
    private AssessmentGenerator $unitGenerator;

    public function __construct(
        Connection $original,
        EntityManagerInterface $entityManager,
        DataPersisterInterface $dataPersister,
        LessonGenerator $lessonGenerator,
        AssessmentGenerator $unitGenerator
    )
    {
        $this->lessonGenerator = $lessonGenerator;
        $this->unitGenerator = $unitGenerator;

        parent::__construct($original, $entityManager, $dataPersister);
    }


    public function clean(): void
    {
        $this->truncate('courses', 'course_lessons', 'classrooms');
    }

    public function configureDataLoader(QueryBuilder $builder): void
    {
        $offset = 0;
        $limit = null;
        $id = null;

        $builder->select('*')
            ->from('grupos')
            ->setFirstResult($offset)
            ->setMaxResults($limit);

        if (is_int($id)) {
            $builder
                ->andWhere('id = ?')
                ->setParameter(0, $id);
        }
    }


    public function createBuilder(array $input, EntityManagerInterface $entityManager): BuilderInterface
    {
        /** @var CourseBuilder $builder */
        $builder = CourseBuilder::make($input, $entityManager);

        $categories = explode(',', (string)$input['valoresCategorias']);
        $categories = array_filter($categories);

        $categories = array_map(function ($category) {
            return $category * 1;
        }, $categories);


        $builder
            ->withId($input['id'])
            ->withName((string)$input['nombre'])
            ->withDescription((string)$input['curso'])
            ->withEnrolmentPayment((float)$input['matricula'])
            ->withMonthlyPayment((float)$input['precio'])
            ->withNumOfPlaces((int)$input['plazas'])
            ->withGenerator($this->lessonGenerator, $this->unitGenerator)
            ->withTimeTable(...[
                (string)$input['fecha_inicio'],
                (string)$input['fecha_final'],
                (string)$input['horario'],
                (string)$input['materiales'],
                (string)$input['numeroAula']
            ])
            ->withCategories($categories);

        return $builder;
    }

}
