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

namespace Britannia\Infraestructure\Symfony\Admin\Issue;


use Britannia\Domain\Entity\Issue\Issue;
use Britannia\Domain\Entity\Issue\IssueDto;
use PlanB\DDDBundle\Sonata\Admin\AdminMapper;
use Symfony\Component\Security\Core\Security;

final class IssueMapper extends AdminMapper
{
    /**
     * @var Security
     */
    private Security $security;


    /**
     * IssueMapper constructor.
     */
    public function __construct(Security $security)
    {
        parent::__construct();
        $this->security = $security;

    }

    protected function className(): string
    {
        return Issue::class;
    }

    protected function create(array $values): object
    {

        $dto = $this->makeDto($values);

        return Issue::make($dto);
    }

    /**
     * @param Issue $object
     * @param array $values
     * @return Issue
     */
    protected function update($object, array $values): object
    {
        $dto = $this->makeDto($values);
        return $object->update($dto);
    }

    private function makeDto(array $values)
    {
        $dto = IssueDto::fromArray($values);

        $dto->author = $this->security->getUser();
        return $dto;
    }
}
