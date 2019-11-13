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


use Britannia\Domain\Entity\Staff\StaffMember;
use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;


/**
 *
 * @method StaffMember|null findOneBy(array $criteria, array $orderBy = null)
 * @method StaffMember[]    findAll()
 * @method StaffMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface StaffMemberRepositoryInterface extends UserLoaderInterface
{

    public function loadUserByUsername($username);
}
