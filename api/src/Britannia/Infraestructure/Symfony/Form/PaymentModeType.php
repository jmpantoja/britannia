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

namespace Britannia\Infraestructure\Symfony\Form;


use Britannia\Domain\VO\PaymentMode;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PaymentModeType extends AbstractSingleType
{

    public function getParent()
    {
        return ChoiceType::class;
    }

    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'choice_loader' => new CallbackChoiceLoader(function () {
                return array_flip(PaymentMode::getConstants());
            }),
            'choice_translation_domain' => false,
            'choice_translation_locale' => null,
            'multiple' => false,
            'expanded' => false
        ]);
    }

//    public function transform($value)
//    {
//        if (is_null($value)) {
//            return null;
//        }
//        return PaymentMode::get($value);
//    }

    /**
     * @return \Britannia\Infraestructure\Symfony\Validator\FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return null;
    }

    public function customMapping($data)
    {
        return PaymentMode::byName($data);
    }
}
