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

namespace PlanB\DDD\Domain\Model;


use PlanB\DDD\Domain\Event\DomainEvent;

abstract class AggregateRoot
{
    private $domainEvents = [];

    final public function pullEvents(): array
    {
        $domainEvents = $this->domainEvents;
        $this->domainEvents = [];

        return $domainEvents;
    }

    final protected function notify(DomainEvent $domainEvent): void
    {
        $this->domainEvents[] = $domainEvent;
    }

}
