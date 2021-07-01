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


if (!function_exists('normalize_key')) {
    function normalize_key(string $className): string
    {
        $className = str_replace('\\', '-', $className);
        return strtolower($className);
    }
}

