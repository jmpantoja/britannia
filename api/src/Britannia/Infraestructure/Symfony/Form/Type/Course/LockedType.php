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


use Britannia\Domain\VO\Course\Locked\Locked;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LockedType extends AbstractSingleType
{

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return ChoiceType::class;
    }

    public function customOptions(OptionsResolver $resolver)
    {

        $resolver->setDefaults([
            'label' => 'Bloqueo',
            'required' => true,
            'choice_loader' => new CallbackChoiceLoader(function () {
                $values = array_flip(Locked::getConstants());
                return $values;
            }),
            'attr' => array('style' => 'max-width: 200px;')
        ]);
    }

    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return new \Britannia\Domain\VO\Course\Locked\Validator\Locked([
            'required' => $options['required']
        ]);
    }

    public function customMapping($data)
    {
        return Locked::byName($data);
    }
}
