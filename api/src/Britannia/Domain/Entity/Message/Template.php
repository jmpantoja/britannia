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

namespace Britannia\Domain\Entity\Message;


use Britannia\Domain\Entity\Message\Template\EmailTemplate;
use Britannia\Domain\Entity\Message\Template\SmsTemplate;

abstract class Template
{
    /** @var TemplateId */
    private $id;

    /** @var string */
    private $name;

    /** @var string */
    private $template;


    protected function __construct(TemplateDto $dto)
    {
        $this->id = new TemplateId();
        $this->update($dto);
    }

    public function update(TemplateDto $dto): self
    {
        $this->name = $dto->name;
        $this->template = $dto->template;

        return $this;
    }

    /**
     * @return TemplateId
     */
    public function id(): ?TemplateId
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function name(): ?string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function template(): ?string
    {
        return $this->template;
    }

    public function isSms(): bool
    {
        return $this instanceof SmsTemplate;
    }

    public function isEmail(): bool
    {
        return $this instanceof EmailTemplate;
    }

    public function __toString(){
        return $this->name;
    }
}
