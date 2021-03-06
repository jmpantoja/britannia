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

use Britannia\Domain\Entity\Issue\Issue;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Domain\Entity\Student\Student;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 *
 * @method Issue|null findOneBy(array $criteria, array $orderBy = null)
 * @method Issue[]    findAll()
 * @method Issue[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface IssueRepositoryInterface
{

    public function numOfUnread(StaffMember $staffMember): int;

    public function getMainIssue(Student $student, ?UserInterface $author): ?Issue;

}
