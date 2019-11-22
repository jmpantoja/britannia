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


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\VO\CourseStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Sonata\ModelManager;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TeachersType extends AbstractSingleType
{

    /**
     * @var Student
     */
    private $course;
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
            'class' => StaffMember::class,
            'property' => 'userName',
            'model_manager' => $this->modelManager,
            'multiple' => true,
            'by_reference' => false,
            'attr' => [
                'data-sonata-select2' => 'false'
            ]
        ]);

        $resolver->setNormalizer('query', function (OptionsResolver $resolver, $value) {
            $builder = $this->modelManager->createQuery(StaffMember::class, 'A');
            $builder->where('A.teacher = :yes')
                ->setParameter('yes', true);

            return $builder;
        });
    }

    public function transform($value)
    {
        return $value;
    }


    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($students)
    {
        return $students;
    }

}
