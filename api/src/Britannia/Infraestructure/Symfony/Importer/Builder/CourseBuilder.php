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
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Course\Age\Age;
use Britannia\Domain\VO\Course\Intensive\Intensive;
use Britannia\Domain\VO\Course\Periodicity\Periodicity;
use Britannia\Domain\VO\Course\Support\Support;
use Britannia\Domain\VO\Course\TimeTable\TimeTable;
use Britannia\Domain\VO\HoursPerWeek;
use Britannia\Infraestructure\Symfony\Importer\Builder\Traits\CourseMaker;
use Britannia\Infraestructure\Symfony\Importer\Maker\FullNameMaker;
use Britannia\Infraestructure\Symfony\Importer\Resume;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Price;
use stdClass;

class CourseBuilder extends BuilderAbstract
{
    use CourseMaker;

    private const TYPE = 'Curso';

    private $id;

    private $name;

    private $enrolmentPayment;

    private $monthlyPayment;

    private $age;

    private $intensive;

    private $hoursPerWeek;

    private $schoolCourse;

    private $periodicity;

    private $numOfPlaces;

    private $timeTable;

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

    public function withSchoolCourse(string $schoolCourse): self
    {
        $this->schoolCourse = $schoolCourse;
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
        if (in_array(1, $values, true)) {
            $this->age = Age::CHILD();
        }

        if (in_array(2, $values, true)) {
            $this->age = Age::ADULT();
        }

        if (in_array(13, $values, true)) {
            $this->intensive = Intensive::NOT_INTENSIVE();
        }

        if (in_array(14, $values, true)) {
            $this->intensive = Intensive::INTENSIVE();
        }

        return $this;
    }

    public function withPeriodicity(int $periodicity): self
    {

        if ($periodicity === 0) {
            $this->periodicity = Periodicity::LIMITED();
            return $this;
        }

        if ($periodicity === 1) {
            $this->periodicity = Periodicity::UNLIMITED();
            return $this;
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


        $schedule = $this->toLessons($field1, $classRoomId);
        if (empty($schedule)) {
            $schedule = $this->toLessons($field2, $classRoomId);
        }

        $this->timeTable = TimeTable::make($start, $end, $schedule);

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

        $classRoom = new ClassRoom();
        $classRoom->setName($name);
        $classRoom->setCapacity(PositiveInteger::make(10));

        $classRoom = $this->findOneOrCreate($classRoom, [
            'name' => $name
        ]);

        $classRoomId = $classRoom->getId();
        return $classRoomId;
    }

    public function build(): object
    {
        return new stdClass();

//        $course = new Course();
//        $course->setOldId($this->id);
//        $course->setName($this->name);
//        $course->setSchoolCourse($this->schoolCourse);
//        $course->setEnrolmentPayment($this->enrolmentPayment);
//        $course->setMonthlyPayment($this->monthlyPayment);
//
//        $course->setSupport(Support::REGULAR());
//
//        $course->setNumOfPlaces($this->numOfPlaces);
//        $course->setAge($this->age);
//        $course->setPeriodicity($this->periodicity);
//        $course->setIntensive($this->intensive);
//
//        $course->setTimeTable($this->timeTable);

//        return $course;
    }
}


/**
 *  Observaciones
 */
