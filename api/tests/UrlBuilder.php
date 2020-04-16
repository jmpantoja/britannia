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

namespace Britannia\Tests;


final class UrlBuilder
{
   public const UNIQID = 'form_id';

    private $base;

    private $query = [];

    public static function make(string $url, array $params = []): self
    {
        return new self($url, $params);
    }

    private function __construct(string $url, array $params = [])
    {
        $this->base = sprintf($url, ...$params);
    }

    public function uniqId(): self
    {
        $this->query = array_merge($this->query, [
            'uniqid' => self::UNIQID
        ]);
        return $this;
    }

    public function query(array $query): self
    {
        $this->query = array_merge($this->query, $query);
        return $this;
    }


    public function build(): string
    {
        if (empty($this->query)) {
            return $this->base;
        }

        return sprintf('%s?%s', $this->base, http_build_query($this->query));

    }

}

