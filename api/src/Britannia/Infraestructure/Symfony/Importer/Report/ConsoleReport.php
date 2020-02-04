<?php

/**
 * This file is part of the planb project.
 *
 * (c) jmpantoja <jmpantoja@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Importer\Report;


use Britannia\Infraestructure\Symfony\Importer\Resume;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\OutputStyle;
use Symfony\Component\Console\Style\SymfonyStyle;

class ConsoleReport extends ReportAbstract
{

    /**
     * @var OutputStyle
     */
    private $style;

    private function __construct(OutputStyle $style)
    {
        $this->style = $style;
    }

    public static function make(InputInterface $input, OutputInterface $output): self
    {

        $style = new SymfonyStyle($input, $output);
        return new self($style);
    }

    public function success(Resume $resume): void
    {
        $line = $this->parseHead($resume);
        $this->style->success($line);
    }

    public function error(Resume $resume): void
    {
        $line = $this->parseHead($resume);
        $this->style->error($line);
    }


    public function warning(Resume $resume): void
    {
        $line = $this->parseHead($resume);
        $this->style->warning($line);
    }
}
