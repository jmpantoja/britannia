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

namespace Britannia\Infraestructure\Symfony\EventSubscriber;


use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use Britannia\Domain\Entity\Record\RecordEventInterface;
use Britannia\Domain\Entity\Record\StudentHasJoinedToCourse;
use Britannia\Domain\Entity\Record\StudentHasLeavedCourse;
use League\Tactician\CommandBus;

class StudentSubscriber extends DomainEventSubscriber
{

    /**
     * @var DataPersisterInterface
     */
    private $persister;

    public function __construct(CommandBus $commandBus, DataPersisterInterface $persister)
    {
        parent::__construct($commandBus);
        $this->persister = $persister;
    }

    /**
     *
     * @return array The event names to listen to
     */
    public static function getSubscribedEvents()
    {
        return [
            StudentHasJoinedToCourse::class => 'onChangeStudentsCourse',
            StudentHasLeavedCourse::class => 'onChangeStudentsCourse'
        ];
    }

    public function onChangeStudentsCourse(RecordEventInterface $event)
    {
        $student = $event->getStudent();
        $student->onSave();
        $this->persister->persist($student);
    }

}
