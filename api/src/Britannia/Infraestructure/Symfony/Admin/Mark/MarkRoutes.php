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

namespace Britannia\Infraestructure\Symfony\Admin\Mark;


use Britannia\Infraestructure\Symfony\Admin\Course\CourseAdmin;
use PlanB\DDDBundle\Sonata\Admin\AdminRoutes;

final class MarkRoutes extends AdminRoutes
{

    private CourseAdmin $original;

    /**
     * @return CourseAdmin
     */
    public function original(): CourseAdmin
    {
        return $this->original;
    }

    /**
     * @param CourseAdmin $original
     * @return MarkRoutes
     */
    public function setOriginal(CourseAdmin $original): MarkRoutes
    {
        $this->original = $original;
        return $this;
    }

    protected function configure(): void
    {

        $this->clearExcept(['edit']);

        $this->add('marks', '/ajax/marks');
        $this->add('add-skill', '/ajax/add/skill');
        $this->add('remove-skill', '/ajax/remove/skill');
    }
}
