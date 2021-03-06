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
use Britannia\Infraestructure\Symfony\Importer\Builder\BuilderInterface;
use Britannia\Infraestructure\Symfony\Importer\Builder\StaffBuilder;
use Britannia\Infraestructure\Symfony\Importer\Console;
use Britannia\Infraestructure\Symfony\Importer\Converter\FullNameConverter;
use Britannia\Infraestructure\Symfony\Importer\DataCollector;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\ChildNormalizer;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\NormalizerInterface;
use Britannia\Infraestructure\Symfony\Importer\Normalizer\StudentNormalizer;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Encoder\EncoderFactoryInterface;

class StaffEtl extends AbstractEtl
{
    /**
     * @var EncoderFactoryInterface
     */
    private EncoderFactoryInterface $encoderFactory;

    public function __construct(Connection $original,
                                EntityManagerInterface $entityManager,
                                DataPersisterInterface $dataPersister,
                                EncoderFactoryInterface $encoderFactory
    )
    {
        parent::__construct($original, $entityManager, $dataPersister);
        $this->encoderFactory = $encoderFactory;
    }


    public function clean(): void
    {
        $this->truncate('staff_members');
    }

    public function createBuilder(array $input, EntityManagerInterface $entityManager): BuilderInterface
    {

        $builder = StaffBuilder::make($input, $entityManager);

        $teacherId = $input['teacherId'];
        if (is_string($teacherId)) {
            $teacherId = (int)$teacherId;
        }


        $is_teacher = $input['is_teacher'] || !is_null($teacherId);
        $is_admin = $input['is_admin'];
        $builder
            ->withId((int)$input['id'])
            ->withTeacherId($teacherId)
            ->withRoles((bool)$is_teacher, (bool)$is_admin)
            ->withUserName((string)$input['user'])
            ->withPassword((string)$input['password'])
            ->withFullName((string)$input['nombre'])
            ->withEmail((string)$input['email'], (string)$input['telefono'], (string)$input['dni'])
            ->withDni((string)$input['dni'])
            ->withCourses((string)$input['cursos'])
            ->withPhone((string)$input['telefono'])
            ->withEncoder($this->encoderFactory);


        return $builder;
    }

    public function configureDataLoader(QueryBuilder $builder): void
    {
        // TODO: Implement configureDataLoader() method.
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
}
