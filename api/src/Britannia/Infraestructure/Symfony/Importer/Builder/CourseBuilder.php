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

namespace Britannia\Infraestructure\Symfony\Importer\Builder;


use Britannia\Domain\Entity\ClassRoom\ClassRoom;
use Britannia\Domain\Entity\ClassRoom\ClassRoomDto;
use Britannia\Domain\Entity\Course\Course\Adult;
use Britannia\Domain\Entity\Course\Course\AdultDto;
use Britannia\Domain\Entity\Course\Course\OneToOne;
use Britannia\Domain\Entity\Course\Course\OneToOneDto;
use Britannia\Domain\Entity\Course\Course\PreSchool;
use Britannia\Domain\Entity\Course\Course\PreSchoolDto;
use Britannia\Domain\Entity\Course\Course\School;
use Britannia\Domain\Entity\Course\Course\SchoolDto;
use Britannia\Domain\Entity\Course\Course\Support;
use Britannia\Domain\Entity\Course\Course\SupportDto;
use Britannia\Domain\Service\Assessment\AssessmentGenerator;
use Britannia\Domain\Service\Course\LessonGenerator;
use Britannia\Domain\VO\Course\Assessment\Assessment;
use Britannia\Domain\VO\Course\Intensive\Intensive;
use Britannia\Domain\VO\Course\TimeRange\TimeRange;
use Britannia\Domain\VO\Course\TimeTable\Schedule;
use Britannia\Domain\VO\Course\TimeTable\TimeTable;
use Britannia\Infraestructure\Symfony\Importer\Builder\Traits\CourseMaker;
use Britannia\Infraestructure\Symfony\Importer\Maker\FullNameMaker;
use Britannia\Infraestructure\Symfony\Importer\Resume;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Price;


class CourseBuilder extends BuilderAbstract
{
    use CourseMaker;

    private const TYPE = 'Curso';

    private $id;

    private $name;

    private $description;

    private $enrolmentPayment;

    private $monthlyPayment;

    private $intensive;

    private $hoursPerWeek;

    private $schoolCourses = [];

    private $periodicity;

    private $numOfPlaces;

    private $timeTable;

    /**
     * @var LessonGenerator
     */
    private LessonGenerator $lessonGenerator;
    /**
     * @var AssessmentGenerator
     */
    private AssessmentGenerator $assessmentGenerator;
    /**
     * @var bool
     */
    private bool $isAdult;


    public function initResume(array $input): Resume
    {
        return Resume::make((int)$input['id'], self::TYPE, $input['nombre']);
    }

    public function withId($id): self
    {
        $this->id = $id * 1;
        return $this;
    }

    public function withName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function withDescription(string $schoolCourse): self
    {
        $patterns = [
            'EPO' => range(1, 6),
            'ESO' => range(1, 4),
            'BACH' => range(1, 2)
        ];

        foreach ($patterns as $level => $courses) {
            foreach ($courses as $course) {
                $pattern = sprintf('/(%sº).*%s/', $course, $level);
                if (preg_match($pattern, $schoolCourse)) {
                    $this->schoolCourses[] = sprintf('%s_%s', $level, $course);
                }
            }
        }

        $this->description = $schoolCourse;
        return $this;
    }

    public function withEnrolmentPayment(float $price): self
    {
        $this->enrolmentPayment = Price::make($price);
        return $this;
    }

    public function withMonthlyPayment(float $price): self
    {
        $this->monthlyPayment = Price::make($price);
        return $this;
    }

    public function withCategories(array $values): self
    {
        if (in_array(13, $values, true)) {
            $this->intensive = Intensive::NOT_INTENSIVE();
        }

        if (in_array(14, $values, true)) {
            $this->intensive = Intensive::INTENSIVE();
        }

        $this->isAdult = false;
        if (in_array(1, $values, true)) {
            $this->isAdult = true;
        }

        return $this;
    }

    public function withNumOfPlaces(int $places): self
    {
        $this->numOfPlaces = PositiveInteger::make($places);
        return $this;
    }

    public function withTimeTable(string $startDate, string $endDate, string $field1, string $field2, string $classRoomNumber): self
    {

        $classRoomId = $this->getClassRoomId($classRoomNumber);

        $start = CarbonImmutable::make($startDate);
        $end = CarbonImmutable::make($endDate);

        $timeRange = TimeRange::make($start, $end);


        $schedule = $this->toLessons($field1, $classRoomId);
        if (empty($schedule)) {
            $schedule = $this->toLessons($field2, $classRoomId);
        }

        $schedule = Schedule::fromArray($schedule);

        $this->timeTable = TimeTable::make($timeRange, $schedule);

        return $this;
    }

    /**
     * @param string $classRoomNumber
     * @return mixed
     */
    protected function getClassRoomId(string $classRoomNumber)
    {
        $classRoomNumber = (int)$classRoomNumber;
        $name = sprintf('Aula #%s', $classRoomNumber);

        $dto = ClassRoomDto::fromArray([
            'name' => $name,
            'capacity' => PositiveInteger::make(10)
        ]);

        $classRoom = ClassRoom::make($dto);
        $classRoom = $this->findOneOrCreate($classRoom, [
            'name' => $name
        ]);

        return $classRoom->id();
    }


    public function withGenerator(LessonGenerator $lessonGenerator, AssessmentGenerator $unitGenerator): self
    {
        $this->lessonGenerator = $lessonGenerator;
        $this->assessmentGenerator = $unitGenerator;

        return $this;
    }

    public function build(): ?object
    {
        $input = [
            'oldId' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'enrollmentPayment' => $this->enrolmentPayment,
            'monthlyPayment' => $this->monthlyPayment,
            'numOfPlaces' => $this->numOfPlaces,
            'intensive' => $this->intensive,
            'timeTable' => $this->timeTable,
            'lessonCreator' => $this->lessonGenerator,
            'assessmentGenerator' => $this->assessmentGenerator,
            'schoolCourses' => $this->schoolCourses,
        ];

        $name = strtoupper($this->name);

        if (strpos($name, 'KIDS') !== false) {

            return $this->buildPreSchool($input);
        }

        if (strpos($name, 'ONE TO ONE') !== false) {
            return $this->buildOneToOne($input);
        }

        if (strpos($name, 'APOYO') !== false) {

            return $this->buildSupport($input);
        }

        if ($this->isAdult === true) {

            return $this->buildAdult($input);
        }

        return $this->buildSchool($input);
    }

    private function buildPreSchool(array $input)
    {
        $input['assessment'] = Assessment::defaultForShool();
        $dto = PreSchoolDto::fromArray($input);
        return PreSchool::make($dto);
    }

    private function buildSchool(array $input)
    {
        $input['assessment'] = Assessment::defaultForShool();

        $dto = SchoolDto::fromArray($input);

        return School::make($dto);
    }

    private function buildOneToOne(array $input)
    {
        $dto = OneToOneDto::fromArray($input);
        $dto->timeRange = $dto->timeTable->range();

        return OneToOne::make($dto);
    }

    private function buildSupport(array $input)
    {
        $dto = SupportDto::fromArray($input);
        return Support::make($dto);
    }

    /**
     * @param array $input
     * @return Adult
     */
    private function buildAdult(array $input): Adult
    {
        $input['assessment'] = Assessment::defaultForAdults();
        $dto = AdultDto::fromArray($input);
        return Adult::make($dto);
    }
}


