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

namespace Britannia\Domain\VO\Discount;


use Britannia\Domain\VO\Student\Job\JobStatus;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\PositiveInteger;
use Tightenco\Collect\Support\Collection;

class JobStatusDiscountList
{
    /**
     * @var Collection
     */
    private $data;

    public static function make(Collection $data): self
    {
        return new self($data);
    }

    private function __construct(Collection $data)
    {

        $this->data = $data;
    }

    public function getByJobStatus(JobStatus $jobStatus): ?Percent
    {
        $name = $jobStatus->getName();
        $key = strtolower($name);

        return $this->data->get($key);
    }


}
