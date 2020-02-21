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

namespace Britannia\Infraestructure\Symfony\Form\Type\Staff;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseList;
use Britannia\Domain\VO\Course\CourseStatus;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Sonata\ModelManager;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeacherHasCoursesType extends AbstractSingleType
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
        $resolver->setDefaults([
            'by_reference' => false,
            'multiple' => true,
            'expanded' => false,
            'model_manager' => $this->modelManager,
            'class' => Course::class,
            'property' => 'name',
            'sonata_help' => 'Seleccione otros alumnos de la misma familia',
            'attr' => [
                'data-sonata-select2' => 'false'
            ]
        ]);

        $resolver->setNormalizer('query', function (OptionsResolver $resolver, $value) {
            $builder = $this->modelManager->createQuery(Course::class, 'A');
            return $builder
                ->where('A.timeRange.status= :param')
                ->setParameter('param', CourseStatus::ACTIVE());
        });

    }

    public function transform($value)
    {
        return $value;
    }

    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($data)
    {
        return CourseList::collect($data);
    }
}
