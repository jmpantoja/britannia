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

namespace Britannia\Infraestructure\Doctrine\DBAL\Type\Assessment;


use Britannia\Domain\VO\Assessment\SetOfSkills;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use PlanB\DDD\Domain\Enum\Enum;
use PlanB\DDDBundle\Doctrine\DBAL\Types\EnumType;

class SetOfSkillsType extends EnumType
{

    /**
     * Gets the name of this type.
     *
     * @return string
     */
    public function getName()
    {
        return 'SetOfSkills';
    }

    function byName(string $value, AbstractPlatform $platform): Enum
    {
        return SetOfSkills::byName($value);
    }
}
