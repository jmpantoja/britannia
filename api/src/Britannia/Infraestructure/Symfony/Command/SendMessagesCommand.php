<?php

namespace Britannia\Infraestructure\Symfony\Command;

use Britannia\Application\UseCase\Cron\SendMessages;
use Britannia\Domain\Repository\StaffMemberRepositoryInterface;
use League\Tactician\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class SendMessagesCommand extends Command
{
    protected static $defaultName = 'britannia:messages:send';
    /**
     * @var CommandBus
     */
    private CommandBus $commandBus;

    public function __construct(?string $name = null, CommandBus $commandBus)
    {
        parent::__construct($name);
        $this->commandBus = $commandBus;
    }


    protected function configure()
    {
        $this
            ->setDescription('Send SMS and Emails');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $this->commandBus->handle(SendMessages::make());

        $output->write('log output');
        return 0;
    }
}
