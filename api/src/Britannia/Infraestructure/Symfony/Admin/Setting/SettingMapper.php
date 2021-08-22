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
use Carbon\CarbonImmutable;
use DateTimeInterface;
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

    protected function obj2array($object)
    {
        $data = parent::obj2array($object);
        if (empty($data)) {
            return $data;
        }

        $data['morning'] = $this->toRange($data['morning'] ?? null);
        $data['afternoon'] = $this->toRange($data['afternoon'] ?? null);

        return $data;
    }

    private function toRange($value)
    {
        return [
            'start' => $this->toDate($value['start']),
            'end' => $this->toDate($value['end']),
        ];
    }

    private function toDate($date): DateTimeInterface
    {
        if (is_array($date)) {
            $date = $date['date'] ?? null;
        }

        if (!($date instanceof DateTimeInterface)) {
            $date = CarbonImmutable::make($date);
        }

        return $date;
    }
}
