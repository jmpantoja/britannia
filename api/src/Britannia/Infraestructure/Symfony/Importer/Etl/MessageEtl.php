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
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Service\Course\TimeTableUpdater;
use Britannia\Infraestructure\Symfony\Importer\Builder\BuilderInterface;
use Britannia\Infraestructure\Symfony\Importer\Builder\MessageBuilder;
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

class MessageEtl extends AbstractEtl
{
    /**
     * @var Security
     */
    private Security $security;


    public function __construct(Connection $original,
                                EntityManagerInterface $entityManager,
                                DataPersisterInterface $dataPersister,
                                Security $secutiry
    )
    {
        parent::__construct($original, $entityManager, $dataPersister);

        $this->security = $secutiry;
    }


    public function clean(): void
    {
        $this->truncate(...[
            'messages',
            'message_course',
            'message_student',
            'message_sms',
            'message_email',
            'message_shipments'
        ]);
    }

    public function configureDataLoader(QueryBuilder $builder): void
    {
        $offset = 0;
        $limit = null;
        $id = null;

        $builder->select('A.id as ID, A.*, B.*')
            ->from('notificacionSms', 'A')
            ->innerJoin('A', 'colaSms', 'B', 'A.id = B.idNotificacionSms')
            ->orderBy('A.id')
            ->setFirstResult($offset)
            ->setMaxResults($limit);


        if (is_int($id)) {
            $builder
                ->andWhere('B.id = ?')
                ->setParameter(0, $id);
        }
    }

    protected function extract(QueryBuilder $builder): array
    {
        $currentId = null;
        $data = [];
        $values = parent::extract($builder);

        foreach ($values as $input) {

            $id = $input['ID'];
            $data[$id] = $data[$id] ?? $input;

            $data[$id]['phones'][] = [
                'phone' => $input['destinatario'],
                'state' => 1 === (int)$input['exito'],
                'raw' => preg_replace('/\D/', '', $input['destinatario'])
            ];
        }

        return $data;
    }


    public function createBuilder(array $input, EntityManagerInterface $entityManager): BuilderInterface
    {
        static $index = 0;
        $builder = MessageBuilder::make($input, $entityManager);
        $user = $this->security->getUser();

        $author = $entityManager->getReference(StaffMember::class, $user->id());

        $builder
            ->withSubject(++$index)
            ->withMessage($input['mensaje'])
            ->withStudents($input['alumnos'])
            ->withAuthor($author)
            ->withCreatedAt($input['createdAt'])
            ->withDate($input['fecha'], $input['hora'])
            ->withPhones($input['phones'] ?? []);


        return $builder;
    }

}
