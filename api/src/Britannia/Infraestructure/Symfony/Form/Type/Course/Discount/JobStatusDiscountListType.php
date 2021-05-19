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


use Britannia\Domain\VO\Discount\JobStatusDiscountList;
use Britannia\Domain\VO\Student\Job\JobStatus;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class JobStatusDiscountListType extends AbstractCompoundType
{


    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $status = JobStatus::getDiscountables();

        foreach ($status as $key => $value) {
            $name = strtolower($key);

            $builder->add($name, JobDiscountType::class, [
                'required' => false,
                'label' => false,
                'status' => JobStatus::byName($key),
                'enable_default_data' => $options['enable_default_data']
            ]);
        }
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => JobStatusDiscountList::class,
            'enable_default_data' => true
        ]);

        $resolver->setRequired('enable_default_data')
            ->setAllowedTypes('enable_default_data', ['bool']);

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
        return JobStatusDiscountList::make($data);
    }


}
