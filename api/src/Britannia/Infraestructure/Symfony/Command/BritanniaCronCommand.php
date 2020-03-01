<?php

namespace Britannia\Infraestructure\Symfony\Command;

use Britannia\Application\UseCase\Cron\UpdateCalendar;
use Britannia\Application\UseCase\Cron\UpdateCourseStatus;
use Britannia\Application\UseCase\Cron\UpdateStudentAge;
use Britannia\Domain\Repository\StaffMemberRepositoryInterface;
use League\Tactician\CommandBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class BritanniaCronCommand extends Command implements ContainerAwareInterface
{
    protected static $defaultName = 'britannia:cron';
    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var StaffMemberRepositoryInterface
     */
    private $userRepository;
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(?string $name = null, CommandBus $commandBus, StaffMemberRepositoryInterface $userRepository)
    {
        parent::__construct($name);

        $this->commandBus = $commandBus;
        $this->userRepository = $userRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Cron tasks for britannia');
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
        $this->login();

        $this->commandBus->handle(UpdateStudentAge::make());
        $this->commandBus->handle(UpdateCourseStatus::make());
        $this->commandBus->handle(UpdateCalendar::make());
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
