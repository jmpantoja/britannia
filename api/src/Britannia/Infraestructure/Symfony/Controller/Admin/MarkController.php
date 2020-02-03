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

namespace Britannia\Infraestructure\Symfony\Controller\Admin;


use Britannia\Infraestructure\Symfony\Controller\Admin\Mark\ActionsRegistry;
use Britannia\Infraestructure\Symfony\Controller\Admin\Mark\MarkAction;
use PlanB\DDDBundle\Sonata\ModelManager;
use Sonata\AdminBundle\Controller\CRUDController;

final class MarkController extends CRUDController
{
    /**
     * @var ModelManager
     */
    private ModelManager $manager;
    /**
     * @var ActionsRegistry
     */
    private $actionsRegistry;

    public function __construct(ActionsRegistry $actionsRegistry, ModelManager $manager)
    {
        $this->actionsRegistry = $actionsRegistry;
        $this->manager = $manager;
    }

    /**
     * @return ActionsRegistry
     */
    public function actionsRegistry(): ActionsRegistry
    {
        return $this->actionsRegistry->initialize($this->admin);
    }


    public function marksAction()
    {
        $updateMarkAction = $this->actionsRegistry()
            ->updateMarksAction();

        return $this->buildResponse($updateMarkAction);
    }

    public function addSkillAction()
    {
        $addSkillAction = $this->actionsRegistry()
            ->addSkillAction();

        return $this->buildResponse($addSkillAction);
    }

    public function removeSkillAction()
    {
        $removeSkillAction = $this->actionsRegistry()
            ->removeSkillAction();

        return $this->buildResponse($removeSkillAction);
    }

    private function buildResponse(MarkAction $markAction)
    {
        $params = $markAction->handle();
        return $this->renderWithExtraParams('admin/mark/ajax_form.html.twig', $params);
    }

}
