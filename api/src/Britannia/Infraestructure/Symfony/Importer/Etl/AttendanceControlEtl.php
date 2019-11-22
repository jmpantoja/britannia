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


use Britannia\Infraestructure\Symfony\Importer\Builder\AttendanceControlBuilder;
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

class AttendanceControlEtl extends AbstractEtl
{

    public function clean(): void
    {
        $this->truncate('attendance_control');
    }

    public function configureDataLoader(QueryBuilder $builder): void
    {
        $offset = 0;
        $limit = null;
        $id = null;

        $builder->select('*')
            ->from('controlAsistencia')
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

        $builder = AttendanceControlBuilder::make($input, $entityManager);

        $builder->withLesson((int)$input['curso'], (string)$input['fecha']);
        $builder->withStudent((int)$input['alumno']);
        $builder->withReason ((string)$input['motivo']);

        return $builder;
    }

}
