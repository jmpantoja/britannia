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

namespace Britannia\Infraestructure\Symfony\Admin\Attendance;


use Britannia\Domain\Entity\Staff\StaffMember;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Doctrine\ORM\QueryBuilder;
use PlanB\DDDBundle\Sonata\Admin\AdminQuery;

final class AttendanceQuery extends AdminQuery
{
    /**
     * @var StaffMember
     */
    private StaffMember $user;
    /**
     * @var CarbonImmutable
     */
    private ?CarbonImmutable $date = null;

    public function setUser(StaffMember $user): self
    {
        $this->user = $user;
        return $this;
    }

    public function setDate(CarbonImmutable $date): self
    {
        $this->date = $date;
        return  $this;
    }

    /**
     * @return CarbonImmutable
     */
    public function date(): CarbonImmutable
    {
        $day = CarbonImmutable::today();
        if ($this->date instanceof CarbonImmutable) {
            $day = $this->date;
        }

        return $day->setTime(0, 0);
    }

    protected function configure(QueryBuilder $builder, string $alias = 'o'): void
    {

        $this->configureQuery($builder, $this->date());
    }

    /**
     * @param QueryBuilder $builder
     * @param Carbon $day
     */
    public function configureQuery(QueryBuilder $builder, CarbonImmutable $day): void
    {
        $courses = $this->user->activeCourses();

        $builder->where('o.day= :day')
            ->andWhere('o.course IN (:courses)')
            ->setParameter('day', $day)
            ->setParameter('courses', $courses);
    }
}
