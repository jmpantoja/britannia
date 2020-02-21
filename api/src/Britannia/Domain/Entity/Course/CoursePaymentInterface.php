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

namespace Britannia\Domain\Entity\Course;


use Doctrine\Common\Collections\Collection;
use PlanB\DDD\Domain\VO\Price;

interface CoursePaymentInterface
{
    public function monthlyPayment(): ?Price;

    public function enrollmentPayment(): ?Price;

    public function discount(): Collection;
}
