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
use PlanB\DDD\Domain\Model\Dto;
use PlanB\DDD\Domain\VO\PhoneNumber;
use PlanB\DDD\Domain\VO\Price;

final class SettingDto extends Dto
{
    public ?PhoneNumber $phone;
    public ?PhoneNumber $mobile;

    public ?string $facebook;
    public ?string $twitter;
    public ?string $mail;
    public ?string $web;

    public ?array $morning;
    public ?array $afternoon;

    public ?string $sepa_presenter_id;
    public ?string $sepa_presenter_name;
    public ?string $sepa_bbva_office;
    public ?string $sepa_creditor_id;
    public ?string $sepa_creditor_name;
    public ?string $sepa_creditor_iban;

    public ?Price $enrollmentPayment = null;
    public ?Price $monthlyPayment = null;
    public ?PassPriceList $passPriceList = null;

    public ?FamilyDiscountList $familyDiscount = null;
    public ?JobStatusDiscountList $jobStatusDiscount = null;
    public string $informationClause = '';
    public string $consentClause = '';
    public string $generalConsiderationsClause = '';
    public string $cashPaymentsClause = '';
    public string $personalDataConsentClause = '';
    public string $faqs = '';
}
