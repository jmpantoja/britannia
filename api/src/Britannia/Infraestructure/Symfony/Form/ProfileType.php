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


use PlanB\DDDBundle\Symfony\Form\Type\FullNameType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormBuilderInterface;

class ProfileType extends AbstractType
{
    public function getParent()
    {
        return FormType::class;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('fullName', FullNameType::class);
    }

}
