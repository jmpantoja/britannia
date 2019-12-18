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

namespace Britannia\Infraestructure\Symfony\Form\Type\Student;


use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentId;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Sonata\ModelManager;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RelativesType extends AbstractSingleType
{

    /**
     * @var ModelManager
     */
    private $modelManager;

    public function __construct(ModelManager $modelManager)
    {
        $this->modelManager = $modelManager;
    }


    public function getParent()
    {
        return ModelType::class;
    }

    public function customOptions(OptionsResolver $resolver)
    {

        $resolver->setRequired(['studentId']);
        $resolver->setAllowedTypes('studentId', [StudentId::class]);

        $resolver->setDefaults([
            'by_reference' => false,
            'multiple' => true,
            'expanded' => false,
            'model_manager' => $this->modelManager,
            'class' => Student::class,
            'property' => 'fullName.reversedMode',
            'sonata_help' => 'Seleccione otros alumnos de la misma familia',
            'attr' => [
                'data-sonata-select2' => 'false'
            ]
        ]);


        $resolver->setRequired(['studentId']);
        $resolver->setAllowedTypes('studentId', [StudentId::class]);

        $resolver->setNormalizer('query', function (OptionsResolver $resolver, $value) {
            $builder = $this->modelManager->createQuery(Student::class, 'A');
            return $builder
                ->where('A.id != :id')
                ->setParameter('id', $resolver['studentId']);
        });

    }

    public function transform($value)
    {
        return $value;
    }


    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($data)
    {
        return $data;
    }
}
