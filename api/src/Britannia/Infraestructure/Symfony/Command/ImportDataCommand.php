<?php

namespace Britannia\Infraestructure\Symfony\Command;


use Britannia\Infraestructure\Symfony\Importer\ImporterManager;
use Britannia\Infraestructure\Symfony\Importer\Report\ConsoleReport;
use Britannia\Infraestructure\Symfony\Importer\Report\PlainTextErrorReport;
use Britannia\Infraestructure\Symfony\Importer\Report\PlainTextReport;
use Britannia\Infraestructure\Symfony\Importer\Report\PlainTextWarningsReport;
use Locale;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class ImportDataCommand extends Command
{
    protected static $defaultName = 'britannia:import:data';

    private $importer;

    /**
     * @var string
     */
    private $pathToReportDir;

    /**
     * @var CronLoginService
     */
    private CronLoginService $loginService;

    public function __construct(?string $name = null,
                                ImporterManager $import,
                                ParameterBagInterface $parameterBag,
                                CronLoginService $loginService)
    {
        parent::__construct($name);

        $this->importer = $import;
        $this->loginService = $loginService;
        $this->initParams($parameterBag);
    }

    /**
     * Sets the container.
     */
    public function initParams(ParameterBagInterface $parameterBag)
    {
        $pathToLogsDir = $parameterBag->get('kernel.logs_dir');
        $this->pathToReportDir = sprintf('%s/import', dirname($pathToLogsDir));

        @chmod($this->pathToReportDir, 0777);
    }

    protected function configure()
    {
        $this
            ->setDescription('Import previus data')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->loginService->login();

        $console = ConsoleReport::make($input, $output);
        $plainText = PlainTextReport::make($this->pathToReportDir);

        Locale::setDefault('es');
        $this->importer
            ->addReport($console)
            ->addReport($plainText)
            ->execute();
    }
}
