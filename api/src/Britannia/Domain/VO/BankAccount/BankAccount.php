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

namespace Britannia\Domain\VO\BankAccount;


use PlanB\DDD\Domain\VO\CityAddress;
use PlanB\DDD\Domain\VO\Iban;
use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;

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

    private function __construct(string $titular, CityAddress $cityAddress, Iban $iban, int $number)
    {
        $this->setTitular($titular);
        $this->setIban($iban);
        $this->setCityAddress($cityAddress);
        $this->setNumber($number);

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
     * @param int $number
     * @return BankAccount
     */
    private function setNumber(int $number): BankAccount
    {
        $this->number = $number;
        return $this;
    }

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
     * @return string
     */
    public function getCityAddress(): CityAddress
    {
        return $this->cityAddress;
    }

    /**
     * @return int
     */
    public function getNumber(): int
    {
        return $this->number;
    }

    public function getLastDigits()
    {
        $complete = $this->getIban()->getElectronicFormat();
        return substr($complete, -4);
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

    public function __toString()
    {
        return $this->getIban()->getElectronicFormat();
    }


}
