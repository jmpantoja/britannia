<?php

namespace Britannia\Infraestructure\Symfony\DataFixtures;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Domain\Entity\Book\Book;
use Britannia\Domain\Entity\Level\Level;
use Britannia\Domain\VO\Course\Book\BookCategory;
use PlanB\DDD\Domain\VO\Price;

class DataExtraFixtures extends BaseFixture
{

    public function loadData(DataPersisterInterface $dataPersister): void
    {

        $this->createMany(Level::class, 8, function (Level $level, int $count) {
            $names = [
                'Prestarters',
                'Starters',
                'A1',
                'A2',
                'B1',
                'B2',
                'C1',
                'C2',
            ];

            $level->setName($names[$count]);
        });

        $this->createMany(Book::class, 8, function (Book $book, int $count) {

            $rand = rand(1, 10);
            $name = sprintf('Libro #%s', $count + 1);

            $category = $rand % 2 === 0 ? BookCategory::STUDENT_BOOK() : BookCategory::WORK_BOOK();

            $book->setName($name);
            $book->setCategory($category);
            $book->setPrice(Price::make(...[
                $this->faker->numberBetween(20, 30)
            ]));
        });

    }

    public function getBackupFiles(): array
    {
        return [];
    }
}
