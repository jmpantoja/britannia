<?php

namespace Britannia\Infraestructure\Symfony\Command;

use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Repository\CourseRepositoryInterface;
use Britannia\Domain\Service\Course\LessonGenerator;
use Britannia\Domain\VO\Course\TimeTable\TimeTable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class BorrameCommand extends Command
{
    protected static $defaultName = 'borrame';
    /**
     * @var CourseRepositoryInterface
     */
    private $repository;
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var LessonGenerator
     */
    private LessonGenerator $lessonGenerator;

    public function __construct(CourseRepositoryInterface $repository,
                                EntityManagerInterface $entityManager,
                                LessonGenerator $lessonGenerator
    )
    {
        parent::__construct(null);

        $this->repository = $repository;
        $this->entityManager = $entityManager;
        $this->lessonGenerator = $lessonGenerator;
    }

    protected function configure()
    {
        $this
            ->setDescription('Add a short description for your command')
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);



        return 0;
    }
}
