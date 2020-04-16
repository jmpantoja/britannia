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

namespace Britannia\Infraestructure\Symfony\Form\Type\Invoice;


use Britannia\Domain\Entity\Invoice\InvoiceDetail;
use Britannia\Domain\Entity\Invoice\InvoiceDetailDto;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractCompoundType;
use PlanB\DDDBundle\Symfony\Form\Type\PercentageType;
use PlanB\DDDBundle\Symfony\Form\Type\PositiveIntegerType;
use PlanB\DDDBundle\Symfony\Form\Type\PriceType;
use PlanB\DDDBundle\Symfony\Form\Type\RefundPriceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class InvoiceDetailType extends AbstractCompoundType
{


    public function customForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('subject', null, [
            'label' => false,
            'attr' => [
                'style' => 'width:100%'
            ]
        ]);
        $builder->add('numOfUnits', PositiveIntegerType::class, [
            'label' => false,
            'attr' => [
                'style' => 'width:100%'
            ]
        ]);
        $builder->add('price', RefundPriceType::class, [
            'label' => false,
            'attr' => [
                'style' => 'width:100%'
            ]
        ]);
        $builder->add('discount', PercentageType::class, [
            'label' => false,
            'attr' => [
                'style' => 'width:100%'
            ]
        ]);
        $builder->add('total', RefundPriceType::class, [
            'label' => false,
            'attr' => [
                'readonly' => true,
                'style' => 'width:100%'
            ]
        ]);
    }

    public function customOptions(OptionsResolver $resolver)
    {
//        $resolver->setDefaults([
//            'class' => InvoiceDetail::class
//        ]);
    }

    /**
     * @inheritDoc
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping(array $data, ?InvoiceDetail $detail = null)
    {
        if (in_array(null, $data)) {
            return null;
        }

        $dto = InvoiceDetailDto::fromArray($data);
        if ($detail instanceof InvoiceDetail) {
            return $detail->update($dto);
        }

        return InvoiceDetail::make($dto);
    }
}
