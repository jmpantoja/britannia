<?php

namespace Britannia\Infraestructure\Symfony\Command;

use Britannia\Application\UseCase\Cron\UpdateCalendar;
use Britannia\Application\UseCase\Cron\UpdateCourseStatus;
use Britannia\Application\UseCase\Cron\UpdateStudentAge;
use League\Tactician\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BritanniaCronCommand extends Command
{
    protected static $defaultName = 'britannia:cron';
    /**
     * @var CommandBus
     */
    private $commandBus;

    public function __construct(?string $name = null, CommandBus $commandBus)
    {
        parent::__construct($name);

        $this->commandBus = $commandBus;
    }

    protected function configure()
    {
        $this
            ->setDescription('Cron tasks for britannia');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);

        $this->commandBus->handle(UpdateStudentAge::make());
        $this->commandBus->handle(UpdateCourseStatus::make());
        $this->commandBus->handle(UpdateCalendar::make());
    }
}