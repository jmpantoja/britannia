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


use Britannia\Domain\VO\Course\Discount\CourseDiscount;
use Britannia\Domain\VO\Student\Job\JobStatus;

class JobStatusDiscountList
{

    /** @var CourseDiscount[] */
    private $data = [];

    private function __construct(array $data)
    {
        $data = array_change_key_case($data, CASE_LOWER);

        $this->data['student'] = $data['student'] ?? null;
        $this->data['unemployed'] = $data['unemployed'] ?? null;
        $this->data['retired'] = $data['retired'] ?? null;
        $this->data['disabled'] = $data['disabled'] ?? null;

    }

    public static function make(array $data = []): self
    {
        return new self($data);
    }

    public function getByJobStatus(JobStatus $jobStatus): CourseDiscount
    {
        $name = $jobStatus->getName();
        $key = strtolower($name);

        return $this->data[$key] ?? CourseDiscount::byDefault();
    }

    /**
     * @return CourseDiscount|mixed
     */
    public function student()
    {
        return $this->getByJobStatus(JobStatus::STUDENT());
    }

    /**
     * @return CourseDiscount|mixed
     */
    public function unemployed()
    {
        return $this->getByJobStatus(JobStatus::UNEMPLOYED());
    }

    /**
     * @return CourseDiscount|mixed
     */
    public function retired()
    {
        return $this->getByJobStatus(JobStatus::RETIRED());
    }

    /**
     * @return CourseDiscount|mixed
     */
    public function disabled()
    {
        return $this->getByJobStatus(JobStatus::DISABLED());
    }


    public function toArray()
    {
        return $this->data;
    }

}
