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

namespace Britannia\Application\UseCase\Cron;


use Britannia\Application\UseCase\Invoice\CreateInvoice;
use Britannia\Domain\Repository\StudentRepositoryInterface;
use League\Tactician\CommandBus;
use PlanB\DDD\Application\UseCase\UseCaseInterface;

final class UpdateInvoicesUseCase implements UseCaseInterface
{
    /**
     * @var StudentRepositoryInterface
     */
    private StudentRepositoryInterface $studentRepository;
    /**
     * @var CommandBus
     */
    private CommandBus $commandBus;

    /**
     * UpdateInvoicesUseCase constructor.
     */
    public function __construct(CommandBus $commandBus, StudentRepositoryInterface $studentRepository)
    {
        $this->studentRepository = $studentRepository;
        $this->commandBus = $commandBus;
    }

    public function handle(UpdateInvoices $command)
    {
        $date = $command->date();
        $students = $this->studentRepository->findActives();

        foreach ($students as $student) {
            $createInvoice = CreateInvoice::create($student, $date);
            $this->commandBus->handle($createInvoice);
        }
    }

}
