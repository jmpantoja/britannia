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

//        $builder->add('termName', TermNameType::class, [
//            'mapped' => false,
//            'attr' => [
//                'style' => 'width: 190px'
//            ]
//        ]);
//
//        $builder->add('start', DatePickerType::class, [
//            'mapped' => false,
//            'format' => \IntlDateFormatter::LONG,
//            'attr' => [
//                'readonly' => true
//            ]
//        ]);
//
//        $builder->add('end', DatePickerType::class, [
//            'mapped' => false,
//            'format' => \IntlDateFormatter::LONG
//        ]);

        $builder->add('students', ChooseStudentListType::class, [
            'label' => 'Alumnos',
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
//
//        $resolver->setRequired('url');
//        $resolver->setAllowedTypes('url', ['string']);
    }

//    public function finishView(FormView $view, FormInterface $form, array $options)
//    {
//        $view->vars['url'] = $options['url'];
//        parent::finishView($view, $form, $options); // TODO: Change the autogenerated stub
//    }

    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
//        return new CourseMarks();
    }

//    protected function validate($data, array $forms, Constraint $constraint): bool
//    {
//        parent::validate($data, $forms, $constraint); // TODO: Change the autogenerated stub
//        return true;
//    }


    public function customMapping(array $data)
    {
        if (empty($data['selected'])) {
            return;
        }

        /** @var Course $course */
        $course = $this->getOption('data');

//        $termName = $data['termName'];
        $students = $data['students'];
//
//        $start = CarbonImmutable::make($data['start']);
//        $end = CarbonImmutable::make($data['end']);


//        $course->setLimitsToTerm($termName, $start, $end);

        return GenerateCertificate::make($course, $students);
    }
}
