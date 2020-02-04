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

namespace Britannia\Domain\VO\Payment\Validator;


use Britannia\Domain\VO\Payment\PaymentMode;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints\NotBlank;

class PaymentValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return Payment::class;
    }

    public function handle($value, Constraint $constraint)
    {
        /** @var PaymentMode $mode */
        $mode = $value['mode'];

        if ($mode->is(PaymentMode::CASH)) {
            return;
        }

        $this->validateField('account', $value['account'], [
            new NotBlank([
                'message' => 'Se necesitan los datos de la domiciliación'
            ])
        ]);

    }
}
