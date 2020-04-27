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

namespace Britannia\Infraestructure\Symfony\Service\Staff;


use Britannia\Domain\Entity\Staff\StaffMember;

final class StaffMemberCoursesOrganizer
{

    public function distributeByGroups(StaffMember $member, int $numOfGroups = 6): array
    {
        $courses = $member->activeCourses();

        $list = [];
        foreach ($courses as $index => $course) {
            $key = $index % $numOfGroups;
            $list[$key][] = $course;
        }

        return $list;
    }

}
