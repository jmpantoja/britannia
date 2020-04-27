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

namespace Britannia\Infraestructure\Symfony\Twig\Extensions;


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\Entity\Course\CourseAssessmentInterface;
use Britannia\Domain\VO\Course\TimeTable\DayOfWeek;
use Britannia\Infraestructure\Symfony\Admin\AdminFilterableInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Twig\TwigTest;

final class SonataExtension extends AbstractExtension
{
    public function getTests()
    {
        return [
            new TwigTest('evaluable', [$this, 'is_evaluable']),
        ];
    }


    public function getFunctions()
    {
        return [
            new TwigFunction('sonata_has_filters', [$this, 'hasFilters']),
            new TwigFunction('day_of_week', function (string $dayName): DayOfWeek {
                return DayOfWeek::byName($dayName);
            }),
        ];
    }

    public function getFilters()
    {
        return [
            // the logic of this filter is now implemented in a different class
            new TwigFilter('unset', [$this, 'unset']),
        ];
    }

    public function hasFilters(AbstractAdmin $admin): bool
    {
        if (!($admin instanceof AdminFilterableInterface)) {
            return true;
        }

        $filters = $admin->getFilterParameters();
        $default = $admin->datagridValues();

        $filters = $this->normalize($filters);
        $default = $this->normalize($default);

        return !($filters == $default);
    }

    private function normalize(array $filters)
    {
        unset($filters['_sort_order']);
        unset($filters['_sort_by']);
        unset($filters['_page']);
        unset($filters['_per_page']);

        $data = [];
        foreach ($filters as $name => $value) {
            $data[$name] = $value['value'] ?? null;
        }


        return array_filter($data);
    }

    public function unset(array $value, string $key)
    {
        unset($value[$key]);
        return $value;
    }

    public function is_evaluable(Course $course)
    {
        return $course instanceof CourseAssessmentInterface;
    }

}

