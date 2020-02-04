<?php


namespace Britannia\Domain\Entity\Staff;


use PlanB\DDD\Domain\Model\EntityList;
use PlanB\DDD\Domain\Model\EntityListInterface;

final class StaffList extends EntityList
{
    protected function typeName(): string
    {
        return StaffMember::class;
    }
}
