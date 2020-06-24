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

namespace Britannia\Domain\Entity\Course\Traits;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\Course\Adult;
use Britannia\Domain\Entity\Course\Course\OneToOne;
use Britannia\Domain\Entity\Course\Course\PreSchool;
use Britannia\Domain\Entity\Course\Course\School;
use Britannia\Domain\Entity\Course\Course\Support;
use Britannia\Domain\Entity\Course\CourseDto;
use Britannia\Domain\Entity\Course\CourseId;
use PlanB\DDD\Domain\VO\RGBA;

trait CourseTrait
{

    /**
     * @var int
     */
    private $oldId;

    /**
     * @var CourseId
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /** @var string */
    private $description;

    /**
     * @var null|RGBA
     */
    private $color;

    protected function updateCourse(CourseDto $dto)
    {
        $this->name = $dto->name;
        $this->description = $dto->description;
        $this->setColor($dto->color);

        if (isset($dto->oldId)) {
            $this->oldId = $dto->oldId;
        }
    }


    /**
     * @return string
     */
    public function name(): string
    {

        return $this->name ?? (string)$this->id();
    }

    /**
     * @return string
     */
    public function description(): ?string
    {
        return $this->description;
    }


    private function setColor(RGBA $color): Course
    {
        if (is_null($this->color)) {
            $this->color = $color;
        }

        return $this;
    }

    /**
     * @return RGBA|null
     */
    public function color(): RGBA
    {
        return $this->color ?? RGBA::make(100, 0, 100);
    }

    public function isAdult(): bool
    {
        return static::class === Adult::class;
    }

    public function isSchool(): bool
    {
        return static::class === School::class;
    }

    public function isPreSchool(): bool
    {
        return static::class === PreSchool::class;
    }

    public function isSupport(): bool
    {
        return static::class === Support::class;
    }

    public function isOnetoOne(): bool
    {
        return static::class === OneToOne::class;
    }


    public function __toString()
    {
        return $this->name();
    }


}
