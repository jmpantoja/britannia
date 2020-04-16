<?php

namespace Britannia\Infraestructure\Symfony\Command;

use Britannia\Application\UseCase\Cron\UpdateInvoices;
use League\Tactician\CommandBus;
use Locale;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InvoicesUpdateCommand extends Command
{
    protected static $defaultName = 'britannia:invoices:update';
    /**
     * @var CommandBus
     */
    private CommandBus $commandBus;

    public function __construct(string $name = null, CommandBus $commandBus)
    {
        parent::__construct($name);
        $this->commandBus = $commandBus;
    }

    protected function configure()
    {
        $this
            ->setDescription('generate invoices');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        Locale::setDefault('es');
        $this->commandBus->handle(UpdateInvoices::make());
        return 0;
    }
}
