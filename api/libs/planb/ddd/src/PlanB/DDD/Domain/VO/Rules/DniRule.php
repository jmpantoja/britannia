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

namespace PlanB\DDD\Domain\VO\Rules;


use Respect\Validation\Rules\AbstractRule;

class DniRule extends AbstractRule
{

    public const DNI_FORMAT_REGEX = '/^(\d{8})([A-Z]{1})$/';

    public const NIE_FORMAT_REGEX = '/^([X-Y-Z]{1})(\d{7})([A-Z]{1})$/';

    private const CONTROL_LETTERS = "TRWAGMYFPDXBNJZSQVHLCKE";

    private const NIE_LETTERS = ['X' => 0, 'Y' => 1, 'Z' => 2];


    public function validate($dni)
    {
        $prefix = substr($dni, 0, 1);

        if (isset(self::NIE_LETTERS[$prefix])) {
            return $this->validateNIE($dni);
        }

        return $this->validateDNI($dni);
    }

    private function validateDNI(string $dni): bool
    {
        $matches = [];
        if (!preg_match(self::DNI_FORMAT_REGEX, $dni, $matches)) {
            $this->setTemplate('DNI incorrecto (ej. 99 99 99 99 A)');
            return false;
        }

        list(, $number, $letter) = $matches;
        return $this->checkLetter($number, $letter);

    }

    private function checkLetter(string $number, $letter): bool
    {
        $module = ((int)$number) % 23;
        $control = substr(self::CONTROL_LETTERS, $module, 1);

        if ($control !== $letter) {
            $this->setTemplate('Letra de control incorrecta');
            return false;
        }

        return true;
    }

    private function validateNIE(string $nie): bool
    {
        $matches = [];
        if (!preg_match(self::NIE_FORMAT_REGEX, $nie, $matches)) {
            $this->setTemplate('NIE incorrecto (ej. X9 99 99 99 A)');
            return false;
        }

        list(, $prefix, $number, $letter) = $matches;
        $number = sprintf('%s%s', self::NIE_LETTERS[$prefix], $number);

        return $this->checkLetter($number, $letter);
    }
}
