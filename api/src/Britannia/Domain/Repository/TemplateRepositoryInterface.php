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


use Britannia\Domain\Entity\Message\Template\EmailTemplate;
use Britannia\Domain\VO\Message\EmailPurpose;

/**
 *
 * @method Template|null findOneBy(array $criteria, array $orderBy = null)
 * @method Template[]    findAll()
 * @method Template[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
interface TemplateRepositoryInterface
{

    /**
     * @return Template[]
     */
    public function findAllSms(): array;

    /**
     * @return Template[]
     */
    public function findAllEmail(): array;

    /**
     * @param EmailPurpose $purpose
     * @return EmailTemplate
     */
    public function getByEmailPurpose(EmailPurpose $purpose);
}

