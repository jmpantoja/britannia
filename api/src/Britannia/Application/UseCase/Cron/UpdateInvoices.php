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

namespace Britannia\Application\UseCase\Cron;


use Carbon\CarbonImmutable;

final class UpdateInvoices
{

    /**
     * @var CarbonImmutable
     */
    private CarbonImmutable $date;



    public static function make(): self
    {
        $date = CarbonImmutable::today();
        return new self($date);
    }

    private function __construct(CarbonImmutable $date)
    {
        $this->date = $date;
    }

    /**
     * @return CarbonImmutable
     */
    public function date(): CarbonImmutable
    {
        return $this->date;
    }

}
