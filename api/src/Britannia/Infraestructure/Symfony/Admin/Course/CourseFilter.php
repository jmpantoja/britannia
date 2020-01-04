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

namespace Britannia\Infraestructure\Symfony\Admin\Course;


use Britannia\Domain\VO\Course\CourseStatus;
use PlanB\DDDBundle\Sonata\Admin\AdminFilter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class CourseFilter extends AdminFilter
{
    public function configure()
    {
        $this->add('name', null, [
            'advanced_filter' => false,
            'show_filter' => true
        ]);

        $this->add('status', 'doctrine_orm_string', [
            'show_filter' => true,
            'advanced_filter' => false,
            'field_type' => ChoiceType::class,
            'field_options' => [
                'label' => 'Estado',
                'choices' => array_flip(CourseStatus::getConstants()),
                'placeholder' => 'Todos'
            ],
        ]);
    }
}
