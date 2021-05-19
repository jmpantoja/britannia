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

namespace Britannia\Application\UseCase\Invoice;


use Britannia\Domain\Entity\Setting\Setting;
use Britannia\Domain\Repository\InvoiceRepositoryInterface;
use Britannia\Domain\Service\Report\SepaDownload;
use Britannia\Domain\Service\Report\ReportList;
use PlanB\DDD\Application\UseCase\UseCaseInterface;

final class GenerateSepaXlsxUseCase implements UseCaseInterface
{
    /**
     * @var Setting
     */
    private Setting $setting;
    /**
     * @var InvoiceRepositoryInterface
     */
    private InvoiceRepositoryInterface $repository;

    public function __construct(InvoiceRepositoryInterface $repository, Setting $setting)
    {
        $this->repository = $repository;
        $this->setting = $setting;
    }

    public function handle(GenerateSepaXlsx $command)
    {
        $month = $command->month();
        $name = date_to_string($month, -1, -1, "MMMM 'de' Y");

        $invoices = $this->repository->findByBank($month);

        return ReportList::make($name, [
            SepaDownload::make($name, $invoices, $this->setting)
        ]);

    }
}
