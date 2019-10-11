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


use PlanB\DDD\Domain\VO\Rules\IbanRule;
use PlanB\DDD\Domain\VO\Traits\Validable;
use PlanB\DDD\Domain\VO\Validator\Constraint;
use PlanB\DDD\Domain\VO\Validator;

class Iban
{
    use Validable;

    /**
     * @var string
     */
    private $iban;
    private $ccc;
    private $entity;
    private $office;
    private $digitOfControl;
    private $account;

    public static function buildConstraint(array $options = []): Constraint
    {
        return new Validator\Iban([
            'required' => $options['required'] ?? true
        ]);
    }


    public static function make(string $iban): self
    {
        $iban = self::assert($iban);
        return new self($iban);
    }

    public function __construct(string $iban)
    {
        $this->initialize($iban);
    }


    /**
     * @param string $iban
     * @return DNI
     * @throws \Assert\AssertionFailedException
     */
    private function initialize(string $iban): self
    {
        preg_match(Validator\IbanValidator::IBAN_REGEX_PATTERN, $iban, $matches);

        $this->setIban($matches[1]);
        $this->setCcc($matches[4]);
        $this->setEntity($matches[5]);
        $this->setOffice($matches[6]);
        $this->setDigitOfControl($matches[7]);
        $this->setAccount($matches[8]);

        return $this;
    }

    /**
     * @return string
     */
    public function getIban(): string
    {
        return $this->iban;
    }

    /**
     * @param string $iban
     * @return Iban
     */
    public function setIban(string $iban): self
    {
        $this->iban = $iban;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getCcc()
    {
        return $this->ccc;
    }

    /**
     * @param mixed $ccc
     * @return Iban
     */
    private function setCcc($ccc): self
    {
        $this->ccc = $ccc;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getEntity()
    {
        return $this->entity;
    }

    /**
     * @param mixed $entity
     * @return Iban
     */
    private function setEntity($entity): self
    {
        $this->entity = $entity;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getOffice()
    {
        return $this->office;
    }

    /**
     * @param mixed $office
     * @return Iban
     */
    private function setOffice($office): self
    {
        $this->office = $office;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getDigitOfControl()
    {
        return $this->digitOfControl;
    }

    /**
     * @param mixed $digitOfControl
     * @return Iban
     */
    private function setDigitOfControl($digitOfControl): self
    {
        $this->digitOfControl = $digitOfControl;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param mixed $account
     * @return Iban
     */
    private function setAccount($account): self
    {
        $this->account = $account;
        return $this;
    }

    public function getElectronicFormat(): string
    {
        return sprintf('%s%s', ...[
            $this->getIban(),
            $this->getCcc()
        ]);
    }

    public function getPrintedFormat(): string
    {
        $code = $this->getElectronicFormat();
        $pieces = str_split($code, 4);

        return implode(' ', $pieces);
    }

    public function __toString(): string
    {
        return $this->getPrintedFormat();
    }

}

