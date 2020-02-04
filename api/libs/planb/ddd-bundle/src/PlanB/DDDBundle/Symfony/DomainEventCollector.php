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

namespace PlanB\DDDBundle\Symfony;

use PlanB\DDD\Domain\Event\DomainEvent;
use PlanB\DDD\Domain\Event\DomainEventCollectorInterface;
use PlanB\DDD\Domain\Model\AggregateRoot;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

class DomainEventCollector implements DomainEventCollectorInterface
{

    /**
     * @var DomainEvent[]
     */
    private $events = [];
    /**
     * @var EventDispatcherInterface
     */
    private $dispatcher;


    public function __construct(EventDispatcherInterface $dispatcher)
    {
        $this->dispatcher = $dispatcher;
    }


    public function notify(DomainEvent $event): self
    {
        $name = spl_object_hash($event);
        $this->events[$name] = $event;
        return $this;
    }

    public function notifyEntity(AggregateRoot $entity): self
    {
        $events = $entity->pullEvents();
        foreach ($events as $event) {
            $this->notify($event);
        }
        return $this;
    }

    public function dispatch(): self
    {
        $events = $this->events;
        $this->events = [];

        foreach ($events as $event) {
            $this->dispatcher->dispatch(get_class($event), $event);
        }

        if ($this->events) {
            $this->dispatch();
        }
        return $this;
    }

    public function isEmpty(): bool
    {
        return 0 === count($this->events);
    }


}
