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

namespace Britannia\Infraestructure\Symfony\Admin\SchoolCourse;


use Britannia\Infraestructure\Symfony\Form\Type\SchoolCourse\SchoolLevelType;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Symfony\Form\Type\PositiveIntegerType;
use Symfony\Component\Validator\Constraints\NotBlank;

final class SchoolCourseForm extends AdminForm
{
    public function configure()
    {
        $this->tab('Curso Escolar');
        $this->group('', ['class' => 'col-md-4']);

        $this->add('course', PositiveIntegerType::class, [
            'constraints' => [
                new NotBlank()
            ]
        ]);

        $this->add('level', SchoolLevelType::class, [
            'constraints' => [
                new NotBlank()
            ]
        ]);

    }
}
