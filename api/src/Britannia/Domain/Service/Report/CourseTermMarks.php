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

namespace Britannia\Domain\Service\Report;


use Britannia\Domain\Entity\Assessment\Term;

final class CourseTermMarks extends Report
{
    /**
     * @var array
     */
    private array $params;

    /**
     * @var array
     */
    private array $options;

    /**
     * @var string
     */
    private string $title;


    /**
     * @param Term[] $terms
     * @return CourseTermMarks
     */
    public static function make(Term $term): self
    {
        return new self($term);
    }

    private function __construct(Term $term)
    {
        $this->params = [
            'term' => $term
        ];

        $this->title = (string)$term->student();
        $this->options = [
            'page-size' => 'A4',
            'orientation' => 'Landscape',
        ];
    }

    /**
     * @return array
     */
    public function params(): array
    {
        return $this->params;
    }

    /**
     * @return array
     */
    public function options(): array
    {
        return $this->options;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return $this->title;
    }
}
