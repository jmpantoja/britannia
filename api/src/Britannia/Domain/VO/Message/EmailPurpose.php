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
 * @method static self SEND_INVOICE()
 * @method static self SEND_TERM_MARKS()
 */
final class EmailPurpose extends Enum
{
    public const SEND_INVOICE = 'Envio Recibos';
    public const SEND_TERM_MARKS = 'Envio Boletin';
}
