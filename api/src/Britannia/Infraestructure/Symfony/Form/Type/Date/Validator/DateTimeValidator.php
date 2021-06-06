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

namespace Britannia\Infraestructure\Symfony\Form\Type\Date\Validator;


use DummyBarTest;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Type;

final class DateTimeValidator extends ConstraintValidator
{

    public function getConstraintType(): string
    {
        return DateTime::class;
    }

    public function handle($value, Constraint $constraint)
    {
        dump($value);
        die('xxx');
        $this->validateField('date', $value['date'], [
            new NotBlank([])
        ]);

        $this->addViolation('asdadasd');

    }
}
