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
use PlanB\DDD\Domain\VO\Price;

final class SettingDto extends Dto
{
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
