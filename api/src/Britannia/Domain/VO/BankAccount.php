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

class BankAccount
{
    /**
     * @var FullName
     */
    private $fullName;
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

    public static function make(FullName $fullName, CityAddress $cityAddress, Iban $iban, int $number): self
    {
        return new self($fullName, $cityAddress, $iban, $number);
    }

    private function __construct(FullName $fullName, CityAddress $cityAddress, Iban $iban, int $number)
    {
        $this->setFullName($fullName);
        $this->setIban($iban);
        $this->setCityAddress($cityAddress);
        $this->setNumber($number);

    }

    /**
     * @return FullName
     */
    public function getFullName(): FullName
    {
        return $this->fullName;
    }

    /**
     * @param FullName $fullName
     * @return BankAccount
     */
    public function setFullName(FullName $fullName): BankAccount
    {
        $this->fullName = $fullName;
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


}
