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

namespace PlanB\DDD\Domain\VO\Validator;


use Symfony\Component\Validator\Constraint as BaseConstraint;
use Symfony\Component\Validator\ConstraintValidator as Base;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

abstract class ConstraintValidator extends Base
{
    /**
     * @param mixed $value
     * @param Constraint $constraint
     * @return mixed
     */
    public function validate($value, BaseConstraint $constraint)
    {

        $this->assertConstraintType($constraint);
        $this->assertValueType($value, $constraint);
        $value = $constraint->sanitize($value);

        if ($constraint->isEmptyAndRequired($value)) {
            $this->addViolation($constraint->requiredMessage);
            return;
        }

        $this->handle($value, $constraint);
    }

    /**
     * @param Constraint $constraint
     */
    protected function assertConstraintType(BaseConstraint $constraint): void
    {
        if (!($constraint instanceof Constraint)) {
            throw new UnexpectedTypeException($constraint, Constraint::class);
        }

        $constrainType = $this->getConstraintType();
        if (!is_a($constraint, $constrainType)) {
            throw new UnexpectedTypeException($constraint, $constrainType);
        }
    }

    /**
     * @param Constraint $constraint
     */
    protected function assertValueType($value, Constraint $constraint): void
    {
        if (is_null($value)) {
            return;
        }

        if ($constraint->isValidType($value)) {
            return;
        }

        $type = $this->formatTypeOf($value);
        $message = sprintf("Tipo '%s' incorrecto", strtolower($type));
        $this->addViolation($message);

    }

    protected function validateField(string $field, $value, $constraints)
    {
        $this->context->getValidator()
            ->inContext($this->context)
            ->atPath('[' . $field . ']')
            ->validate($value, $constraints);
    }

    protected function validateValue($value, array $constraints)
    {
        $violationList = $this->context
            ->getValidator()
            ->validate($value, $constraints);

        foreach ($violationList as $violation) {
            $message = $violation->getMessage();
            $this->addViolation($message);
        }
    }


    /**
     * @return string
     */
    abstract public function getConstraintType(): string;

    abstract public function handle($value, Constraint $constraint);

    /**
     * @param $message
     */
    protected function addViolation($message): void
    {
        $this->context->addViolation($message);
    }

    protected function addViolationToField(string $name, string $message, $value = null)
    {
        $this->context->getValidator()
            ->inContext($this->context)
            ->getViolations()
            ->add(new ConstraintViolation($message, null, [], null, $name, $value));

    }


}
