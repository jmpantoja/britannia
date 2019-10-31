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

namespace Britannia\Infraestructure\Doctrine\DBAL\Type;


use Britannia\Domain\Entity\ClassRoom\ClassRoomId;
use Britannia\Domain\VO\DayOfWeek;
use Britannia\Domain\VO\LessonDefinition;
use Britannia\Domain\VO\LessonLength;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use PlanB\DDD\Domain\VO\PositiveInteger;

class LessonDefinitionListType extends Type
{

    /**
     * Gets the SQL declaration snippet for a field of this type.
     *
     * @param mixed[] $fieldDeclaration The field declaration.
     * @param AbstractPlatform $platform The currently used database platform.
     *
     * @return string
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return self::TEXT;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {

        $data = json_decode((string)$value, true);

        if (is_null($data)) {
            return [];
        }

        return array_map(function ($item) {

            $dayOfWeek = DayOfWeek::byName($item['dayOfWeek']);
            $hour = \DateTime::createFromFormat('U', (string)$item['hour']);

            $length = PositiveInteger::make($item['length']);


            $classRoom = new ClassRoomId($item['classroom']);

            return LessonDefinition::make(...[
                $dayOfWeek,
                $hour,
                $length,
                $classRoom,
            ]);
        }, array_values($data));
    }

    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $value = array_map(function (LessonDefinition $lessonDefinition) {
            return [
                'dayOfWeek' => $lessonDefinition->getDayOfWeek()->getName(),
                'hour' => $lessonDefinition->getStartTime()->getTimestamp(),
                'length' => $lessonDefinition->getLength()->getNumber(),
                'classroom' => (string)$lessonDefinition->getClassRoomId(),

            ];
        }, (array)$value);

        return json_encode(array_values($value));
    }


    /**
     * Gets the name of this type.
     *
     * @return string
     *
     * @todo Needed?
     */
    public function getName()
    {
        return 'LessonDefinitionList';
    }
}
