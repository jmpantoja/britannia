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
use Britannia\Domain\Entity\Student\StudentList;
use Britannia\Infraestructure\Symfony\Validator\FullName;
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

        $resolver->setNormalizer('query', function (OptionsResolver $resolver) {
            return $this->createQuery($resolver['course']);
        });
    }

    private function createQuery(Course $course): QueryBuilder
    {
        $query = $this->modelManager->getEntityManager(Student::class)
            ->createQueryBuilder('o')
            ->select('o')
            ->from(Student::class, 'o');

        if ($course->isAdult()) {
            $query->where('o.age >= 17');
        }

        if ($course->isSchool() or $course->isSupport()) {
            $query->where('o.age >= 6 AND o.age <= 20');
        }

        if ($course->isPreSchool()) {
            $query->where('o.age <= 6');
        }

        return $query;
    }

    /**
     * @param StudentCourse[] $value
     * @return Student[]
     */
    public function transform($value)
    {
        if (empty($value)) {
            return $value;
        }

        $results = [];
        foreach ($value as $studentCourse) {
            $results[] = $studentCourse->student();
        }

        return $results;
    }

    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($students)
    {
        return StudentList::collect($students);
    }

}
