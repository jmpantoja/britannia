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
use Britannia\Domain\VO\CourseInfoData;
use Britannia\Domain\VO\Discount\StudentDiscount;
use Britannia\Infraestructure\Symfony\Admin\Report\ReportAdmin;
use Britannia\Infraestructure\Symfony\Form\Type\Course\Discount\FamilyOrderType;
use Britannia\Infraestructure\Symfony\Form\Type\Course\Discount\FreeEnrollmentType;
use Britannia\Infraestructure\Symfony\Form\Type\Student\JobStatusType;
use Britannia\Infraestructure\Symfony\Service\Course\BoundariesInformation;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use IntlDateFormatter;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\PriceType;
use Sonata\Form\Type\DatePickerType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CourseInformationType extends AbstractCompoundType
{

    /**
     * @var BoundariesInformation
     */
    private BoundariesInformation $boundaries;

    public function __construct(BoundariesInformation $boundaries)
    {
        $this->boundaries = $boundaries;
    }

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $course = $options['data'];

        $builder->add('selected', HiddenType::class);
        $builder
            ->add('freeEnrollment', FreeEnrollmentType::class)
            ->add('familyOrder', FamilyOrderType::class)
            ->add('jobStatus', JobStatusType::class)
            ->add('startDate', DatePickerType::class, [
                'format' => IntlDateFormatter::LONG,
                'label' => false,
                'sonata_help' => 'Se incorpora con el curso comenzado'
            ])
            ->add('firstMonth', PriceType::class, [
                'required' => false,
                'label' => 'Precio primer mes',
                'data' => $this->boundaries->firstMonthly($course)
            ])
            ->add('lastMonth', PriceType::class, [
                'required' => false,
                'label' => 'Precio último mes',
                'data' => $this->boundaries->lastMonthly($course)
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

        $resolver->setRequired('admin');
        $resolver->setAllowedTypes('admin', [ReportAdmin::class]);
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
        $course = $this->getOption('data');
        $startDate = $data['startDate'];

        $discount = StudentDiscount::make(...[
            $data['familyOrder'],
            $data['jobStatus'],
            $startDate,
            $data['freeEnrollment'],
            $data['firstMonth'],
            $data['lastMonth'],
        ]);

        return GenerateCourseInformation::make($course, $discount);
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        /** @var Course $course */
        $course = $options['data'];

        $view->vars['first_month_description'] = $this->boundaries->firstMonthDescription($course);
        $view->vars['last_month_description'] = $this->boundaries->lastMonthDescription($course);

        $view->vars['url'] = $options['admin']->generateObjectUrl('boundaries-prices', $course);

        parent::finishView($view, $form, $options);
    }

}
