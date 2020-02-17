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

namespace Britannia\Domain\Entity\Course\Course;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseAssessmentInterface;
use Britannia\Domain\Entity\Course\CourseDto;
use Britannia\Domain\Entity\Course\Traits\AssessmentTrait;
use Britannia\Domain\VO\Course\Assessment\Assessment;
use Doctrine\Common\Collections\ArrayCollection;

final class School extends Course implements CourseAssessmentInterface
{
    use AssessmentTrait;

    /**
     * @var null|string
     */
    private $schoolCourse;

    public function __construct(SchoolDto $dto)
    {
        $this->assessment = $dto->assessment;
        $this->terms = new ArrayCollection();

        parent::__construct($dto);
    }


    public function update(CourseDto $dto): School
    {
        parent::update($dto);

        $this->schoolCourse = $dto->schoolCourse;
        $this->updateAssessment($dto);

        return $this;
    }

    /**
     * @return string|null
     */
    public function schoolCourse(): ?string
    {
        return $this->schoolCourse;
    }

    public function assessment(): Assessment
    {
        return  $this->assessment ?? Assessment::defaultForShool();
    }
}
