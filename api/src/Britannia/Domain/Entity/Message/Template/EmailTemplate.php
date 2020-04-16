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


use Britannia\Domain\Entity\Message\Template;
use Britannia\Domain\Entity\Message\TemplateDto;
use Britannia\Domain\VO\Message\EmailPurpose;
use Britannia\Domain\VO\Message\MessageMailer;

final class EmailTemplate extends Template
{
    private $subject;
    private $mailer;
    private $purpose;

    public static function make(TemplateDto $dto): self
    {
        return new static($dto);
    }

    public function update(TemplateDto $dto): self
    {
        parent::update($dto);
        $this->subject = $dto->subject;
        $this->mailer = $dto->mailer;
        $this->purpose = $dto->purpose;

        return $this;
    }

    /**
     * @return mixed
     */
    public function subject(): string
    {
        return $this->subject;
    }

    /**
     * @return mixed
     */
    public function mailer(): MessageMailer
    {
        return $this->mailer;
    }

    /**
     * @return mixed
     */
    public function purpose(): ?EmailPurpose
    {
        if (!($this->purpose instanceof EmailPurpose)) {
            return null;
        }

        return $this->purpose;
    }


}
