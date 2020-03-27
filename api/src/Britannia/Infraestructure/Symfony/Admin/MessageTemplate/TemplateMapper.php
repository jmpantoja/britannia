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

namespace Britannia\Infraestructure\Symfony\Admin\MessageTemplate;


use Britannia\Domain\Entity\Message\Template;
use Britannia\Domain\Entity\Message\TemplateDto;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;

final class TemplateMapper extends AdminMapper
{

    protected function className(): string
    {
        return Template::class;
    }

    protected function create(array $values): object
    {
        $dto = $this->makeDto($values);

        return Template::make($dto);
    }

    protected function update($object, array $values): object
    {
        $dto = $this->makeDto($values);
        return  $object->update($dto);
    }

    private function makeDto(array $values): TemplateDto
    {
        return TemplateDto::fromArray($values);
    }
}
