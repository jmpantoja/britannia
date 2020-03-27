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

namespace Britannia\Domain\VO\Message\Validator;


use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;

use Britannia\Domain\VO;

final class MessageMailerValidator extends ConstraintValidator
{

    /**
     * @inheritDoc
     */
    public function getConstraintType(): string
    {
        return MessageMailer::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\Message\MessageMailer) {
            return;
        }

        if (VO\Message\MessageMailer::hasName($value)) {
            return;
        }

        $this->addViolation($constraint->message);
    }
}
