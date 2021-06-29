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

namespace Britannia\Domain\Entity\Setting;


use PlanB\DDD\Domain\Behaviour\Comparable;

final class SettingId implements Comparable
{
    const ID = 1;
    private int $id;

    public function __construct()
    {
        $this->id = self::ID;
    }

    public function compareTo(object $other): int
    {
        if ($this->equals($other)) {
            return 0;
        }
        return strcmp((string)$this->id, (string)$other->id);
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->id();
    }

    public function equals(Comparable $other): bool
    {
        return $this->id === $other->id;
    }
}
