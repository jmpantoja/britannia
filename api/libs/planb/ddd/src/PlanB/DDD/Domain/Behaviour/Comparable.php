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

namespace PlanB\DDD\Domain\Behaviour;


interface Comparable
{
    public function compareTo(Comparable $other): int;

    public function equals(Comparable $other): bool;
}
