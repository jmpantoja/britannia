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
use Britannia\Infraestructure\Symfony\Importer\Builder\AttachmentBuilder;
use Britannia\Infraestructure\Symfony\Importer\Builder\BuilderInterface;
use Britannia\Infraestructure\Symfony\Service\FileUpload\AttachmentUploader;
use Britannia\Infraestructure\Symfony\Service\FileUpload\FileUploader;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

final class AttachmentEtl extends AbstractEtl
{
    /**
     * @var string
     */
    private $sourceDir;
    /**
     * @var FileUploader
     */
    private FileUploader $uploader;

    public function __construct(Connection $original,
                                EntityManagerInterface $entityManager,
                                DataPersisterInterface $dataPersister,
                                ParameterBagInterface $parameterBag,
                                AttachmentUploader $uploader)
    {
        parent::__construct($original, $entityManager, $dataPersister);
        $attachmentsDir = $parameterBag->get('attachments_dir');
        $this->sourceDir = sprintf('%s/%s', ...[
            dirname($attachmentsDir),
            'older'
        ]);
        $this->uploader = $uploader;
    }


    public function clean(): void
    {
        $this->truncate('student_attachment');
    }

    public function configureDataLoader(QueryBuilder $builder): void
    {
        $offset = 0;
        $limit = null;
        $id = null;

        $builder->select('F.*', 'S.id as old_id')
            ->from('files', 'F')
            ->innerJoin('F', 'alumnos', 'S', 'F.token = S.token')
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
        $builder = AttachmentBuilder::make($input, $entityManager);
        $builder
            ->withOldId($input['old_id'])
            ->withDescription($input['title'], $input['description'])
            ->withFilename($this->sourceDir, $input['filename'], $this->uploader)
            ->withDates($input['created_at'], $input['linked_at']);

        return $builder;
    }
}
