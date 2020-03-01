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

namespace Britannia\Application\UseCase\Lesson;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Domain\Entity\Record\Record;
use Britannia\Domain\Entity\Record\TypeOfRecord;
use Britannia\Domain\Repository\RecordRepositoryInterface;
use Doctrine\ORM\EntityManagerInterface;
use PlanB\DDD\Application\UseCase\UseCaseInterface;

final class LessonHasBeenAttendedUseCase implements UseCaseInterface
{
    /**
     * @var RecordRepositoryInterface
     */
    private RecordRepositoryInterface $recordRepository;

    public function __construct(RecordRepositoryInterface $recordRepository)
    {

        $this->recordRepository = $recordRepository;
    }


    public function handle(LessonHasBeenAttended $command)
    {
        $attendance = $command->attendance();

        $this->recordRepository->deleteAttendance($attendance);
    }
}
