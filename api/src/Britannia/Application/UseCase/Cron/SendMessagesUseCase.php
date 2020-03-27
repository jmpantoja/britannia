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

namespace Britannia\Application\UseCase\Cron;


use Britannia\Domain\Service\Message\Deliverer;
use Britannia\Domain\Service\Message\DeliveryFactory;
use Britannia\Infraestructure\Doctrine\Repository\MessageRepository;
use Carbon\CarbonImmutable;
use PlanB\DDD\Application\UseCase\UseCaseInterface;

final class SendMessagesUseCase implements UseCaseInterface
{
    /**
     * @var MessageRepository
     */
    private MessageRepository $messageRepository;
    /**
     * @var DeliveryFactory
     */
    private DeliveryFactory $deliveryFactory;

    /**
     * SendMessagesUseCase constructor.
     * @param MessageRepository $messageRepository
     * @param DeliveryFactory $deliveryFactory
     */
    public function __construct(MessageRepository $messageRepository, DeliveryFactory $deliveryFactory)
    {
        $this->messageRepository = $messageRepository;

        $this->deliveryFactory = $deliveryFactory;
    }

    public function handle(SendMessages $command)
    {
        $now = CarbonImmutable::now();
        $messages = $this->messageRepository->pendingShipping($now);


        foreach ($messages as $message) {

            $message->send($this->deliveryFactory);
        }
    }
}
