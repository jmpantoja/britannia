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
use Britannia\Domain\Entity\Student\TutorDto;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;

final class TutorMapper extends AdminMapper
{

    protected function className(): string
    {
        return Tutor::class;
    }

    protected function create(array $values): Tutor
    {
        $dto = TutorDto::fromArray($values);
        return Tutor::make($dto);
    }

    /**
     * @param Tutor $tutor
     * @param array $values
     * @return Tutor
     */
    protected function update($tutor, array $values): Tutor
    {
        $dto = TutorDto::fromArray($values);
        return $tutor->update($dto);
    }
}
