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


use Britannia\Domain\Entity\Academy\Academy;
use Britannia\Domain\Entity\Academy\AcademyDto;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;

final class AcademyMapper extends AdminMapper
{
    protected function className(): string
    {
        return Academy::class;
    }

    protected function create(array $values): Academy
    {
        $dto = AcademyDto::fromArray($values);
        return Academy::make($dto);
    }

    /**
     * @param Academy $academy
     * @param array $values
     */
    protected function update($academy, array $values)
    {
        $dto = AcademyDto::fromArray($values);
        $academy->update($dto);
    }

}
