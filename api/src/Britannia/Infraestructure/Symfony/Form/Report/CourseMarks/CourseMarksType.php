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


use Britannia\Application\UseCase\CourseReport\GenerateTermMarks;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseAssessmentInterface;
use Britannia\Domain\VO\CourseInfoData;
use Britannia\Infraestructure\Symfony\Form\Report\CourseMarks\Validator\CourseMarks;
use Britannia\Infraestructure\Symfony\Form\Type\Assessment\TermNameType;
use Britannia\Infraestructure\Symfony\Form\Type\Date\DateType;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseMarksType extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {

        /** @var CourseAssessmentInterface $course */
        $course = $options['data'];

        $builder->add('selected', HiddenType::class);
        $builder->add('termName', TermNameType::class, [
            'mapped' => false,
            'numOfTerms' => $course->assessment()->numOfTerms(),
            'attr' => [
                'style' => 'width: 235px;'
            ]
        ]);

        $builder->add('start', DateType::class, [
            'mapped' => false,
//            'format' => \IntlDateFormatter::LONG,
            'label' => 'Desde',
            'attr' => [
                'readonly' => true
            ]
        ]);

        $builder->add('end', DateType::class, [
            'mapped' => false,
            'label' => 'Hasta',
            //    'format' => \IntlDateFormatter::LONG
        ]);

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

        $resolver->setRequired('url');
        $resolver->setAllowedTypes('url', ['string']);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['url'] = $options['url'];
        parent::finishView($view, $form, $options); // TODO: Change the autogenerated stub
    }

    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return new CourseMarks();
    }

    protected function validate($data, array $forms, Constraint $constraint): bool
    {
        parent::validate($data, $forms, $constraint); // TODO: Change the autogenerated stub
        return true;
    }


    public function customMapping(array $data)
    {
        if (empty($data['selected'])) {
            return;
        }

        $termName = $data['termName'];
        $students = $data['students'];

        $start = CarbonImmutable::make($data['start']);
        $end = CarbonImmutable::make($data['end']);

        /** @var Course $course */
        $course = $this->getOption('data');
        $course->setLimitsToTerm($termName, $start, $end);

        return GenerateTermMarks::make($course, $termName, $students);
    }
}
