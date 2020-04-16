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

namespace Britannia\Domain\VO\Course\Pass;


use PlanB\DDD\Domain\VO\Price;

final class PassPriceList
{
    /** @var Price[] */
    private $data = [];

    private function __construct(array $data)
    {
        $data = array_change_key_case($data, CASE_LOWER);

        $this->data['ten_hours'] = $data['ten_hours'] ?? null;
        $this->data['five_hours'] = $data['five_hours'] ?? null;
        $this->data['one_hour'] = $data['one_hour'] ?? null;
    }

    public static function make(array $data = []): self
    {
        return new self($data);
    }

    public function getByPassHours(PassHours $passHours): ?Price
    {
        $name = $passHours->getName();
        $key = strtolower($name);

        return $this->data[$key] ?? null;
    }

    public function tenHours(): Price
    {
        return $this->getByPassHours(PassHours::TEN_HOURS());
    }

    public function fiveHours(): Price
    {
        return $this->getByPassHours(PassHours::FIVE_HOURS());
    }

    public function oneHour(): Price
    {
        return $this->getByPassHours(PassHours::ONE_HOUR());
    }
}
