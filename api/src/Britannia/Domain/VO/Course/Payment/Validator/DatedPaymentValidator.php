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

namespace Britannia\Domain\VO\Course\Payment\Validator;


use Carbon\CarbonImmutable;
use DateTimeInterface;
use PlanB\DDD\Domain\VO\Price;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class DatedPaymentValidator extends ConstraintValidator
{

    public function getConstraintType(): string
    {
        return DatedPayment::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof \Britannia\Domain\VO\Course\Payment\DatedPayment) {
            return;
        }

        $this->validateField('price', $value['price'], [
            new NotBlank(),
            new Type([
                'type' => Price::class
            ])
        ]);

        $this->validateField('deadLine', $value['deadLine'], [
            new NotBlank(),
            new Type([
                'type' => DateTimeInterface::class
            ])
        ]);

    }
}
