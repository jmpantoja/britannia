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

namespace Britannia\Domain\VO;


use PlanB\DDD\Domain\VO\CityAddress;
use PlanB\DDD\Domain\VO\FullName;
use PlanB\DDD\Domain\VO\Iban;
use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use Britannia\Domain\VO\Validator;

class BankAccount
{

    use Validable;
    /**
     * @var string
     */
    private $titular;
    /**
     * @var IBAN
     */
    private $iban;
    /**
     * @var string
     */
    private $cityAddress;
    /**
     * @var int
     */
    private $number = 0;

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\BankAccount([
            'required' => $options['required'] ?? true
        ]);
    }

    public static function make(string $titular, CityAddress $cityAddress, Iban $iban, int $number): self
    {
        return new self($titular, $cityAddress, $iban, $number);
    }

    private function __construct(string $titular, CityAddress $cityAddress, Iban $iban, int $number)
    {
        $this->setTitular($titular);
        $this->setIban($iban);
        $this->setCityAddress($cityAddress);
        $this->setNumber($number);

    }

    /**
     * @return string
     */
    public function getTitular(): string
    {
        return $this->titular;
    }

    /**
     * @param string $titular
     * @return BankAccount
     */
    public function setTitular(string $titular): BankAccount
    {
        $this->titular = $titular;
        return $this;
    }

    /**
     * @return Iban
     */
    public function getIban(): Iban
    {
        return $this->iban;
    }

    /**
     * @param Iban $iban
     * @return BankAccount
     */
    public function setIban(Iban $iban): BankAccount
    {
        $this->iban = $iban;
        return $this;
    }

    /**
     * @return string
     */
    public function getCityAddress(): CityAddress
    {
        return $this->cityAddress;
    }

    /**
     * @param string $cityAddress
     * @return BankAccount
     */
    private function setCityAddress(CityAddress $cityAddress): BankAccount
    {
        $this->cityAddress = $cityAddress;
        return $this;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    /**
     * @param int $number
     * @return BankAccount
     */
    private function setNumber(int $number): BankAccount
    {
        $this->number = $number;
        return $this;
    }

    public function __toString()
    {
        return $this->getIban()->getElectronicFormat();
    }

}
