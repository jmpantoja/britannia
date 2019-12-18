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

namespace Britannia\Infraestructure\Symfony\Admin\Course;


use PlanB\DDDBundle\Sonata\Admin\AdminTools;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

final class CourseTools extends AdminTools
{

    /**
     * @var CourseMapper
     */
    private CourseMapper $mapper;

    public function __construct(CourseMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function dataGrid(ListMapper $listMapper): CourseDatagrid
    {
        return CourseDatagrid::make($listMapper);
    }

    public function form(FormMapper $formMapper): CourseForm
    {
        return CourseForm::make($formMapper)
            ->setDataMapper($this->mapper);
    }
}
