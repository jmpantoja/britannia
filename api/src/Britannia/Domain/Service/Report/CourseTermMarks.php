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

final class CourseTermMarks extends HtmlBasedPdfReport
{

    /**
     * @param Term[] $terms
     * @return CourseTermMarks
     */
    public static function make(Term $term): self
    {
        $name = (string)$term->student();
        $params = [
            'term' => $term
        ];

        $options = [
            'page-size' => 'A4',
            'orientation' => 'Landscape',
        ];

        return new self($name, $params, $options);
    }

}
