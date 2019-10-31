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

namespace Britannia\Domain\VO;


use Britannia\Domain\Entity\ClassRoom\ClassRoomId;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;

class LessonDefinition
{
    use Validable;

    /**
     * @var DayOfWeek
     */
    private $dayOfWeek;
    /**
     * @var \DateTime
     */
    private $startTime;
    /**
     * @var \Date
     */
    private $length;
    /**
     * @var ClassRoomId
     */
    private $classRoomId;

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\LessonDefinition([
            'required' => $options['required'] ?? true
        ]);
    }

    public static function make(DayOfWeek $dayOfWeek, \DateTime $hour, PositiveInteger $length, ClassRoomId $classRoomId): self
    {

        return new self($dayOfWeek, $hour, $length, $classRoomId);
    }

    private function __construct(DayOfWeek $dayOfWeek, \DateTime $hour, PositiveInteger $length, ClassRoomId $classRoomId)
    {
        $this->setDayOfWeek($dayOfWeek);
        $this->setStartTime($hour);
        $this->setLength($length);
        $this->setClassRoom($classRoomId);
    }

    /**
     * @return DayOfWeek
     */
    public function getDayOfWeek(): DayOfWeek
    {
        return $this->dayOfWeek;
    }

    /**
     * @param DayOfWeek $dayOfWeek
     * @return LessonDefinition
     */
    private function setDayOfWeek(DayOfWeek $dayOfWeek): LessonDefinition
    {
        $this->dayOfWeek = $dayOfWeek;
        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }

    /**
     * @param \DateTime $startTime
     * @return LessonDefinition
     */
    private function setStartTime(\DateTime $startTime): LessonDefinition
    {
        $this->startTime = $startTime;
        return $this;
    }

    /**
     * @return LessonLength
     */
    public function getLength(): PositiveInteger
    {
        return $this->length;
    }

    /**
     * @param LessonLength $length
     * @return LessonDefinition
     */
    private function setLength(PositiveInteger $length): LessonDefinition
    {
        $this->length = $length;
        return $this;
    }

    /**
     * @return ClassRoomId
     */
    public function getClassRoomId(): ClassRoomId
    {
        return $this->classRoomId;
    }

    /**
     * @param ClassRoomId $classRoom
     * @return LessonDefinition
     */
    private function setClassRoom(ClassRoomId $classRoom): LessonDefinition
    {
        $this->classRoomId = $classRoom;
        return $this;
    }

}
