<?php

namespace Britannia\Infraestructure\Symfony\Command;

use Britannia\Application\UseCase\Cron\MakeBackup;
use Britannia\Infraestructure\Symfony\Service\Backup\BackupManager;
use Carbon\CarbonImmutable;
use Doctrine\ORM\EntityManager;
use League\Tactician\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class BackupCommand extends Command
{
    protected static $defaultName = 'britannia:backup';

    /**
     * @var EntityManager
     */
    private EntityManager $entityManager;
    /**
     * @var ParameterBagInterface
     */
    private ParameterBagInterface $parameterBag;
    /**
     * @var CommandBus
     */
    private CommandBus $commandBus;
    /**
     * @var BackupManager
     */
    private BackupManager $backupManager;

    public function __construct(string $name = null, BackupManager $backupManager)
    {
        parent::__construct($name);
        $this->backupManager = $backupManager;
    }

    protected function configure()
    {
        $this
            ->setDescription('backup');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->backupManager->create();
        return 0;
    }
}
