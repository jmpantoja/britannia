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

namespace Britannia\Infraestructure\Symfony\Admin\Notification;


use Britannia\Domain\Entity\Notification\Notification;
use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;

final class NotificationDataSource extends AdminDataSource
{
    public function __invoke(Notification $record)
    {
        $data['Tipo'] = $this->parse($record->getType());
        $data['Alumno'] = $this->parse($record->getStudent());
        $data['Curso'] = $this->parse($record->getCourse());
        $data['Fecha'] = $this->parse($record->getDate());
        $data['Descripción'] = $this->parse($record->getDescription());

        return $data;
    }

}
