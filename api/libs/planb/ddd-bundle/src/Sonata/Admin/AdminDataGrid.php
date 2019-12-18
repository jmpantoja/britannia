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


use Sonata\AdminBundle\Datagrid\ListMapper;

abstract class AdminDataGrid
{
    /**
     * @var ListMapper
     */
    private $mapper;

    private function __construct(ListMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public static function make(ListMapper $mapper): self
    {
        return new static($mapper);
    }

    public function addIdentifier($name, $type = null, array $fieldDescriptionOptions = []): self
    {
        $this->mapper->addIdentifier($name, $type, $fieldDescriptionOptions);
        return $this;
    }

    public function add($name, $type = null, array $fieldDescriptionOptions = []): self
    {
        $this->mapper->add($name, $type, $fieldDescriptionOptions);
        return $this;
    }

}
