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
use Britannia\Infraestructure\Symfony\Importer\Builder\StaffBuilder;
use Britannia\Infraestructure\Symfony\Importer\Builder\TeacherCoursesBuilder;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;

final class TeacherCoursesEtl extends AbstractEtl
{

    public function clean(): void
    {
        $this->truncate('courses_teachers');
    }

    public function configureDataLoader(QueryBuilder $builder): void
    {
    }

    protected function extract(QueryBuilder $builder): array
    {
        $sql = <<<eof
SELECT id, user, nombre, password, null as email, dni, telefono, cursos, null as teacherId, true as is_teacher, false as is_admin  FROM academia_mysql.profesores
UNION
SELECT id, user, name as nombre, password, email, null as dni, null as telefono, null as cursos, idProfesorVinculado as teacherId, false as is_teacher, true as is_admin   FROM academia_mysql.user where idProfesorVinculado is null
eof;

        $query = $builder->getConnection()->executeQuery($sql);

        return $query->fetchAll();
    }

    public function createBuilder(array $input, EntityManagerInterface $entityManager): BuilderInterface
    {
        $builder = TeacherCoursesBuilder::make($input, $entityManager);

        $teacherId = $input['teacherId'];
        if (is_string($teacherId)) {
            $teacherId = (int)$teacherId;
        }

        $is_teacher = $input['is_teacher'] || !is_null($teacherId);
        if(!$is_teacher){
            return  $builder;
        }


        $builder
            ->withTeacher((int)$input['id'])
            ->withCourses((string)$input['cursos']);

        return $builder;

    }
}
