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

namespace Britannia\Infraestructure\Symfony\Form\Report\CourseInfo;


use Britannia\Application\UseCase\Report\GenerateCourseInformation;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\VO\CourseInfoData;
use Britannia\Domain\VO\Discount;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Sonata\ModelManager;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Sonata\AdminBundle\Model\ModelManagerInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseInformationType extends AbstractCompoundType
{

    /**
     * @var ModelManagerInterface
     */
    private $modelManager;

    public function __construct(ModelManager $modelManager)
    {
        $this->modelManager = $modelManager;
    }

    public function customForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('course', EntityType::class, [
                'class' => Course::class,
                'choice_label' => 'name',
                'data' => $options['course'],
                'required' => true
            ])
            ->add('student', EntityType::class, [
                'class' => Student::class,
                'choice_label' => 'fullName',
                'placeholder' => 'Alumno genérico',
                'required' => false
            ])
            ->add('generar', SubmitType::class, [
                'attr' => [
                    'class' => 'btn btn-success'
                ]
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => GenerateCourseInformation::class,
            'course' => null,
            'attr' => [
                'novalidate' => 'true'
            ]
        ]);

        $resolver->setAllowedTypes('course', ['null', Course::class]);

    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }


    public function customMapping(array $data)
    {
        $course = $data['course'];
        $student = $data['student'] ?? null;


        return GenerateCourseInformation::make($course, $student);
    }
}
