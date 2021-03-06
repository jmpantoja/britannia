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

namespace PlanB\DDD\Domain\VO;


use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator;
use PlanB\DDD\Domain\VO\Validator\Constraint;

class PhoneNumber
{

    use Validable;
    /**
     * @var string
     */
    private $phoneNumber;
    /**
     * @var string
     */
    private $description;


    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\PhoneNumber([
            'required' => $options['required'] ?? true
        ]);
    }

    public static function make(string $phoneNumber, string $description = null): self
    {
        $data = self::assert([
            'phoneNumber' => $phoneNumber,
            'description' => $description
        ]);

        return new self(...[
            $data['phoneNumber'],
            (string)$data['description']
        ]);
    }

    private function __construct(string $phoneNumber, string $description)
    {
        $this->setPhoneNumber($phoneNumber);
        $this->setDescription($description);
    }

    private function setPhoneNumber(string $phoneNumber): self
    {
        $this->phoneNumber = $phoneNumber;
        return $this;
    }

    /**
     * @return string
     */
    public function getPhoneNumber(): string
    {
        return $this->phoneNumber;
    }

    public function getRaw(): string
    {
        return str_replace(' ', '', $this->phoneNumber);
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return PhoneNumber
     */
    private function setDescription(string $description): PhoneNumber
    {
        $this->description = $description;
        return $this;
    }


    public function __toString()
    {
        return $this->getPhoneNumber();
    }

    /**
     * ver mas en https://www.consumoresponde.es/art%C3%ADculos/rangos_de_numeracion_existentes_en_espana
     * @return bool
     */
    public function isMobile(): bool
    {
        $number = $this->phoneNumber;

        return (bool)preg_match('/^(6|71|72|73|74)/', $number);
    }


    /**
     * ver mas en https://www.consumoresponde.es/art%C3%ADculos/rangos_de_numeracion_existentes_en_espana
     * @return bool
     */
    public function isLandline(): bool
    {
        $number = $this->phoneNumber;

        return (bool)preg_match('/^(9|8)[^0]/', $number);
    }

}
