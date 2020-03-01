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
//        $this->add('attachment_upload', 'attachment/upload');
//        $this->add('attachment_download', 'attachment/download/{path_to_file}', ['path_to_file' => ''], ['path_to_file' => '.+']);
//
//        $this->add('photo_upload', 'photo/upload');
//        $this->add('photo_view', 'photo/view/{path_to_file}', ['path_to_file' => ''], ['path_to_file' => '.+']);
    }
}
