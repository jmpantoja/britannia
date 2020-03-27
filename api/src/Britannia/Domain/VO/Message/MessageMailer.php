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


use MabeEnum\Enum;

/**
 * @method static self MANAGERS()
 * @method static self TEACHERS()
 * @method static self RECEPTION()
 */
final class MessageMailer extends Enum
{
    public const MANAGERS = 'gestión';
    public const TEACHERS = 'teachers';
    public const RECEPTION = 'recepción';

}
