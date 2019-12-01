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


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentCourse;
use Britannia\Domain\VO\CourseStatus;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\QueryBuilder;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Sonata\ModelManager;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Sonata\AdminBundle\Form\Type\ModelType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseHasStudentsType extends AbstractSingleType
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
            'class' => Student::class,
            'property' => 'fullName.reversedMode',
            'model_manager' => $this->modelManager,
            'multiple' => true,
            'by_reference' => false,
            'attr' => [
                'data-sonata-select2' => 'false'
            ]
        ]);

        $resolver->setRequired([
            'course'
        ]);

        $resolver->setAllowedTypes('course', [Course::class]);
        $resolver->setNormalizer('course', function (OptionsResolver $resolver, $value) {
            return $this->course = $value;
        });

        $resolver->setNormalizer('query', function (OptionsResolver $resolver, $value) {
            $builder = $this->modelManager->createQuery(Student::class, 'A');

            return $builder;
        });
    }

    public function transform($value)
    {
        if (empty($value)) {
            return $value;
        }

        $results = [];

        /** @var StudentCourse $studentCourse */
        foreach ($value as $studentCourse) {
            $results[] = $studentCourse->getStudent();
        }

        return $results;
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
        $results = new ArrayCollection();

        foreach ($students as $student) {
            $studentCourse = $this->create($student);
            $results->add($studentCourse);
        }

        /** @var QueryBuilder $builder */
        $builder = $this->modelManager->createQuery(StudentCourse::class, 'A');

        $builder
            ->where('A.course = :course')
            ->setParameter('course', $this->course);

        if (!$results->isEmpty()) {
            $builder->andWhere('A.student not IN (:students)')
                ->setParameter('students', $students);
        }

        $toRemove = $builder->getQuery()->execute();

        foreach ($toRemove as $item){
            $this->modelManager->delete($item);
        }


        return $results;
    }

    /**
     * @param $student
     * @return StudentCourse
     */
    protected function create(Student $student): StudentCourse
    {
        $repository = $this->modelManager->getEntityManager(StudentCourse::class)
            ->getRepository(StudentCourse::class);

        $studentCourse = $repository->findOneBy([
            'course' => $this->course,
            'student' => $student
        ]);

        return $studentCourse ?? StudentCourse::make($student, $this->course);

    }

}
