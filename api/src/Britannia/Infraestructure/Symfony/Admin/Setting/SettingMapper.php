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

namespace Britannia\Infraestructure\Symfony\Admin\Setting;


use Britannia\Domain\Entity\Setting\Setting;
use Britannia\Domain\Entity\Setting\SettingDto;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;

final class SettingMapper extends AdminMapper
{

    protected function className(): string
    {
        return Setting::class;
    }

    protected function create(array $values): Setting
    {
        $dto = $this->makeDto($values);
        return Setting::make($dto);
    }

    /**
     * @param Setting $object
     * @param array $values
     * @return Setting
     */
    protected function update($object, array $values): Setting
    {
        $dto = $this->makeDto($values);

        return $object->update($dto);
    }

    private function makeDto(array $values): SettingDto
    {
        return SettingDto::fromArray($values);
    }
}
