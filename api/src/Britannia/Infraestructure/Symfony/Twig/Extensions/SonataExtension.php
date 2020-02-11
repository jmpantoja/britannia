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


use Britannia\Infraestructure\Symfony\Admin\AdminFilterableInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

final class SonataExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('sonata_has_filters', [$this, 'hasFilters']),
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
}

