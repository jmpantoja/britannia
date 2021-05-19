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

namespace PlanB\DDDBundle\Symfony\Form\Type;


use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Sonata\ModelManager;
use Symfony\Component\OptionsResolver\OptionsResolver;

abstract class ModelType extends AbstractSingleType
{
    protected const MULTISELECT = 'multiselect';

    private $entityManager;
    /**
     * @var ModelManager
     */
    private ModelManager $modelManager;

    public function __construct(ModelManager $modelManager, EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->modelManager = $modelManager;
    }


    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return \Sonata\AdminBundle\Form\Type\ModelType::class;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired('class');
        $resolver->setAllowedTypes('class', ['string']);


        $resolver->setDefaults([
            'by_reference' => false,
            'multiple' => true,
            'expanded' => false,
            'model_manager' => $this->modelManager,
        ]);

        $resolver->setNormalizer('query', function (OptionsResolver $resolver) {
            return null;
        });

        $resolver->setNormalizer('choices', function (OptionsResolver $resolver) {
            $alias = 'A';

            $builder = $this->entityManager->createQueryBuilder()
                ->from($resolver['class'], $alias)
                ->select($alias);

            $this->configureQuery($builder, $resolver, $alias);

            $choices = $builder->getQuery()->execute();
            return $this->sanitizeChoices($choices, $resolver);
        });

        $resolver->setNormalizer('attr', function (OptionsResolver $resolver, $value) {
            if (self::MULTISELECT === $this->getBlockPrefix()) {
                $value['data-sonata-select2'] = 'false';
            }
            return $value;
        });

        $resolver->setNormalizer('multiple', function (OptionsResolver $resolver, $value) {
            if (self::MULTISELECT === $this->getBlockPrefix()) {
                return 'true';
            }
            return $value;
        });

        parent::configureOptions($resolver);
    }

    abstract public function configureQuery(QueryBuilder $builder, OptionsResolver $resolver, string $alias = 'A');

    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($data)
    {
        return $data;
    }

    protected function sanitizeChoices(array $choices, OptionsResolver $resolver): array
    {
        return $choices;
    }

}
