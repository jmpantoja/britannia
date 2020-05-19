<?php

namespace Britannia\Infraestructure\Symfony\DataFixtures;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Domain\Entity\Book\Book;
use Britannia\Domain\Entity\Book\BookDto;
use Britannia\Domain\Entity\Level\Level;
use Britannia\Domain\Entity\SchoolCourse\SchoolCourse;
use Britannia\Domain\Entity\SchoolCourse\SchoolCourseDto;
use Britannia\Domain\VO\Course\Book\BookCategory;
use Britannia\Domain\VO\SchoolCourse\SchoolLevel;
use PlanB\DDD\Domain\VO\PositiveInteger;
use PlanB\DDD\Domain\VO\Price;

class DataExtraFixtures extends BaseFixture
{

    public function loadData(DataPersisterInterface $dataPersister): void
    {
//        $this->loadBooks($dataPersister);
//        $this->loadCourses($dataPersister);
    }


    /**
     * @param DataPersisterInterface $dataPersister
     */
    private function loadBooks(DataPersisterInterface $dataPersister): void
    {
        foreach (range(1, 10) as $index) {
            $price = Price::make($this->faker->numberBetween(20, 30));
            $pvp = $price->add(Price::make($this->faker->numberBetween(5, 10)));


            $dto = BookDto::fromArray([
                'name' => sprintf('Libro #%s', $index),
                'category' => $this->rand(BookCategory::values()),
                'price' => $price,
                'pvp' => $pvp
            ]);

            $dataPersister->persist(Book::make($dto));
        }
    }

    /**
     * @param DataPersisterInterface $dataPersister
     */
    private function loadCourses(DataPersisterInterface $dataPersister): void
    {
        foreach (range(1, 6) as $index) {
            $dto = SchoolCourseDto::fromArray([
                'course' => PositiveInteger::make($index),
                'level' => SchoolLevel::EPO(),
            ]);

            $dataPersister->persist(SchoolCourse::make($dto));
        }

        foreach (range(1, 4) as $index) {
            $dto = SchoolCourseDto::fromArray([
                'course' => PositiveInteger::make($index),
                'level' => SchoolLevel::ESO(),
            ]);

            $dataPersister->persist(SchoolCourse::make($dto));
        }


        foreach (range(1, 2) as $index) {
            $dto = SchoolCourseDto::fromArray([
                'course' => PositiveInteger::make($index),
                'level' => SchoolLevel::BACHILLERATO(),
            ]);

            $dataPersister->persist(SchoolCourse::make($dto));
        }

    }

    public function getBackupFiles(): array
    {
        return [];
    }

    private function rand(array $values)
    {
        $key = array_rand($values);
        return $values[$key];
    }

}
