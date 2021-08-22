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


use Britannia\Domain\VO\Course\Pass\PassPriceList;
use Britannia\Domain\VO\Discount\FamilyDiscountList;
use Britannia\Domain\VO\Discount\JobStatusDiscountList;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Email;
use PlanB\DDD\Domain\VO\PhoneNumber;
use PlanB\DDD\Domain\VO\Price;

class Setting
{
    /**
     * @var SettingId
     */
    private $id;

    /** @var PhoneNumber|null */
    private $phone;

    /** @var PhoneNumber|null */
    private $mobile;

    /** @var string */
    private $facebook;

    /** @var string */
    private $twitter;

    /** @var string */
    private $instagram;

    /** @var string */
    private $flickr;

    /** @var Email */
    private $mail;

    /** @var string */
    private $web;

    private $morning;

    private $afternoon;

    private $sepa_presenter_id;
    private $sepa_presenter_name;
    private $sepa_bbva_office;

    private $sepa_creditor_id;
    private $sepa_creditor_name;
    private $sepa_creditor_iban;

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

        $this->phone = $dto->phone;
        $this->mobile = $dto->mobile;

        $this->facebook = $dto->facebook;
        $this->twitter = $dto->twitter;
        $this->instagram = $dto->instagram;
        $this->flickr = $dto->flickr;

        $this->mail = $dto->mail;
        $this->web = $dto->web;

        $this->morning = $dto->morning;
        $this->afternoon = $dto->afternoon;

        $this->sepa_presenter_id = $dto->sepa_presenter_id;
        $this->sepa_presenter_name = $dto->sepa_presenter_name;
        $this->sepa_bbva_office = $dto->sepa_bbva_office;

        $this->sepa_creditor_id = $dto->sepa_creditor_id;
        $this->sepa_creditor_name = $dto->sepa_creditor_name;
        $this->sepa_creditor_iban = $dto->sepa_creditor_iban;

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
     * @return PhoneNumber|null
     */
    public function phone(): ?PhoneNumber
    {
        return $this->phone;
    }

    /**
     * @return PhoneNumber|null
     */
    public function mobile(): ?PhoneNumber
    {
        return $this->mobile;
    }

    /**
     * @return string
     */
    public function facebook(): string
    {
        return $this->facebook;
    }

    /**
     * @return string
     */
    public function twitter(): string
    {
        return $this->twitter;
    }

    /**
     * @return Email
     */
    public function mail(): string
    {
        return (string)$this->mail;
    }

    /**
     * @return string
     */
    public function web(): string
    {
        return $this->web;
    }

    /**
     * @return mixed
     */
    public function morning(): array
    {
        return [
            'start' => CarbonImmutable::make($this->morning['start']),
            'end' => CarbonImmutable::make($this->morning['end']),
        ];

    }

    /**
     * @return mixed
     */
    public function afternoon()
    {
        return [
            'start' => CarbonImmutable::make($this->afternoon['start']),
            'end' => CarbonImmutable::make($this->afternoon['end']),
        ];

    }

    /**
     * @return string|null
     */
    public function sepaPresenterId(): ?string
    {
        return $this->sepa_presenter_id;
    }

    /**
     * @return string|null
     */
    public function sepaPresenterName(): ?string
    {
        return $this->sepa_presenter_name;
    }

    /**
     * @return string|null
     */
    public function sepaBbvaOffice(): ?string
    {
        return $this->sepa_bbva_office;
    }

    /**
     * @return string|null
     */
    public function sepaCreditorId(): ?string
    {
        return $this->sepa_creditor_id;
    }

    /**
     * @return mixed
     */
    public function sepaCreditorName(): ?string
    {
        return $this->sepa_creditor_name;
    }

    /**
     * @return string|null
     */
    public function sepaCreditorIban(): ?string
    {
        return $this->sepa_creditor_iban;
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

    public function __toString()
    {
        return 'Settings';
    }
}
