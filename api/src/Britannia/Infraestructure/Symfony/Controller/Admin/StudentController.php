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


use Britannia\Infraestructure\Symfony\Controller\Admin\Tutor\TutorFormService;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\HttpFoundation\Request;

class StudentController extends CRUDController
{
    /**
     * @var TutorFormService
     */
    private TutorFormService $tutorFormService;

    /**
     * StudentController constructor.
     */
    public function __construct(TutorFormService $tutorFormService)
    {
        $this->tutorFormService = $tutorFormService;
    }

    public function tutorFormAction(Request $request)
    {
        $id = $request->get('id');
        $uniqId = $request->get('uniqId');
        $name = $request->get('name');

        $params = $this->tutorFormService->buildResponse($id, $uniqId, $name, $this->admin->getFormTheme());

        return $this->renderWithExtraParams('admin/mark/ajax_form.html.twig', $params);
    }
}
