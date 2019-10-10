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

class IbanRule extends AbstractRule
{

    public const IBAN_REGEX_PATTERN = '/^(([A-Z]{2})(\d{2}))?((\d{4})(\d{4})(\d{2})(\d{10}))$/';

    private const IBAN_WEIGHTS = ['A' => '10', 'B' => '11', 'C' => '12', 'D' => '13', 'E' => '14', 'F' => '15', 'G' => '16', 'H' => '17', 'I' => '18', 'J' => '19', 'K' => '20', 'L' => '21', 'M' => '22', 'N' => '23', 'O' => '24', 'P' => '25', 'Q' => '26', 'R' => '27', 'S' => '28', 'T' => '29', 'U' => '30', 'V' => '31', 'W' => '32', 'X' => '33', 'Y' => '34', 'Z' => '35'];

    private const CCC_WEIGHTS = [1, 2, 4, 8, 5, 10, 9, 7, 3, 6];

    private const INVALID_IBAN_MESSAGE = 'IBAN incorrecto (ej. ES00 9999 9999 9999 9999 9999)';

    private const INVALID_IBAN_DIGIT_CONTROL_MESSAGE = 'Digito de Control IBAN incorrecto';

    private const INVALID_CCC_DIGIT_CONTROL_MESSAGE = 'Digito de Control CCC incorrecto';


    public function validate($iban): bool
    {

        $iban = (string)$iban;
        $matches = [];
        if (!preg_match(self::IBAN_REGEX_PATTERN, $iban, $matches)) {
            $this->setTemplate(self::INVALID_IBAN_MESSAGE);
            return false;
        }

        list(, , $country, $control, $ccc, $entity, $office, $dc, $account) = $matches;

        return $this->validateIBAN($country, $control, $ccc)
            && $this->validateCCC($entity, $office, $dc, $account);
    }


    /**
     * @param string $country
     * @param string $control
     * @param string $ccc
     * @return bool
     */
    private function validateIBAN(string $country, string $control, string $ccc): bool
    {

        if (empty($country) && empty($control)) {
            return true;
        }

        $fist = substr($country, 0, 1);
        $second = substr($country, 1, 1);

        $code = sprintf('%s%s%s00', ...[
            $ccc,
            self::IBAN_WEIGHTS[$fist],
            self::IBAN_WEIGHTS[$second],
        ]);

        $expected = 98 - bcmod($code, '97');
        $expected = sprintf('%02s', $expected);

        if ($expected !== $control) {
            $this->setTemplate(self::INVALID_IBAN_DIGIT_CONTROL_MESSAGE);
            return false;
        }

        return true;
    }

    /**
     * @param string $entity
     * @param string $office
     * @param string $dc
     * @param string $account
     * @return bool
     */
    private function validateCCC(string $entity, string $office, string $dc, string $account): bool
    {
        $code1 = sprintf('00%s%s', $entity, $office);
        $code2 = $account;

        $expected = sprintf('%s%s', ...[
            $this->calculateControl($code1),
            $this->calculateControl($code2)
        ]);

        if ($expected !== $dc) {
            $this->setTemplate(self::INVALID_CCC_DIGIT_CONTROL_MESSAGE);
            return false;
        }

        return true;
    }


    private function calculateControl(string $number): int
    {
        $code = 0;

        for ($i = 0; $i < 10; $i++) {
            $code += substr($number, $i, 1) * self::CCC_WEIGHTS[$i];
        }

        $code = 11 - ($code % 11);

        if ($code === 11) {
            $code = 0;
        }

        if ($code === 10) {
            $code = 1;
        }

        return $code;
    }
}
