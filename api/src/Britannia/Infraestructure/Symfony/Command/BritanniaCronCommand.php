<?php

namespace Britannia\Infraestructure\Symfony\Command;

use Britannia\Application\UseCase\Course\UpdateCourseStatus;
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
//
//        $date = (new \DateTime())->setTime(0, 0, 0);
//        $endDate= (new \DateTime())->setTime(0, 0, 0)->sub(new \DateInterval('P1D'));
//
//        dump($date, $endDate, $date->diff($endDate));
//
//        die();
        $this->commandBus->handle(UpdateCourseStatus::make(...[
            new \DateTime()
        ]));


    }
}
