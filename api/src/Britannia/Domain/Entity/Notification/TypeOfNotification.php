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

namespace Britannia\Domain\Entity\Notification;


use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self NEW_STUDENT()
 * @method static self STUDENT_BEGINS_COURSE()
 * @method static self STUDENT_LEAVE_COURSE()
 * @method static self ATTENDANCE()
 * @method static self MESSAGE_SENT()
 * @method static self INVOICE_PAID()
 */
class TypeOfNotification extends Enum
{
    public const NEW_STUDENT = 'Nuevo Alumno';
    public const STUDENT_BEGINS_COURSE = 'Incorporación a curso';
    public const STUDENT_LEAVE_COURSE = 'Abandono de curso';
    public const ATTENDANCE = 'Falta de asistencia';
    public const MESSAGE_SENT = 'Mensaje enviado';
    public const INVOICE_PAID = 'Recibo pagado';
    public const ISSUE = 'Observación';

}
