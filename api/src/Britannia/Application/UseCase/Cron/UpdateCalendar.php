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

class UpdateCalendar
{
    /**
     * @var CarbonImmutable
     */
    private $date;

    private function __construct(CarbonImmutable $date)
    {
        $this->date = $date->setTime(0, 0);
    }

    public static function make(): self
    {
        return new self(CarbonImmutable::now());
    }

    /**
     * @return int
     */
    public function year(): int
    {
        return $this->date->format('Y') * 1;
    }
    
}
