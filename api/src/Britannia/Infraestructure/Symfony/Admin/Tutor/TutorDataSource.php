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

namespace Britannia\Infraestructure\Symfony\Admin\Tutor;


use Britannia\Domain\Entity\Student\Tutor;
use Britannia\Domain\VO\Student\Job\Job;
use PlanB\DDDBundle\Sonata\Admin\AdminDataSource;

final class TutorDataSource extends AdminDataSource
{
    public function __invoke(Tutor $tutor)
    {
        $data['Nombre'] = $this->parse($tutor->fullName());
        $data['DNI'] = $this->parse($tutor->dni());
        $data['Dirección'] = $this->parse($tutor->address());
        $data['Emails'] = $this->parse($tutor->emails());
        $data['Teléfonos'] = $this->parse($tutor->phoneNumbers());
        $data['Profesión'] = $this->parseJobName($tutor->job());
        $data['Situación laboral'] = $this->parseJobStatus($tutor->job());
        return $data;
    }

    private function parseJobName(?Job $job)
    {
        if (!($job instanceof Job)) {
            return '';
        }

        return $this->parse($job->getName());
    }

    private function parseJobStatus(?Job $job)
    {
        if (!($job instanceof Job)) {
            return '';
        }

        return $this->parse($job->getStatus());
    }
}
