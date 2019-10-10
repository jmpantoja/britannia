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

namespace PlanB\DDD\Domain\VO\Traits;


use PlanB\DDD\Domain\VO\Validator\Constraint;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validation;

trait Validable
{
    /**
     * @param $value
     * @param array $options
     * @return
     */
    public static function assert($value, array $options = [])
    {
        $constraint = self::buildConstraint($options);
        $violationList = self::validateWithConstraint($value, $constraint);

        $value = $constraint->sanitize($value);
        if (count($violationList) > 0) {
            $message = self::prepareViolationsMessage($violationList);
            throw new \UnexpectedValueException($message);
        }

        return $constraint->normalize($value);
    }

    /**
     * @param ConstraintViolationListInterface $violationList
     * @return string
     */
    private static function prepareViolationsMessage(ConstraintViolationListInterface $violationList): string
    {
        $messages = [];
        foreach ($violationList as $violation) {
            $name = $violation->getPropertyPath();

            $messages[$name] = $messages[$name] ?? [];
            $messages[$name][] = $violation->getMessage();
        }

        $message = json_encode($messages, JSON_PRETTY_PRINT);
        return $message;
    }

    public static function validate($value, array $options = []): ConstraintViolationListInterface
    {
        $constraint = self::buildConstraint($options);

        return self::validateWithConstraint($value, $constraint);
    }

    /**
     * @param $value
     * @param $constraint
     * @return \Symfony\Component\Validator\ConstraintViolationListInterface
     */
    private static function validateWithConstraint($value, Constraint $constraint): ConstraintViolationListInterface
    {
        $validator = Validation::createValidator();
        return $validator->validate($value, $constraint);
    }


    public static function isValid($value, array $options = []): bool
    {
        $violationList = self::validate($value);
        return count($violationList) === 0;
    }

    abstract public static function buildConstraint(array $options = []): Constraint;

}
