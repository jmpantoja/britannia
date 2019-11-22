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
use Britannia\Infraestructure\Symfony\Importer\Builder\CourseBuilder;
use Britannia\Infraestructure\Symfony\Importer\Builder\StudentCoursesBuilder;
use Britannia\Infraestructure\Symfony\Importer\Builder\StudentBuilder;
use Britannia\Infraestructure\Symfony\Importer\Console;
use Britannia\Infraestructure\Symfony\Importer\Converter\FullNameConverter;
use Britannia\Infraestructure\Symfony\Importer\DataCollector;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\ChildNormalizer;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\NormalizerInterface;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\StudentNormalizer;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

class StudentCoursesEtl extends AbstractEtl
{

    public function clean(): void
    {
        $this->truncate('students_courses');
    }

    public function configureDataLoader(QueryBuilder $builder): void
    {
        $offset = 0;
        $limit = null;
        $id = null;

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

        return $builder;
    }

}
