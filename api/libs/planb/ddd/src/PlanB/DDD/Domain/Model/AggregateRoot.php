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
use PlanB\DDD\Domain\Event\EventDispatcher;

abstract class AggregateRoot
{
    final protected function notify(DomainEvent $domainEvent): void
    {
        EventDispatcher::getInstance()
            ->dispatch($domainEvent);
    }

}
