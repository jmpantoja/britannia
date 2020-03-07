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

namespace Britannia\Domain\Service\Report;


use Britannia\Domain\Entity\Setting\Setting;
use Britannia\Domain\Entity\Student\Student;

final class StudentRegistrationFormForStudent extends HtmlBasedPdfReport
{
    /**
     * @param Student $student
     * @param Setting $setting
     * @return static
     */
    public static function make(Student $student, Setting $setting): self
    {
        $params = [
            'student' => $student,
            'setting' => $setting
        ];

        $options = [
            'page-size' => 'A4',
            'orientation' => 'Portrait',
        ];

        $name = sprintf('ficha-%s-ejemplar-alumno', $student);
        return new self($name, $params, $options);
    }

}
