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


use PlanB\DDD\Domain\VO\Percent;

class CourseDiscount implements \Serializable
{
    /**
     * @var Percent
     */
    private $discount;
    /**
     * @var bool
     */
    private $freeEnrollment;

    private function __construct(Percent $discount, bool $freeEnrollment)
    {
        $this->discount = $discount;
        $this->freeEnrollment = $freeEnrollment;
    }

    public static function byDefault(): self
    {
        return new self(Percent::zero(), false);
    }

    public static function withEnrollment(?Percent $discount): self
    {
        $discount = $discount ?? Percent::zero();
        return new self($discount, false);
    }

    public static function withoutEnrollment(?Percent $discount): self
    {
        $discount = $discount ?? Percent::zero();
        return new self($discount, true);
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

        $this->discount = Percent::make($data['discount']);
        $this->freeEnrollment = $data['freeEnrollment'];
    }
}
