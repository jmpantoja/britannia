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


use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\DataMapperInterface;

abstract class AdminForm
{
    private $mapper;
    private $isOpened;

    public static function make(FormMapper $mapper): self
    {
        return new static($mapper);
    }


    protected function __construct(FormMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function admin(): AbstractAdmin
    {
        return $this->mapper->getAdmin();
    }

    public function setDataMapper(DataMapperInterface $dataMapper): self
    {
        $this->mapper->getFormBuilder()->setDataMapper($dataMapper);
        return $this;
    }

    public function dataMapper(): ?DataMapperInterface
    {
        return $this->mapper->getFormBuilder()->getDataMapper();
    }


    protected function tab(string $name): self
    {
        $this->endTabs();
        $this->mapper->with($name, ['tab' => true]);
        return $this;

    }

    protected function group(string $name, array $options = []): self
    {
        $this->endGroups();
        $this->mapper->with($name, $options);
        return $this;
    }

    protected function add($name, $type = null, array $options = [], array $fieldDescriptionOptions = []): self
    {
        $this->mapper->add($name, $type, $options, $fieldDescriptionOptions);
        return $this;
    }

    protected function endTabs(): void
    {
        while ($this->mapper->hasOpenTab()) {
            $this->mapper->end();
        }
        $this->isOpened = false;
    }

    protected function endGroups(): void
    {
        if ($this->isOpened) {
            $this->mapper->end();
        }
        $this->isOpened = true;
    }


}
