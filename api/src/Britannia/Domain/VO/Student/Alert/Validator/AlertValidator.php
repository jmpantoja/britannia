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

namespace Britannia\Domain\VO\Student\Alert\Validator;


use Britannia\Domain\VO;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class AlertValidator extends ConstraintValidator
{
    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return Alert::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\Student\Alert\Alert) {
            return;
        }

        if (false === (bool)$value['alert']) {
            return;
        }

        $description = $value['description'];
        $description = strip_tags($description);

        $this->validateField('description', $description, [
            new NotBlank(),
            new Length([
                'min' => 20
            ])
        ]);

    }

}
