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

namespace Britannia\Infraestructure\Symfony\EventSubscriber;


use League\Tactician\CommandBus;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

abstract class DomainEventSubscriber implements EventSubscriberInterface
{

    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(CommandBus $commandBus)
    {
        $this->commandBus = $commandBus;
    }

    /**
     * @param $command
     */
    protected function handle($command): void
    {
        $this->commandBus->handle($command);
    }
}
