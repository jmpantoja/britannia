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


use Britannia\Infraestructure\Symfony\Admin\Course\CourseDatagrid;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

abstract class AdminTools
{
    abstract public function dataGrid(ListMapper $listMapper): CourseDatagrid;
    abstract public function form(FormMapper $formMapper): AdminForm;
}
