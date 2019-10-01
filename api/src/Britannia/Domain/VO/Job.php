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


use Respect\Validation\Validator;

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

    public static function make($name, $status): self
    {
        self::ensureIsValid($name, $status);

        return new self($name, JobStatus::byName($status));
    }

    /**
     * @param $name
     * @param $status
     */
    private static function ensureIsValid($name, $status): void
    {
        $validator = Validator::create();

        $validator->key('name', Validator::alnum(' .-'));
        $validator->key('status', Validator::in(JobStatus::getNames()));

        $validator->assert([
            'name' => $name,
            'status' => $status
        ]);
    }

    private function __construct(string $name, JobStatus $status)
    {
        $this->setName($name);
        $this->status = $status;
    }

    /**
     * @param string $name
     * @return Job
     */
    private function setName(string $name): Job
    {
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

    public function setStatus($status): self
    {
        $this->status = $status;

        return $this;
    }
}
