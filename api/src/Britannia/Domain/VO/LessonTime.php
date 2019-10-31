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

class LessonTime
{
    /**
     * @var \DateTime
     */
    private $startTime;

    /**
     * @var PositiveInteger
     */
    private $length;

    public static function make(\DateTime $startTime, PositiveInteger $length): self
    {
        return new self($startTime, $length);
    }

    private function __construct(\DateTime $startTime, PositiveInteger $length)
    {
        $this->setStartTime($startTime);
        $this->setLength($length);
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
     * @return LessonTime
     */
    private function setStartTime(\DateTime $startTime): LessonTime
    {
        $startTime = $startTime->setDate(0, 0, 0);
        $this->startTime = $startTime;
        return $this;
    }

    /**
     * @return PositiveInteger
     */
    public function getLength(): PositiveInteger
    {
        return $this->length;
    }

    /**
     * @param PositiveInteger $length
     * @return LessonTime
     */
    private function setLength(PositiveInteger $length): LessonTime
    {
        $this->length = $length;
        return $this;
    }


}
