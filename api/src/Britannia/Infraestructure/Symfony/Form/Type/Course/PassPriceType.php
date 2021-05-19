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


use Britannia\Domain\Entity\Setting\Setting;
use Britannia\Domain\Repository\JobStatusDiscountParametersInterface;
use Britannia\Domain\VO\Course\Discount\CourseDiscount;
use Britannia\Domain\VO\Course\Pass\PassPriceList;
use Britannia\Domain\VO\Discount\JobStatusDiscountList;
use Britannia\Domain\VO\Student\Job\JobStatus;
use Britannia\Infraestructure\Symfony\Form\Type\Course\Discount\FreeEnrollmentType;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\PercentageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PassPriceType extends AbstractCompoundType
{

    /**
     * @var PassPriceList
     */
    private $passPriceList;

    public function __construct(Setting $setting)
    {
        $this->passPriceList = $setting->passPriceList();
    }

    public function customForm(FormBuilderInterface $builder, array $options)
    {

        $passHours = $options['passHours'];

        $freeEnrollment = null;
        $discount = null;

        if ($options['enable_default_data']) {
            $courseDiscount = $this->passPriceList->getByJobStatus($passHours);
            $freeEnrollment = $courseDiscount->isFreeEnrollment();
            $discount = $courseDiscount->getDiscount();
        }

        $builder
            ->add('freeEnrollment', FreeEnrollmentType::class, [
                'empty_data' => $freeEnrollment
            ])
            ->add('discount', PercentageType::class, [
                'label' => 'descuento',
                'required' => true,
                'empty_data' => $discount
            ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CourseDiscount::class
        ]);

        $resolver->setRequired(['status', 'enable_default_data'])
            ->setAllowedTypes('status', [JobStatus::class])
            ->setAllowedTypes('enable_default_data', ['bool']);


    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['status'] = $options['status'];
        parent::buildView($view, $form, $options);
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

        $freeEnrollment = $data['freeEnrollment'] ?? false;
        if ($freeEnrollment) {
            return CourseDiscount::withoutEnrollment($data['discount']);
        }

        return CourseDiscount::withEnrollment($data['discount']);
    }
}
