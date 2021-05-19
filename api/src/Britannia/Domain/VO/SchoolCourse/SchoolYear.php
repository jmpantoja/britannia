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

namespace Britannia\Domain\VO\SchoolCourse;


use Carbon\CarbonImmutable;

final class SchoolYear
{
    private int $from;
    private int $to;

    public static function make(CarbonImmutable $date = null): self
    {
        if (is_null($date)) {
            $date = CarbonImmutable::today();
        }

        return new self($date);
    }

    private function __construct(CarbonImmutable $date)
    {
        $limit = CarbonImmutable::create($date->year, 8, 15);

        $this->from = $date->year;
        if ($date->lessThan($limit)) {
            $this->from--;
        }

        $this->to = $this->from + 1;
    }

    /**
     * @return int
     */
    public function from(): int
    {
        return $this->from;
    }

    /**
     * @return int
     */
    public function to(): int
    {
        return $this->to;
    }

    public function name(): string
    {
        return sprintf('%s/%s', ...[
            $this->from,
            $this->to
        ]);
    }
}
