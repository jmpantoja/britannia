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


use Britannia\Domain\Entity\Assessment\TermList;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Assessment\TermDefinition;
use Britannia\Domain\VO\Assessment\TermName;
use Britannia\Infraestructure\Symfony\Form\Type\Assessment\TermListType;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDDBundle\Sonata\ModelManager;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\FormView;

final class MarkController extends CRUDController
{
    /**
     * @var ModelManager
     */
    private ModelManager $manager;

    public function __construct(ModelManager $manager)
    {
        $this->manager = $manager;
    }


    public function marksAction()
    {
        $courseId = $this->getRequest()->request->get('courseId');
        $termName = $this->getRequest()->request->get('termName');
        $unitsWeight = $this->getRequest()->request->get('unitsWeight');
        $numOfUnits = $this->getRequest()->request->get('numOfUnits');


        $course = $this->manager->find(Course::class, $courseId);
        $terms = $this->organizeByTermName($course);

        $input = $terms[$termName];
        $termList = TermList::collect($input);


        $termDefinition = TermDefinition::make(...[
            TermName::byName($termName),
            Percent::make((int)$unitsWeight),
            (int)$numOfUnits
        ]);

        $termList->updateDefintion($termDefinition);

        foreach ($termList as $term){
            $this->manager->update($term);
        }

        /** @var FormFactory $factory */
        $factory = $this->get('form.factory');

        $builder = $factory->createNamedBuilder($this->admin->getUniqid(), FormType::class)
            ->add($termName, TermListType::class, [
                'mapped' => false,
                'label' => false,
                'data' => $termList
            ]);

        $form = $builder->getForm();

        $formView = $form->createView();
        $this->setFormTheme($formView, $this->admin->getFormTheme());

        return $this->renderWithExtraParams('admin/mark/marks_ajax.html.twig', [
            'form' => $formView,
            'units' => $termList->units(),
            'skills' => $termList->skills()
        ]);
    }

    /**
     * Sets the admin form theme to form view. Used for compatibility between Symfony versions.
     */
    private function setFormTheme(FormView $formView, array $theme = null): void
    {
        $twig = $this->get('twig');

        $twig->getRuntime(FormRenderer::class)->setTheme($formView, $theme);
    }

    /**
     * @param Course $course
     * @return array
     */
    private function organizeByTermName(Course $course): array
    {
        $data = [];
        foreach (TermName::all() as $termName) {
            $data[$termName->getName()] = [];
        }

        foreach ($course->terms() as $term) {
            $key = (string)$term->termName();
            $data[$key][] = $term;
        }

        return array_filter($data);
    }
}
