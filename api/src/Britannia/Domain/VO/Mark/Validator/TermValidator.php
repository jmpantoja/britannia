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

namespace Britannia\Domain\VO\Mark\Validator;


use Britannia\Domain\VO;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

class TermValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return Term::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\Mark\TermDefinition) {
            return;
        }

        $this->validateField('weighOfUnits', $value['weighOfUnits'], [
            new NotBlank(),
            new Type([
                'type' => Percent::class
            ])
        ]);
    }
}
