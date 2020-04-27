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

namespace Britannia\Infraestructure\Symfony\Form\Type\Staff;


use Britannia\Domain\VO\StaffMember\Status;
use Britannia\Domain\VO\Validator;
use Britannia\Infraestructure\Symfony\Validator\FullName;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDDBundle\Symfony\Form\Type\AbstractSingleType;
use Symfony\Component\Form\ChoiceList\Loader\CallbackChoiceLoader;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatusType extends AbstractSingleType
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
            'required' => true,
            'choice_loader' => new CallbackChoiceLoader(function () {
                return array_flip(Status::getConstants());
            }),
            'attr' => [
                'style' => 'width:170px'
            ]
        ]);
    }

    /**
     * @return FullName
     */
    public function buildConstraint(array $options): ?Constraint
    {
        return new \Britannia\Domain\VO\StaffMember\Validator\Status([
            'required' => $options['required']
        ]);
    }

    public function customMapping($data)
    {
        return Status::byName($data);
    }
}
