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


use Britannia\Domain\VO\NumOfYears;
use Britannia\Domain\VO\Validator;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumOfYearsType extends AbstractSingleType
{
    public function getParent()
    {
        return ChoiceType::class;
    }


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'required' => false,
            'choice_loader' => new CallbackChoiceLoader(function () {
                $values = array_flip(NumOfYears::getConstants());
                return array_merge(['' => ''], $values);
            })
        ]);
    }

    /**
     * @param array $options
     * @return null|Constraint
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return new Validator\NumOfYears([
            'required' => $options['required'],
        ]);
    }

    public function customMapping($data)
    {
        return NumOfYears::byName($data);
    }
}
