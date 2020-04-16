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

namespace Britannia\Domain\Entity\Message\Template;


use Britannia\Domain\Entity\Message\TemplateDto;
use Britannia\Domain\VO\Message\MessageMailer;
use Britannia\Domain\VO\Message\EmailPurpose;

final class EmailTemplateDto extends TemplateDto
{
    public ?MessageMailer $mailer;
    public ?EmailPurpose $purpose;
    public ?string $subject;
}
