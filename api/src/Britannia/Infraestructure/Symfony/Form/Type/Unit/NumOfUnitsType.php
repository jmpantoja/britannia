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

namespace Britannia\Infraestructure\Symfony\Form\Type\Unit;


use Britannia\Domain\VO\Mark\NumOfUnits;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class NumOfUnitsType extends AbstractSingleType
{
    public function getParent()
    {
        return ChoiceType::class;
    }


    public function customOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'expanded' => false,
            'choice_loader' => new CallbackChoiceLoader(function () {
                return array_flip(NumOfUnits::getConstants());
            }),
          //  'data' => null,
            'required' => false,
            'label' => 'nº de unidades',
        ]);

        $resolver->setRequired('completed');
        $resolver->setAllowedTypes('completed', ['int']);

        $resolver->setNormalizer('data', function (OptionsResolver $resolver) {
            dump($resolver['completed']);
            return NumOfUnits::TWO();
        });
    }

    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return new \Britannia\Domain\VO\Mark\Validator\NumOfUnits([
            'required' => $options['required']
        ]);
    }

    public function customMapping($data)
    {
        return NumOfUnits::byName($data);
    }
}
