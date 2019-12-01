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

namespace Britannia\Infraestructure\Symfony\Form\Type\Course;


use Britannia\Domain\Entity\Course\Level;
use PlanB\DDDBundle\Sonata\ModelManager;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class LevelType extends ModelType
{
    /**
     * @var ModelManager
     */
    private $modelManager;

    public function __construct(PropertyAccessorInterface $propertyAccessor, ModelManager $modelManager)
    {
        $this->modelManager = $modelManager;
        parent::__construct($propertyAccessor);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'multiple' => false,
            'expanded' => false,
            'btn_add' => false,
            'attr'=>[
                'style' => 'width:200px'
            ]
        ]);

        $resolver->setNormalizer('query', function (OptionsResolver $resolver) {
            return $this->getQuery();
        });
    }


    /**
     * @return mixed
     */
    private function getQuery()
    {
        $query = $this->modelManager->createQuery(Level::class)
            ->getQueryBuilder()
            ->orderBy('o.name')
            ->getQuery();

        return $query;
    }

}
