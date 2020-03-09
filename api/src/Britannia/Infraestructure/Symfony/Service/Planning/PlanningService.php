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

namespace Britannia\Infraestructure\Symfony\Service\Planning;


use Britannia\Domain\Entity\ClassRoom\ClassRoom;
use Britannia\Domain\Repository\ClassRoomRepositoryInterface;
use Britannia\Domain\Repository\LessonRepositoryInterface;
use Carbon\CarbonImmutable;

class PlanningService
{
    /**
     * @var ClassRoomRepositoryInterface
     */
    private $classRoomRepository;
    /**
     * @var LessonRepositoryInterface
     */
    private $lessonRepository;

    public function __construct(ClassRoomRepositoryInterface $classRoomRepository,
                                LessonRepositoryInterface $lessonRepository)
    {

        $this->classRoomRepository = $classRoomRepository;
        $this->lessonRepository = $lessonRepository;
    }


    /**
     * @return ClassRoom[]
     */
    public function getClassRooms(): array
    {
        $classRooms = $this->classRoomRepository->findAll();

        $data = array_map(function (ClassRoom $classRoom) {
            return [
                'id' => (string)$classRoom->id(),
                'title' => (string)$classRoom->name()
            ];
        }, $classRooms);

        return $data;
    }

    public function getEvents(CarbonImmutable $date): array
    {
        $lessons = $this->lessonRepository->findByDay($date);

        $data = [];

        foreach ($lessons as $lesson) {
            $event = FullCalendarEvent::fromLesson($lesson);
            $data[] = $event->toArray();
        }

        return $data;

    }
}
