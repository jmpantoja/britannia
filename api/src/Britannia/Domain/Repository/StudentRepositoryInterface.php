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


use Britannia\Domain\Entity\Student\Student;
use Carbon\CarbonImmutable;

/**
 * @method Student|null find($id, $lockMode = null, $lockVersion = null)
 * @method Student|null findOneBy(array $criteria, array $orderBy = null)
 * @method Student[]    findAll()
 * @method Student[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface StudentRepositoryInterface
{
    /**
     * @return Student[]
     */
    public function findByIdList(array $list): array;

    /**
     * @return Student[]
     */
    public function findByBirthDay(CarbonImmutable $day): array;

    /**
     * @return Student[]
     */
    public function findActives(): array;

    /**
     * @return Student[]
     */
    public function disableStudentsWithoutActiveCourses();


}

