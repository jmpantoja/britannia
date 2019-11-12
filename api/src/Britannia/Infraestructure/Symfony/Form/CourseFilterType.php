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
use Britannia\Domain\Repository\CourseRepositoryInterface;
use Britannia\Domain\VO\Periodicity;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Sonata\ModelManager;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseFilterType extends AbstractSingleType
{

    /**
     * @var CourseRepositoryInterface
     */
    private $repository;

    public function __construct(CourseRepositoryInterface $repository)
    {

        $this->repository = $repository;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired([
            'teacher'
        ]);

        $resolver->setDefaults([
            'required' => false,
            'attr' => [
//                'style' => 'width: 190px'
            ]
        ]);

        $resolver->setNormalizer('choices', function (OptionsResolver $resolver) {
            $courses = [];

            $activeCourses = $resolver['teacher']->getActiveCourses();

            foreach ($activeCourses as $course) {
                $name = (string)$course;
                $courseId = (string)$course->getId();
                $courses[$name] = $courseId;
            }

            return $courses;
        });

    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($data)
    {

        if(is_string($data)){
            $data = $this->repository->find($data);
        }

        if (!($data instanceof Course)) {
            return null;
        }

        return $data;
    }

}
