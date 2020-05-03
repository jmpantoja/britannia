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


use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self STUDENT_BOOK()
 * @method static self WORK_BOOK()
 * @method static self TEACHER_BOOK()
 */
class BookCategory extends Enum
{
    private const STUDENT_BOOK = 'Estudiante';
    private const WORK_BOOK = 'Ejercicios';
    private const TEACHER_BOOK = 'Profesor';
}
