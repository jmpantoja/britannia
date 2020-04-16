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

namespace Britannia\Infraestructure\Doctrine\Repository;


use Britannia\Domain\Entity\Message\Template;
use Britannia\Domain\Entity\Message\Template\EmailTemplate;
use Britannia\Domain\Repository\TemplateRepositoryInterface;
use Britannia\Domain\VO\Message\EmailPurpose;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Exception;

/**
 *
 * @method Template|null findOneBy(array $criteria, array $orderBy = null)
 * @method Template[]    findAll()
 * @method Template[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
final class TemplateRepository extends ServiceEntityRepository implements TemplateRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Template::class);
    }

    /**
     * @inheritDoc
     */
    public function findAllSms(): array
    {
        $query = $this->createQueryBuilder('A')
            ->where('A INSTANCE OF :type')
            ->setParameter('type', 'sms')
            ->getQuery();

        return $query->execute();

    }

    /**
     * @inheritDoc
     */
    public function findAllEmail(): array
    {
        $query = $this->createQueryBuilder('A')
            ->where('A INSTANCE OF :type')
            ->setParameter('type', 'email')
            ->getQuery();

        return $query->execute();

    }

    /**
     * @param EmailPurpose $purpose
     * @return EmailTemplate
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws Exception
     */
    public function getByEmailPurpose(EmailPurpose $purpose): EmailTemplate
    {

        $query = $this->_em->createQueryBuilder()
            ->select('A')
            ->from(EmailTemplate::class, 'A')
            ->where('A.purpose = :purpose')
            ->setParameter('purpose', $purpose)
            ->getQuery();

        try {
            $template = $query->getSingleResult();
        } catch (Exception $exception) {
            $message = sprintf('No hay ninguna plantilla definida para "%s"', $purpose->getValue());
            throw new Exception($message);
        }

        return $template;
    }
}
