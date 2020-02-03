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

namespace Britannia\Infraestructure\Symfony\Controller\Admin\Mark;

use Sonata\AdminBundle\Admin\AdminInterface;

final class ActionsRegistry
{
    /**
     * @var array
     */
    private array $formTheme;


    /**
     * @var UpdateMarksAction
     */
    private UpdateMarksAction $updateMarksAction;
    /**
     * @var AddSkillAction
     */
    private AddSkillAction $addSkillAction;
    /**
     * @var RemoveSkillAction
     */
    private RemoveSkillAction $removeSkillAction;


    public function __construct(UpdateMarksAction $updateMarksAction,
                                AddSkillAction $addSkillAction,
                                RemoveSkillAction $removeSkillAction)
    {
        $this->updateMarksAction = $updateMarksAction;
        $this->addSkillAction = $addSkillAction;
        $this->removeSkillAction = $removeSkillAction;
    }

    public function initialize(AdminInterface $admin): self
    {
        $this->formTheme = $admin->getFormTheme();
        return $this;
    }

    public function updateMarksAction(): UpdateMarksAction
    {
        return $this->updateMarksAction->initialize($this->formTheme);
    }

    public function addSkillAction()
    {
        return $this->addSkillAction->initialize($this->formTheme);
    }

    /**
     * @return RemoveSkillAction
     */
    public function removeSkillAction(): RemoveSkillAction
    {
        return $this->removeSkillAction->initialize($this->formTheme);
    }


}
