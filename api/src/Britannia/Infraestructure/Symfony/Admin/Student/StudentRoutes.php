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

namespace Britannia\Infraestructure\Symfony\Admin\Student;


use PlanB\DDDBundle\Sonata\Admin\AdminRoutes;

final class StudentRoutes extends AdminRoutes
{
    protected function configure(): void
    {
        $this->add('tutor_form', 'tutor/form');
        $this->add('student_cell', 'student/cell');

//        $this
//            ->add('dummy',
//                'dummy/{id}',
//                array('_controller' => 'AcmeDemoBundle:Default:dummy'),
//                array('id' => '\d+')
//            )
//        ;
    }
}
