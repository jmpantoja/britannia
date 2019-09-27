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

namespace PlanB\DDDBundle\ApiPlattform;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use PlanB\DDD\Domain\Event\DomainEventCollectorInterface;
use PlanB\DDD\Domain\Model\AggregateRoot;

class DataPersister implements DataPersisterInterface
{

    /**
     * @var DomainEventCollectorInterface
     */
    private $collector;

    public function __construct(DomainEventCollectorInterface $collector)
    {
        $this->collector = $collector;
    }

    /**
     * Is the data supported by the persister?
     */
    public function supports($data): bool
    {

        if(!($data instanceof AggregateRoot)){
            return false;
        }
        $this->collector->notifyEntity($data);
        return !$this->collector->isEmpty();
    }

    /**
     * Persists the data.
     *
     *
     * @param AggregateRoot $data
     * @return object|void Void will not be supported in API Platform 3, an object should always be returned
     */
    public function persist($data)
    {
        $this->collector->dispatch();

    }

    /**
     * Removes the data.
     */
    public function remove($data)
    {
        $this->collector->dispatch();
    }
}
