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

namespace Britannia\Infraestructure\Symfony\Admin\Record;


use Britannia\Domain\Entity\Record\Record;
use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;

final class RecordDataSource extends AdminDataSource
{
    public function __invoke(Record $record)
    {
        $data['Tipo'] = $this->parse($record->getType());
        $data['Alumno'] = $this->parse($record->getStudent());
        $data['Curso'] = $this->parse($record->getCourse());
        $data['Fecha'] = $this->parse($record->getDate());
        $data['Descripción'] = $this->parse($record->getDescription());

        return $data;
    }

}
