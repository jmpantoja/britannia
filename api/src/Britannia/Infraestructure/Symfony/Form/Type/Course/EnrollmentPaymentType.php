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


use PlanB\DDD\Domain\VO\Price;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use PlanB\DDDBundle\Symfony\Form\Type\PriceType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EnrollmentPaymentType extends AbstractSingleType
{
    /**
     * @var Price
     */
    private Price $enrollmentPrice;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $enrollmentPrice = $parameterBag->get('enrollment_price');
        $this->enrollmentPrice = Price::make($enrollmentPrice);
    }

    public function getParent()
    {
        return PriceType::class;
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'empty_data' => $this->enrollmentPrice,
            'show_unlock' => true,
            'attr' => [
                'readonly' => true,
                'style' => 'width:180px'
            ]
        ]);

        $resolver->setRequired(['show_unlock'])
            ->setAllowedTypes('show_unlock', ['bool']);

        $resolver->setNormalizer('attr', function (OptionsResolver $resolver) {
            return [
                'readonly' => $resolver['show_unlock'],
                'style' => 'width:180px'
            ];
        });
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['show_unlock'] = $options['show_unlock'];

        return parent::finishView($view, $form, $options);
    }


    /**
     * @inheritDoc
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($data)
    {
        return $data;
    }
}
