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


use Britannia\Domain\Entity\Assessment\Term;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Assessment\CourseTerm;
use Britannia\Domain\VO\Assessment\Skill;
use Britannia\Domain\VO\Assessment\TermDefinition;
use Britannia\Domain\VO\Assessment\TermName;
use Britannia\Infraestructure\Symfony\Form\Type\Assessment\OtherSkillExamType;
use Britannia\Infraestructure\Symfony\Form\Type\Assessment\TermListType;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDDBundle\Sonata\ModelManager;
use Sonata\AdminBundle\Controller\CRUDController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\FormFactory;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\Response;

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
        $termName = $this->getTermName();

        $unitsWeight = $this->getUnitsWeight();
        $numOfUnits = $this->getNumOfUnits();

        $courseTerm = $this->getCourseTerm();

        $termDefinition = TermDefinition::make(...[
            $termName,
            $unitsWeight,
            $numOfUnits
        ]);

        $courseTerm->updateDefintion($termDefinition);

        $courseTerm->termList()
            ->values()
            ->each(fn(Term $term) => $this->manager->update($term));

        $name = $termName->getName();
        $paramsForm = ['data' => $courseTerm];
        $paramsView = ['units' => $courseTerm->units(), 'skills' => $courseTerm->setOfSkills()];

        return $this->buildResponse($name, TermListType::class, $paramsForm, $paramsView);
    }

    public function addSkillAction()
    {
        $course = $this->getCourse();
        $termName = $this->getTermName();

        $date = $this->getDate();
        $skill = $this->getSkill();

        $courseTerm = $this->getCourseTerm();

        $courseTerm->addSkill($date, $skill);

        $this->manager->update($course);

        $name = sprintf('%s-%s', $termName, $skill);
        $paramsForm = ['data' => $courseTerm, 'skill' => $skill];

        return $this->buildResponse($name, OtherSkillExamType::class, $paramsForm);
    }

    public function removeSkillAction()
    {
        $course = $this->getCourse();
        $termName = $this->getTermName();
        $date = $this->getDate();
        $skill = $this->getSkill();

        $courseTerm = $this->getCourseTerm();

        $courseTerm->removeSkill($date, $skill);

        $this->manager->update($course);

        $name = sprintf('%s-%s', $termName, $skill);
        $paramsForm = ['data' => $courseTerm, 'skill' => $skill];

        return $this->buildResponse($name, OtherSkillExamType::class, $paramsForm);
    }


    private function buildResponse(string $name, string $type, array $paramsForm, array $paramsView = []): Response
    {
        /** @var FormFactory $factory */
        $factory = $this->get('form.factory');

        $defaults = [
            'mapped' => false,
            'label' => false
        ];
        $paramsForm = array_replace($defaults, $paramsForm);

        $builder = $factory->createNamedBuilder($this->admin->getUniqid(), FormType::class)
            ->add($name, $type, $paramsForm);

        $form = $builder->getForm();
        $formView = $form->createView();
        $this->setFormTheme($formView, $this->admin->getFormTheme());

        $paramsView = array_replace([
            'form' => $formView
        ], $paramsView);

        return $this->renderWithExtraParams('admin/mark/ajax_form.html.twig', $paramsView);
    }

    /**
     * Sets the admin form theme to form view. Used for compatibility between Symfony versions.
     */
    private function setFormTheme(FormView $formView, array $theme = null): void
    {
        $twig = $this->get('twig');

        $twig->getRuntime(FormRenderer::class)->setTheme($formView, $theme);
    }

    private function getCourseTerm(): CourseTerm
    {
        $course = $this->getCourse();
        $termName = $this->getTermName();

        return CourseTerm::make($course, $termName);
    }

    /**
     * @return object|void|null
     */
    private function getCourse(): Course
    {
        $courseId = $this->getRequest()->request->get('courseId');
        return $this->manager->find(Course::class, $courseId);
    }

    /**
     * @return TermName|mixed
     */
    private function getTermName(): TermName
    {
        $termName = $this->getRequest()->request->get('termName');
        return TermName::byName($termName);
    }

    /**
     * @return mixed
     */
    private function getUnitsWeight(): Percent
    {
        $unitsWeight = (int)$this->getRequest()->request->get('unitsWeight');
        return Percent::make($unitsWeight);
    }

    /**
     * @return mixed
     */
    private function getNumOfUnits(): int
    {
        return (int)$this->getRequest()->request->get('numOfUnits');
    }

    /**
     * @return CarbonImmutable|false|mixed
     */
    private function getDate(): CarbonImmutable
    {
        $date = $this->getRequest()->request->get('date');
        return CarbonImmutable::createFromLocaleFormat('d M. Y', 'es', $date);
    }

    /**
     * @return Skill|mixed
     */
    private function getSkill(): Skill
    {
        $skill = $this->getRequest()->request->get('skill');
        return Skill::byName($skill);
    }

}
