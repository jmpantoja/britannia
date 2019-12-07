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

namespace Britannia\Infraestructure\Symfony\Form\Type\Course\Discount;


use Britannia\Domain\Repository\JobStatusDiscountStorageInterface;
use Britannia\Domain\VO\Course\Discount\CourseDiscount;
use Britannia\Domain\VO\Student\Job\JobStatus;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\PercentageType;
use PlanB\DDDBundle\Symfony\Form\Type\ToggleType;
use Sonata\Form\Type\BooleanType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiscountType extends AbstractCompoundType
{

    /**
     * @var \Britannia\Domain\VO\Discount\JobStatusDiscountList
     */
    private $jobStatusDiscountList;

    public function __construct(JobStatusDiscountStorageInterface $jobStatusDiscountStorage)
    {
        $this->jobStatusDiscountList = $jobStatusDiscountStorage->getList();
    }

    public function customForm(FormBuilderInterface $builder, array $options)
    {

        $builder
            ->add('freeEnrollment', FreeEnrollmentType::class)
            ->add('discount', PercentageType::class, [
                'label' => 'descuento',
                'required' => true,
                'empty_data' => $options['discount']
            ]);


    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => CourseDiscount::class,
            'discount' => null
        ]);

        $resolver->setRequired(['status', 'discount']);
        $resolver->setAllowedTypes('status', [JobStatus::class]);

        $resolver->setNormalizer('discount', function (OptionsResolver $resolver) {

            $jobStatus = $resolver['status'];
            $discount = $this->jobStatusDiscountList->getByJobStatus($jobStatus);

            return $discount ?? Percent::make(0);
        });
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['status'] = $options['status'];
        parent::buildView($view, $form, $options);
    }

    protected function dataToForms($data, array $forms): void
    {
        parent::dataToForms($data, $forms);
        $forms['freeEnrollment']->setData($data->isFreeEnrollment());
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
        $freeEnrollment = $data['freeEnrollment'] ?? false;

        if ($freeEnrollment) {
            return CourseDiscount::withoutEnrollment($data['discount']);
        }

        return CourseDiscount::withEnrollment($data['discount']);

    }
}
