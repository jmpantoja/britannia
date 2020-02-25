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


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseList;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\Entity\Student\StudentCourseList;
use Britannia\Domain\VO\Course\CourseStatus;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Sonata\ModelManager;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentHasCoursesType extends AbstractSingleType
{

    /**
     * @var Student
     */
    private $student;
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
            'class' => Course::class,
            'property' => 'name',
            'multiple' => true,
            'by_reference' => false,
            'model_manager' => $this->modelManager,
            'attr' => [
                'data-sonata-select2' => 'false'
            ]
        ]);

        $resolver->setRequired([
            'student'
        ]);

        $resolver->setAllowedTypes('student', [Student::class]);
        $resolver->setNormalizer('student', function (OptionsResolver $resolver, $value) {
            return $this->student = $value;
        });

        $resolver->setNormalizer('query', function (OptionsResolver $resolver, $value) {
            $builder = $this->modelManager->createQuery(Course::class, 'A');

            return $builder
                ->where('A.timeRange.status != :finalized')
                ->setParameter('finalized', CourseStatus::FINALIZED());
        });
    }

    /**
     * @param StudentCourse[] $value
     * @return Course[]
     */
    public function transform($value)
    {
        return StudentCourseList::collect($value)
            ->onlyActives()
            ->toCourseList()
            ->toArray();
    }


    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($courses)
    {
        return CourseList::collect($courses);
    }
}
