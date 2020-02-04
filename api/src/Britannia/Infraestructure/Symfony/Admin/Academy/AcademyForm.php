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

namespace Britannia\Infraestructure\Symfony\Admin\Academy;


use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use Symfony\Component\Validator\Constraints\NotBlank;

final class AcademyForm extends AdminForm
{

    public function configure()
    {
        $this->tab('Academia');
        $this->group('', ['class' => 'col-md-4']);

        $this->add('name', null, [
            'constraints' => [
                new NotBlank()
            ]
        ]);
    }
}
