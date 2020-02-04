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

namespace Britannia\Domain\VO\Student\ContactMode;


use MabeEnum\Enum;

class ContactMode extends Enum
{
    public const TELEPHONE = 'Llamada de teléfono';
    public const WHATSAPP = 'Enviar Whatsapp';
    public const EMAIL = 'Enviar correo electrónico';
    public const POSTMAIL = 'Enviar correo ordinario';
}
