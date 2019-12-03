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

namespace Britannia\Domain\VO\Course\Book;


use MabeEnum\Enum;


class BookCategory extends Enum
{
    public const WORK_BOOK = 'Ejercicios';
    public const STUDENT_BOOK = 'Estudiante';
    public const TEACHER_BOOK = 'Profesor';
}
