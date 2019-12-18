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


use Britannia\Domain\Entity\Course\Borrame\Borrame;
use Britannia\Infraestructure\Symfony\Form\Report\CourseInfo\CourseInformationType;
use Cocur\Slugify\Slugify;
use Knp\Snappy\Pdf;
use League\Tactician\CommandBus;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class CourseController extends CRUDController
{

    /**
     * @var CommandBus
     */
    private $commandBus;
    /**
     * @var Pdf
     */
    private $pdfGenerator;

    public function __construct(CommandBus $commandBus, Pdf $pdfGenerator)
    {
        $this->commandBus = $commandBus;
        $this->pdfGenerator = $pdfGenerator;
    }

    public function reportInfoAction($id)
    {
        $course = $this->getCourse($id);
        $form = $this->createForm(CourseInformationType::class, null, [
            'course' => $course,
        ]);

        $template = 'admin/report/course_information.html.twig';

        $name = Slugify::create()
            ->slugify($course->getName());

        $filename = sprintf('info-%s-%s.pdf', $name, date('U'));
        return $this->handleForm($form, $template, $filename);
    }

    /**
     * @param $id
     * @return null|object
     */
    private function getCourse($id)
    {
        $course = $this->admin->getSubject();
        if (!$course) {
            throw new NotFoundHttpException(sprintf('unable to find the course with id: %s', $id));
        }
        return $course;
    }

    /**
     * @param Form $form
     * @param string $template
     * @param string $filename
     * @return Response
     */
    private function handleForm(Form $form, string $template, string $filename): Response
    {
        $request = $this->getRequest();
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $isFormValid = $form->isValid();
            if ($isFormValid) {
                $command = $form->getData();
                $data = $this->commandBus->handle($command);
                return $this->generatePdf($data, $template, $filename);
            } else {
                $this->addFlash(
                    'sonata_flash_error',
                    'Los datos no son correctos'
                );
            }
        }

        $formView = $form->createView();
        $twig = $this->get('twig');
        $twig->getRuntime(FormRenderer::class)->setTheme($formView, $this->admin->getFormTheme());

        return $this->renderWithExtraParams('admin/report/generator.html.twig', [
            'form' => $formView
        ]);
    }

    /**
     * @param $data
     * @param string $template
     * @param string $filename
     * @return Response
     */
    private function generatePdf(array $data, string $template, string $filename): Response
    {
        $isPdf = true;

        $data['title'] = $data['title'] ?? $filename;
        $data['asset_base'] = $isPdf ? 'http://api' : '';
        $html = $this->renderView($template, $data);

        return !$isPdf ?
            new Response($html) :
            new Response(
                $this->pdfGenerator->getOutputFromHtml($html, [
                    'page-size' => 'A5'
                ]),
                200,
                [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => sprintf('attachment; filename="%s"', $filename),
                ]
            );
    }

    public function reportMarkAction($id)
    {
        $course = $this->getCourse($id);

        dump(__METHOD__);
        dump('estamos trabajando en ello');
        dump($course);
        die();
    }

    public function reportCertificateAction($id)
    {
        $course = $this->getCourse($id);

        dump(__METHOD__);
        dump('estamos trabajando en ello');
        dump($course);
        die();
    }

}

