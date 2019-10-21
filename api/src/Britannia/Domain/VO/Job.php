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


use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use Britannia\Domain\VO\Validator;


class Job
{
    use Validable;

    /**
     * @var string
     */
    private $name;
    /**
     * @var JobStatus
     */
    private $status;


    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\Job([
            'required' => $options['required'] ?? true
        ]);
    }

    public static function make(?string $name, JobStatus $status): self
    {
        self::assert([
            'name' => $name,
            'status' => $status
        ]);

        if (is_string($status)) {
            $status = JobStatus::byName($status);
        }

        return new self((string)$name, $status);
    }

    private function __construct(string $name, JobStatus $status)
    {
        $this->setName($name);
        $this->setStatus($status);
    }

    /**
     * @param string $name
     * @return Job
     */
    private function setName(string $name): self
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

    private function setStatus(JobStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

}
