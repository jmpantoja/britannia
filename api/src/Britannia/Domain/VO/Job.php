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

namespace Britannia\Domain\VO;


class Job
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var JobStatus
     */
    private $status;

    public static function make(?string $name, ?JobStatus $status): self
    {
        return new self($name, $status);
    }

    private function __construct(?string $name, ?JobStatus $status)
    {

        $this->setName($name);
        $this->status = $status;
    }


    /**
     * @param string $name
     * @return Job
     */
    private function setName(?string $name): Job
    {
        $name = !empty($name) ? $name : null;

        $this->name = $name;
        return $this;
    }


    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @return JobStatus
     */
    public function getStatus(): ?JobStatus
    {
        return $this->status;
    }
}
