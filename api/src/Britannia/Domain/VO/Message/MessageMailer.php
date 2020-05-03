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

namespace Britannia\Domain\VO\Message;


use PlanB\DDD\Domain\Enum\Enum;

/**
 * @method static self MANAGERS()
 * @method static self TEACHERS()
 * @method static self RECEPTION()
 */
final class MessageMailer extends Enum
{
    private const MANAGERS = 'gestión';
    private const TEACHERS = 'teachers';
    private const RECEPTION = 'recepción';
}
