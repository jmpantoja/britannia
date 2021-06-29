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

namespace PlanB\DDDBundle\Sonata;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\Common\Util\ClassUtils;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use League\Tactician\CommandBus;
use PlanB\DDDBundle\Doctrine\DBAL\Type\EntityIdType;
use Ramsey\Uuid\Uuid;
use Sonata\DoctrineORMAdminBundle\Model\ModelManager as ORMModelManager;
use Symfony\Component\Cache\Adapter\AdapterInterface;

class ModelManager extends ORMModelManager
{
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var CommandFactoryInterface
     */
    private $commandFactory;
    /**
     * @var DataPersisterInterface
     */
    private $dataPersister;

    /** @var AdapterInterface */
    private $adapter;

    public function __construct(ManagerRegistry $registry, DataPersisterInterface $dataPersister, AdapterInterface $cache)
    {
        $this->dataPersister = $dataPersister;

        parent::__construct($registry);
        $this->adapter = $cache;
    }

    public function create($object)
    {
        $this->dataPersister->persist($object);
        $this->cleanCache($object);
    }

    public function update($object)
    {
        $this->dataPersister->persist($object);
        $this->cleanCache($object);
    }

    public function delete($object)
    {
        $this->dataPersister->remove($object);
        $this->cleanCache($object);
    }

    /**
     * @param object $object
     */
    private function cleanCache(object $object): void
    {
        $className = get_class($object);
        do {
            $this->adapter->delete(normalize_key($className));
            $className = get_parent_class($className);
        } while ($className);
    }

    public function getIdentifierValues($entity)
    {
        $class = ClassUtils::getClass($entity);
        $metadata = $this->getMetadata($class);
        $platform = $this->getEntityManager($class)->getConnection()->getDatabasePlatform();

        $identifiers = [];

        foreach ($metadata->getIdentifierValues($entity) as $name => $value) {
            if (!\is_object($value)) {
                $identifiers[] = $value;

                continue;
            }

            $fieldType = $metadata->getTypeOfField($name);
            $type = $fieldType && Type::hasType($fieldType) ? Type::getType($fieldType) : null;
            if ($type) {
                $identifiers[] = $this->getValueFromType($value, $type, $fieldType, $platform);

                continue;
            }

            $metadata = $this->getMetadata(ClassUtils::getClass($value));

            foreach ($metadata->getIdentifierValues($value) as $value) {
                $identifiers[] = $value;
            }
        }

        return $identifiers;
    }

    private function getValueFromType($value, Type $type, string $fieldType, AbstractPlatform $platform): string
    {
        if (!$platform->hasDoctrineTypeMappingFor($fieldType)) {
            return (string)$type->convertToDatabaseValue($value, $platform);
        }

        if ('binary' === $platform->getDoctrineTypeMapping($fieldType)) {
            return (string)$type->convertToPHPValue($value, $platform);
        }

        if ($type instanceof EntityIdType) {
            $uuid = Uuid::fromString((string)$value);
            return (string)$type->convertToPHPValue($uuid, $platform);
        }

        return (string)$type->convertToDatabaseValue($value, $platform);
    }


}
