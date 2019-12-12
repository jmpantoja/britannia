<?php

namespace Britannia\Infraestructure\Symfony\Command;


use Britannia\Domain\Repository\StaffMemberRepositoryInterface;
use Britannia\Infraestructure\Symfony\Importer\ImporterManager;
use Britannia\Infraestructure\Symfony\Importer\Report\ConsoleReport;
use Britannia\Infraestructure\Symfony\Importer\Report\PlainTextErrorReport;
use Britannia\Infraestructure\Symfony\Importer\Report\PlainTextReport;
use Britannia\Infraestructure\Symfony\Importer\Report\PlainTextWarningsReport;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class BritanniaImportDataCommand extends Command implements ContainerAwareInterface
{
    protected static $defaultName = 'britannia:import:data';

    private $importer;

    /**
     * @var string
     */
    private $pathToReportDir;
    /**
     * @var StaffMemberRepositoryInterface
     */
    private $userRepository;

    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(?string $name = null,
                                ImporterManager $import,
                                StaffMemberRepositoryInterface $userRepository)
    {
        parent::__construct($name);

        $this->importer = $import;
        $this->userRepository = $userRepository;
    }

    /**
     * Sets the container.
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $pathToLogsDir = $container->getParameter('kernel.logs_dir');
        $this->pathToReportDir = sprintf('%s/reports', dirname($pathToLogsDir));

        @chmod($this->pathToReportDir, 0777);

        $this->container = $container;
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
        ini_set('memory_limit', '512M');
        $this->login();

        $console = ConsoleReport::make($input, $output);
        $plainText = PlainTextReport::make($this->pathToReportDir);

        $this->importer
            ->addReport($console)
            ->addReport($plainText)
            ->execute();
    }

    protected function login()
    {
        $user = $this->userRepository->findOneBy([
            'userName' => 'administrador'
        ]);

        if (empty($user)) {
            return;
        }

        $token = new UsernamePasswordToken(
            $user,
            null,
            'main',
            $user->getRoles());

        $this->container->get('security.token_storage')->setToken($token);

    }
}
