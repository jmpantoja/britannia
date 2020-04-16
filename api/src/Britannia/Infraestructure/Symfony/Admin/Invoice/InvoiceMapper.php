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

namespace Britannia\Infraestructure\Symfony\Admin\Invoice;


use Britannia\Domain\Entity\Invoice\Invoice;
use Britannia\Domain\Entity\Invoice\InvoiceDto;
use Britannia\Domain\Entity\Issue\Issue;
use Britannia\Domain\Entity\Issue\IssueDto;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;
use Symfony\Component\Security\Core\Security;

final class InvoiceMapper extends AdminMapper
{
    protected function className(): string
    {
        return Invoice::class;
    }

    protected function create(array $values): object
    {

        $dto = $this->makeDto($values);
        return Invoice::make($dto);
    }

    /**
     * @param Invoice $object
     * @param array $values
     * @return Invoice
     */
    protected function update($object, array $values): object
    {
        $dto = $this->makeDto($values);
        return $object->update($dto);
    }

    private function makeDto(array $values)
    {
        $dto = InvoiceDto::fromArray($values);
        return $dto;
    }
}
