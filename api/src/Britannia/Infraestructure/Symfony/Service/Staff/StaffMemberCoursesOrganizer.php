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


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Staff\StaffMember;

final class StaffMemberCoursesOrganizer
{

    public function distributeByGroups(StaffMember $member): array
    {
        $courses = $member->activeCourses();

        $list = [
            '+18' => [],
            'Regular' => [],
            'Preescolar' => [],
            'Apoyo' => [],
            '1to1' => [],
        ];

        /**
         * @var Course $course
         */
        foreach ($courses as $course) {
            $key = $this->typeOfCourse($course);
            $list[$key][] = $course;
        }

        return $list;
    }

    private function typeOfCourse(Course $course): string
    {
        if ($course instanceof Course\Adult) {
            return '+18';
        }

        if ($course instanceof Course\School) {
            return 'Regular';
        }

        if ($course instanceof Course\PreSchool) {
            return 'Preescolar';
        }

        if ($course instanceof Course\Support) {
            return 'Apoyo';
        }

        if ($course instanceof Course\OneToOne) {
            return '1to1';
        }
    }

}
