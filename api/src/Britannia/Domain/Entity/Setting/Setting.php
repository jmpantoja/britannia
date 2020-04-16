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

namespace Britannia\Domain\Entity\Setting;


use Britannia\Domain\VO\Course\Pass\PassHours;
use Britannia\Domain\VO\Course\Pass\PassPriceList;
use Britannia\Domain\VO\Discount\FamilyDiscountList;
use Britannia\Domain\VO\Discount\JobStatusDiscountList;
use PlanB\DDD\Domain\VO\Price;

class Setting
{
    /**
     * @var SettingId
     */
    private $id;

    /**
     * @var Price|null
     */
    private $enrollmentPayment;
    /**
     * @var Price|null
     */
    private $monthlyPayment;

    /** @var PassPriceList|null */
    private $passPriceList;

    /**
     * @var JobStatusDiscountList
     */
    private $jobStatusDiscount;

    /**
     * @var FamilyDiscountList
     */
    private $familyDiscount;

    /**
     * @var string
     */
    private $informationClause = '';
    /**
     * @var string
     */
    private $consentClause = '';
    /**
     * @var string
     */
    private $generalConsiderationsClause = '';
    /**
     * @var string
     */
    private $cashPaymentsClause = '';
    /**
     * @var string
     */
    private $personalDataConsentClause = '';
    /**
     * @var string
     */
    private $faqs = '';


    public static function make(SettingDto $dto): self
    {
        return new self($dto);
    }

    private function __construct(SettingDto $dto)
    {
        $this->id = new SettingId();
        $this->update($dto);
    }

    public function update(SettingDto $dto): self
    {
        $this->enrollmentPayment = $dto->enrollmentPayment;
        $this->monthlyPayment = $dto->monthlyPayment;
        $this->passPriceList = $dto->passPriceList;

        $this->familyDiscount = $dto->familyDiscount;
        $this->jobStatusDiscount = $dto->jobStatusDiscount;

        $this->informationClause = $dto->informationClause;
        $this->consentClause = $dto->consentClause;
        $this->generalConsiderationsClause = $dto->generalConsiderationsClause;
        $this->cashPaymentsClause = $dto->cashPaymentsClause;
        $this->personalDataConsentClause = $dto->personalDataConsentClause;
        $this->faqs = $dto->faqs;

        return $this;
    }


    /**
     * @return SettingId
     */
    public function id(): SettingId
    {
        return $this->id;
    }

    /**
     * @return Price|null
     */
    public function enrollmentPayment(): ?Price
    {
        return $this->enrollmentPayment;
    }

    /**
     * @return Price|null
     */
    public function monthlyPayment(): ?Price
    {
        return $this->monthlyPayment;
    }

    /**
     * @return PassPriceList|null
     */
    public function passPriceList(): ?PassPriceList
    {
        return $this->passPriceList;
    }

    public function jobStatusDiscount(): ?JobStatusDiscountList
    {

        return $this->jobStatusDiscount ?? JobStatusDiscountList::make();
    }

    /**
     * @return mixed
     */
    public function familyDiscount(): ?FamilyDiscountList
    {
        return $this->familyDiscount;
    }

    /**
     * @return string
     */
    public function informationClause(): string
    {
        return $this->informationClause;
    }

    /**
     * @return string
     */
    public function consentClause(): string
    {
        return $this->consentClause;
    }

    /**
     * @return string
     */
    public function generalConsiderationsClause(): string
    {
        return $this->generalConsiderationsClause;
    }

    /**
     * @return string
     */
    public function cashPaymentsClause(): string
    {
        return $this->cashPaymentsClause;
    }

    /**
     * @return string
     */
    public function personalDataConsentClause(): string
    {
        return $this->personalDataConsentClause;
    }

    /**
     * @return string
     */
    public function faqs(): string
    {
        return $this->faqs;
    }

}
