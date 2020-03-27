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

namespace Britannia\Infraestructure\Symfony\Importer\Etl\Message;


use Britannia\Domain\Entity\Message\Message;
use Britannia\Domain\Service\Message\DeliveryFactoryInterface;
use Britannia\Domain\Service\Message\DeliveryInterface;
use Carbon\CarbonImmutable;

final class DeliveryFactoryFake implements DeliveryFactoryInterface
{
    /**
     * @var CarbonImmutable
     */
    private CarbonImmutable $date;

    private $phones = [];


    public static function make(CarbonImmutable $date): self
    {
        return new self($date);
    }

    private function __construct(CarbonImmutable $date)
    {
        $this->date = $date;
    }

    public function setPhones($phones): self
    {
        $this->phones = $phones;
        return $this;
    }

    public function fromMessage(Message $message): DeliveryInterface
    {
        return DeliveryFake::make($this->date, $this->phones);
    }


}
