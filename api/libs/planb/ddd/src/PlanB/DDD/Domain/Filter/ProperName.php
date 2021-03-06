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

namespace PlanB\DDD\Domain\Filter;



use Laminas\Filter\FilterInterface;

class ProperName implements FilterInterface
{

    /**
     * @var int
     */
    private $limit;

    public function __construct(int $limit = 2)
    {

        $this->limit = $limit;
    }

    public function filter($value)
    {
        if (is_null($value)) {
            return null;
        }

        $value = trim($value);
        $pieces = preg_split('/\s+/', $value);

        $pieces = array_map(function (string $word) {
            return $this->normalize($word);
        }, $pieces);

        if (count($pieces) === 0) {
            return '';
        }

        $pieces[0] = mb_convert_case($pieces[0], MB_CASE_TITLE);
        return implode(' ', $pieces);
    }


    private function normalize(string $word)
    {
        $word = mb_strtolower($word);
        if (mb_strlen($word) <= $this->limit) {
            return $word;
        }

        return mb_convert_case($word, MB_CASE_TITLE);
    }
}
