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

namespace Britannia\Application\UseCase\Invoice;


use Carbon\CarbonImmutable;

final class GenerateSepaXlsx
{

    /**
     * @var CarbonImmutable
     */
    private CarbonImmutable $month;

    public static function byMonth(CarbonImmutable $month): self
    {
        return new self($month);
    }

    /**
     * GenerateSepaXlsx constructor.
     * @param CarbonImmutable $month
     */
    private function __construct(CarbonImmutable $month)
    {
        $this->month = $month;
    }

    /**
     * @return CarbonImmutable
     */
    public function month(): CarbonImmutable
    {
        return $this->month;
    }
}
