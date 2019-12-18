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

namespace Britannia\Domain\VO\Course\TimeTable;


use Britannia\Domain\Entity\ClassRoom\ClassRoomId;
use Britannia\Domain\VO\Course\TimeTable\Validator;

use Carbon\CarbonImmutable;
use DateInterval;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Traits\Validable;

use PlanB\DDD\Domain\VO\Validator\Constraint;
use Serializable;

class TimeSheet implements Serializable
{
    use Validable;

    /**
     * @var DayOfWeek
     */
    private $dayOfWeek;

    /**
     * @var CarbonImmutable
     */
    private $start;

    /**
     * @var CarbonImmutable
     */
    private $end;

    /**
     * @var PositiveInteger
     */
    private $length;
    /**
     * @var ClassRoomId
     */
    private $classRoomId;

    private function __construct(DayOfWeek $dayOfWeek, CarbonImmutable $start, CarbonImmutable $end, ClassRoomId $classRoomId)
    {
        $this->dayOfWeek = $dayOfWeek;
        $this->start = $start;
        $this->end = $end;
        $this->length = $end->diffInMinutes($start);
        $this->classRoomId = $classRoomId;
    }

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\TimeSheet([
            'required' => $options['required'] ?? true
        ]);
    }

    public static function make(DayOfWeek $dayOfWeek, CarbonImmutable $start, CarbonImmutable $end, ClassRoomId $classRoomId): self
    {
        $values = self::assert([
            'dayOfWeek' => $dayOfWeek,
            'start' => $start,
            'end' => $end,
            'classroomId' => $classRoomId

        ]);

        return new self(...[
            $values['dayOfWeek'],
            $values['start'],
            $values['end'],
            $values['classroomId'],
        ]);
    }

    /**
     * @return DayOfWeek
     */
    public function dayOfWeek(): DayOfWeek
    {
        return $this->dayOfWeek;
    }

    /**
     * @return CarbonImmutable
     */
    public function start(): CarbonImmutable
    {
        return $this->start;
    }

    /**
     * @return CarbonImmutable
     */
    public function end(): CarbonImmutable
    {
        return $this->end;
    }


    /**
     * @return int
     */
    public function length(): int
    {
        return $this->end->diffInMinutes($this->start);
    }

    /**
     * @return ClassRoomId
     */
    public function classRoomId(): ClassRoomId
    {
        return $this->classRoomId;
    }

    /**
     * String representation of object
     * @link http://php.net/manual/en/serializable.serialize.php
     * @return string the string representation of the object or null
     * @since 5.1.0
     */
    public function serialize()
    {
        return serialize(array(
            $this->dayOfWeek->getName(),
            $this->start,
            $this->end,
            $this->classRoomId
        ));
    }

    /**
     * Constructs the object
     * @link http://php.net/manual/en/serializable.unserialize.php
     * @param string $serialized <p>
     * The string representation of the object.
     * </p>
     * @return void
     * @since 5.1.0
     */
    public function unserialize($serialized)
    {
        $options = [
            'allowed_classes' => [
                ClassRoomId::class,
                CarbonImmutable::class]
        ];

        list (
            $dayOfWeek,
            $this->start,
            $this->end,
            $this->classRoomId
            ) = unserialize($serialized, $options);

        $this->dayOfWeek = DayOfWeek::byName($dayOfWeek);
    }
}
