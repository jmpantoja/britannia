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

namespace PlanB\DDD\Domain\Event;


use PlanB\DDD\Domain\Model\AggregateRoot;

interface DomainEventCollectorInterface
{

    public function notifyEntity(AggregateRoot $entity);

    public function notify(DomainEvent $event);

    public function dispatch();

    public function isEmpty(): bool;
}
