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

namespace Britannia\Domain\VO\Validator;


use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraints\NotBlank;
use Britannia\Domain\VO;

class JobValidator extends ConstraintValidator
{

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return Job::class;
    }

    public function handle($value, Constraint $constraint)
    {
        if ($value instanceof VO\Job) {
            return;
        }

        $this->validateField('name', $value['name'], [
            new NotBlank([
                'allowNull' => true
            ])
        ]);


        $this->validateField('status', $value['status'], [
            new JobStatus()
        ]);
    }
}
