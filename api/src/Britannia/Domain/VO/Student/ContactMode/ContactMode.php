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

/**
 * @method static self TELEPHONE()
 * @method static self WHATSAPP()
 * @method static self EMAIL()
 * @method static self POSTMAIL()
 */
class ContactMode extends Enum
{
    public const TELEPHONE = 'Llamada de teléfono';
    public const WHATSAPP = 'Enviar Whatsapp';
    public const EMAIL = 'Enviar correo electrónico';
    public const POSTMAIL = 'Enviar correo ordinario';

    public function isTelephone()
    {
        return $this->is(self::TELEPHONE());
    }

    public function isWhatsapp()
    {
        return $this->is(self::WHATSAPP());
    }

    public function isEmail()
    {
        return $this->is(self::EMAIL());
    }

    public function isPostmail()
    {
        return $this->is(self::POSTMAIL());
    }
}
