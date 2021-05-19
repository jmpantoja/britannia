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

namespace Britannia\Infraestructure\Symfony\Admin\ClassRoom;


use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Symfony\Form\Type\PositiveIntegerType;
use Symfony\Component\Validator\Constraints\NotBlank;

final class ClassRoomForm extends AdminForm
{

    public function configure()
    {
        $this->tab('Nivel');

        $this->group('', ['class' => 'col-md-4'])
            ->add('name', null, [
                'label' => 'Nombre',
                'constraints' => [
                    new NotBlank()
                ]
            ])
            ->add('capacity', PositiveIntegerType::class, [
                'label' => 'Capacidad',
            ]);
    }
}
