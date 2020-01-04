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

namespace PlanB\DDDBundle\Sonata\Admin;


use Britannia\Infraestructure\Symfony\Admin\Academy\AcademyFilters;
use Sonata\AdminBundle\Datagrid\DatagridMapper;

abstract class AdminFilter
{
    /**
     * @var DatagridMapper
     */
    private DatagridMapper $filterMapper;


    public static function make(DatagridMapper $filterMapper): ?AdminFilter
    {
        return new static($filterMapper);
    }

    protected function __construct(DatagridMapper $filterMapper)
    {
        $this->filterMapper = $filterMapper;
    }

    public function add(
        $name,
        $type = null,
        array $filterOptions = [],
        $fieldType = null,
        $fieldOptions = null,
        array $fieldDescriptionOptions = []
    ): self
    {
        $this->filterMapper->add($name, $type, $filterOptions, $fieldType, $fieldOptions, $fieldDescriptionOptions);
        return $this;
    }
}
