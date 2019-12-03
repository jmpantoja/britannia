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

namespace Britannia\Domain\VO\Course\Discount;


use Britannia\Domain\VO\Student\Job\JobStatus;
use PlanB\DDD\Domain\VO\Percent;

class Discount implements \Serializable
{

    /**
     * @var JobStatus
     */
    private $status;
    /**
     * @var Percent
     */
    private $discount;
    /**
     * @var bool
     */
    private $freeEnrollment;

    public static function withEnrollment(JobStatus $status, ?Percent $discount): self
    {
        $discount = $discount ?? Percent::zero();
        return new self($status, $discount, false);
    }

    public static function withoutEnrollment(JobStatus $status, ?Percent $discount): self
    {
        $discount = $discount ?? Percent::zero();
        return new self($status, $discount, true);
    }

    private function __construct(JobStatus $status, Percent $discount, bool $freeEnrollment)
    {
        $this->status = $status;
        $this->discount = $discount;
        $this->freeEnrollment = $freeEnrollment;
    }

    /**
     * @return JobStatus
     */
    public function getStatus(): JobStatus
    {
        return $this->status;
    }

    /**
     * @return Percent
     */
    public function getDiscount(): Percent
    {
        return $this->discount;
    }

    /**
     * @return bool
     */
    public function isFreeEnrollment(): bool
    {
        return $this->freeEnrollment;
    }



    public function serialize()
    {
        $data = [
            'status' => $this->status->getName(),
            'discount' => $this->discount->toInt(),
            'freeEnrollment' => $this->freeEnrollment
        ];

        return serialize($data);
    }

    public function unserialize($serialized)
    {
        $data = unserialize($serialized, [
            'allowed_classes' => []
        ]);

        $this->status = JobStatus::byName($data['status']);
        $this->discount = Percent::make($data['discount']);
        $this->freeEnrollment = $data['freeEnrollment'];
    }
}
