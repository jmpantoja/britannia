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

namespace Britannia\Domain\Entity\Message;


use Britannia\Domain\Entity\Student\Student;
use Britannia\Domain\Entity\Student\StudentList;
use PlanB\DDD\Domain\Model\EntityList;

final class ShipmentList extends EntityList
{

    protected function typeName(): string
    {
        return Shipment::class;
    }

    public static function make(Message $message, StudentList $students): self
    {
        $shipments = $students->values()
            ->map(fn(Student $student) => Shipment::make($message, $student));

        return static::collect($shipments);
    }


    public function onlyActives(): self
    {

        $shipments = $this->values()
            ->filter(fn(Shipment $shipment) => $shipment->isActive());

        return static::collect($shipments);
    }
}
