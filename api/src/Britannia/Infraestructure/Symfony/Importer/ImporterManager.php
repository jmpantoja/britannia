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

namespace Britannia\Infraestructure\Symfony\Importer;


use Britannia\Infraestructure\Symfony\Importer\Etl\EtlInterface;
use Britannia\Infraestructure\Symfony\Importer\Report\Reporter;
use Britannia\Infraestructure\Symfony\Importer\Report\ReportInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ImporterManager
{

    /**
     * @var EtlInterface[]
     */
    protected $etls = [];
    /**
     * @var Reporter
     */
    private Reporter $reporter;

    public function __construct()
    {
        $this->reporter = Reporter::make();
    }

    public function addReport(ReportInterface $report): self
    {
        $this->reporter->add($report);
        return $this;
    }

    public function addEtl(EtlInterface $import): self
    {
        $this->etls[] = $import;
        return $this;
    }

    public function execute()
    {
        $this->cleanAll();
        $this->runAll();
    }

    /**
     * @param SymfonyStyle $console
     */
    protected function cleanAll(): void
    {
        foreach ($this->etls as $etl) {
            $etl->clean();
        }
    }

    /**
     * @param SymfonyStyle $console
     */
    protected function runAll(): void
    {
        foreach ($this->etls as $etl) {
            $etl->run($this->reporter);
        }
    }
}
