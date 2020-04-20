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

namespace Britannia\Infraestructure\Symfony\Admin\TeacherCourse;


use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use PlanB\DDDBundle\Sonata\Admin\AdminQuery;
use Symfony\Component\Security\Core\User\UserInterface;


final class TeacherCourseQuery extends AdminQuery
{
    /**
     * @var UserInterface|null
     */
    private UserInterface $user;

    public function setUser(UserInterface $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function configure(QueryBuilder $builder, string $alias = 'o'): void
    {
        //  $builder->orderBy('o.timeRange.start', 'DESC');

        $builder->innerJoin('o.teachers', 't', Join::WITH, 't.id = :teacherId')
            ->setParameter('teacherId', $this->user);

    }
}
