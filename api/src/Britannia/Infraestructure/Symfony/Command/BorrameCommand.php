<?php

namespace Britannia\Infraestructure\Symfony\Command;

use Britannia\Domain\Repository\CourseRepositoryInterface;
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

    public function __construct(CourseRepositoryInterface $repository, EntityManagerInterface $entityManager)
    {
        parent::__construct(null);

        $this->repository = $repository;
        $this->entityManager = $entityManager;
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

        $allCourses = $this->repository->findAll();

        $total = count($allCourses);


        foreach ($allCourses as $course) {
            $course->initColor();

            $this->entityManager->persist($course);

            $io->text($total--);

        }

        $this->entityManager->flush();

        die();


        return 0;
    }
}
