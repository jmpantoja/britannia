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

namespace Britannia\Domain\Repository;

use Britannia\Domain\Entity\Invoice\Invoice;
use Britannia\Domain\Entity\Student\Student;
use Carbon\CarbonImmutable;

/**
 *
 * @method Invoice|null findOneBy(array $criteria, array $orderBy = null)
 * @method Invoice[]    findAll()
 * @method Invoice[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface InvoiceRepositoryInterface
{

    public function existsByStudentAndMonth(Student $student, CarbonImmutable $date): bool ;

    public function findUnPaidByStudentAndMonth(Student $student, CarbonImmutable $date): ?Invoice;

    public function findByBank(CarbonImmutable $month);
}
