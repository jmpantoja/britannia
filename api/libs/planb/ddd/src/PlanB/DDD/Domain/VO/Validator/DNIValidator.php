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


class DNIValidator extends ConstraintValidator
{

    public const DNI_FORMAT_REGEX = '/^(\d{8})([A-Z]{1})$/';

    public const NIE_FORMAT_REGEX = '/^([X-Y-Z]{1})(\d{7})([A-Z]{1})$/';

    private const CONTROL_LETTERS = "TRWAGMYFPDXBNJZSQVHLCKE";

    private const NIE_LETTERS = ['X' => 0, 'Y' => 1, 'Z' => 2];

    /**
     * @return string
     */
    public function getConstraintType(): string
    {
        return DNI::class;
    }


    public function handle($value, Constraint $constraint)
    {
        $prefix = substr($value, 0, 1);

        if (isset(self::NIE_LETTERS[$prefix])) {
            $this->validateNIE($value);
            return;
        }

        $this->validateDNI($value);
    }

    private function validateDNI(string $dni): void
    {
        $matches = [];
        if (!preg_match(self::DNI_FORMAT_REGEX, $dni, $matches)) {
            $this->addViolation('DNI incorrecto (ej. 99 99 99 99 A)');
            return;
        }

        list(, $number, $letter) = $matches;
        $this->checkLetter($number, $letter);

    }

    private function checkLetter(string $number, $letter)
    {
        $module = ((int)$number) % 23;
        $control = substr(self::CONTROL_LETTERS, $module, 1);

        if ($control !== $letter) {
            $this->addViolation('Letra de control incorrecta');
        }

    }

    private function validateNIE(string $nie)
    {
        $matches = [];
        if (!preg_match(self::NIE_FORMAT_REGEX, $nie, $matches)) {
            $this->addViolation('NIE incorrecto (ej. X9 99 99 99 A)');
            return false;
        }

        list(, $prefix, $number, $letter) = $matches;
        $number = sprintf('%s%s', self::NIE_LETTERS[$prefix], $number);

        $this->checkLetter($number, $letter);
    }

}
