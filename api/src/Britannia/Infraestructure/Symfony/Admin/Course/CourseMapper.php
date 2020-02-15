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

namespace Britannia\Infraestructure\Symfony\Admin\Course;


use Britannia\Domain\Entity\Course\Adult;
use Britannia\Domain\Entity\Course\AdultDto;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseDto;
use Britannia\Domain\Entity\Course\PreSchool;
use Britannia\Domain\Entity\Course\PreSchoolDto;
use Britannia\Domain\Entity\Course\School;
use Britannia\Domain\Entity\Course\SchoolDto;
use Britannia\Domain\Entity\Course\Support;
use Britannia\Domain\Entity\Course\SupportDto;
use Britannia\Domain\Service\Assessment\AssessmentGenerator;
use Britannia\Domain\Service\Course\LessonGenerator;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;

final class CourseMapper extends AdminMapper
{
    /**
     * @var LessonGenerator
     */
    private LessonGenerator $lessonCreator;
    /**
     * @var AssessmentGenerator
     */
    private AssessmentGenerator $assessmentGenerator;
    /**
     * @var Course
     */
    private Course $subject;

    public function __construct(LessonGenerator $lessonCreator, AssessmentGenerator $assessmentGenerator)
    {
        parent::__construct();
        $this->lessonCreator = $lessonCreator;
        $this->assessmentGenerator = $assessmentGenerator;
    }

    public function className(): string
    {
        return Course::class;
    }

    public function setSubject(Course $subject): self
    {
        $this->subject = $subject;
        return $this;
    }

    protected function create(array $values): Course
    {
        $dto = $this->makeDto($values);

        if ($dto instanceof PreSchoolDto) {
            return PreSchool::make($dto);
        } elseif ($dto instanceof SchoolDto) {
            return School::make($dto);
        }
        elseif ($dto instanceof SupportDto){
            return Support::make($dto);
        }
        return Adult::make($dto);
    }

    /**
     * @param Course $course
     * @param array $values
     * @return Course
     */
    protected function update($course, array $values): Course
    {
        $dto = $this->makeDto($values);
        return $course->update($dto);
    }

    /**
     * @param array $values
     * @return CourseDto
     */
    protected function makeDto(array $values): CourseDto
    {
        $values['lessonCreator'] = $this->lessonCreator;
        $values['assessmentGenerator'] = $this->assessmentGenerator;

        if ($this->subject instanceof Adult) {
            return AdultDto::fromArray($values);
        }

        if ($this->subject instanceof School) {
            return SchoolDto::fromArray($values);
        }

        if ($this->subject instanceof PreSchool) {
            return PreSchoolDto::fromArray($values);
        }

        if ($this->subject instanceof Support) {
            return SupportDto::fromArray($values);
        }
    }

}
