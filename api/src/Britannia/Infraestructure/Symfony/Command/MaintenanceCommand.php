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
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class MaintenanceCommand extends Command implements ContainerAwareInterface
{
    protected static $defaultName = 'britannia:maintenance';
    /**
     * @var CommandBus
     */
    private $commandBus;

    /**
     * @var ContainerInterface
     */
    private $container;
    /**
     * @var CronLoginService
     */
    private CronLoginService $cronLoginService;

    public function __construct(?string $name = null, CommandBus $commandBus, CronLoginService $cronLoginService)
    {
        parent::__construct($name);

        $this->commandBus = $commandBus;
        $this->cronLoginService = $cronLoginService;
    }

    protected function configure()
    {
        $this
            ->setDescription('maintenance tasks for britannia');
    }

    /**
     * Sets the container.
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $this->cronLoginService->login();

        $this->commandBus->handle(UpdateCalendar::make());
        $this->commandBus->handle(UpdateStudentAge::make());
        $this->commandBus->handle(UpdateCourseStatus::make());

        return 0;
    }
}
