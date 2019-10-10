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

namespace Britannia\Infraestructure\Symfony\Form;


use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentId;
use PlanB\DDDBundle\Sonata\ModelManager;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;

class RelativesType extends ModelType
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

        $resolver->setRequired(['studentId']);
        $resolver->setAllowedTypes('studentId', [StudentId::class]);

        $resolver->setDefaults([
            'by_reference' => false,
            'multiple' => true,
            'expanded' => false,
            'property' => 'fullName.reversedMode',
            'sonata_help'=> 'Seleccione otros alumnos de la misma familia',
        ]);

        $resolver->setNormalizer('query', function (OptionsResolver $resolver) {
            return $this->getRelativeQuery($resolver['studentId']);
        });
    }


    /**
     * @return mixed
     */
    private function getRelativeQuery(StudentId $studentId)
    {
        $query = $this->modelManager->createQuery(Student::class)
            ->where('o.id != :id')
            ->setParameter('id', $studentId)
            ->getQuery();
        return $query;
    }

}
