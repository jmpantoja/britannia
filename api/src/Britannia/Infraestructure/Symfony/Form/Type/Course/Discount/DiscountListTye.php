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


use Britannia\Domain\VO\Student\Job\JobStatus;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DiscountListTye extends AbstractCompoundType
{

    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $status = JobStatus::getDiscountables();

        foreach ($status as $name => $value) {

            $builder->add($name, DiscountType::class, [
                'required' => false,
                'label' => false,
                'status' => JobStatus::byName($name)
            ]);
        }
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Collection::class
        ]);
    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    protected function dataToForms($data, array $forms): void
    {
        foreach ($data as $name => $discount) {
            $form = $forms[$name] ?? null;
            $this->setData($form, $discount);
        }

    }

    /**
     * @param $form
     * @param $discount
     */
    protected function setData(?FormInterface $form, $discount): void
    {
        if (is_null($form)) {
            return;
        }

        $form->setData($discount);
    }


    public function customMapping(array $data)
    {

        $data = array_filter($data);

        return new ArrayCollection($data);
        return $data;
    }


}
