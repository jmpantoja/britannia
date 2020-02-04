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


use Britannia\Domain\Entity\Lesson\Lesson;
use Britannia\Infraestructure\Symfony\Form\Type\Lesson\AttendanceListType;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;

final class AttendanceForm extends AdminForm
{

    public function configure(Lesson $lesson)
    {
        $this->add('attendances', AttendanceListType::class, [
            'lesson' => $lesson,
            'required' => false,
            'label' => $this->toString($lesson)
        ]);
    }


    private function toString(Lesson $lesson)
    {
        $date = \IntlDateFormatter::formatObject($lesson->day(), "EEEE, d 'de' MMMM Y");;

        $course = $lesson->course()->name();
        return sprintf('%s - %s', $course, $date);
    }
}
