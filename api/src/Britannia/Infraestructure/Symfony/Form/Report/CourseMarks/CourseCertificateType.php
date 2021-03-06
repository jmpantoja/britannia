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

namespace Britannia\Infraestructure\Symfony\Form\Report\CourseMarks;


use Britannia\Application\UseCase\CourseReport\GenerateCertificate;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\CourseInfoData;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseCertificateType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {

        /** @var Course $course */
        $course = $options['data'];

        $builder->add('selected', HiddenType::class);

        $builder->add('students', ChooseStudentListType::class, [
            'label' => false,
            'mapped' => false,
            'data' => $course
        ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
            'mapped' => false,
            'attr' => [
                'novalidate' => 'true'
            ]
        ]);
    }

    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data)
    {
        if (empty($data['selected'])) {
            return;
        }

        /** @var Course $course */
        $course = $this->getOption('data');
        $students = $data['students'];

        return GenerateCertificate::make($course, $students);
    }
}
