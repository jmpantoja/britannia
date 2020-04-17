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


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Assessment\CourseTerm;
use Britannia\Domain\VO\Assessment\Skill;
use Britannia\Domain\VO\Assessment\TermDefinition;
use Britannia\Domain\VO\Assessment\TermName;
use Carbon\CarbonImmutable;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDDBundle\Sonata\ModelManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

final class RequestParameters
{
    /**
     * @var Request|null
     */
    private Request $request;
    /**
     * @var ModelManager
     */
    private ModelManager $manager;

    public function __construct(RequestStack $requestStack, ModelManager $manager)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->manager = $manager;
    }


    public function courseTerm(): CourseTerm
    {
        $course = $this->course();
        $termName = $this->termName();

        return CourseTerm::make($course, $termName);
    }

    /**
     * @return object|void|null
     */
    public function course(): Course
    {
        $courseId = $this->request->get('courseId');
        return $this->manager->find(Course::class, $courseId);
    }

    /**
     * @return TermName|mixed
     */
    public function termName(): TermName
    {
        $termName = $this->request->get('termName');
        return TermName::byName($termName);
    }

    /**
     * @return mixed
     */
    public function unitsWeight(): Percent
    {
        $unitsWeight = (int)$this->request->get('unitsWeight');
        return Percent::make($unitsWeight);
    }

    /**
     * @return mixed
     */
    public function numOfUnits(): int
    {
        return (int)$this->request->get('numOfUnits');
    }

    /**
     * @return CarbonImmutable|false|mixed
     */
    public function date(): CarbonImmutable
    {
        $date = $this->request->get('date');
        return string_to_date($date, -1,-1,'d m Y');
    }

    /**
     * @return Skill|mixed
     */
    public function skill(): Skill
    {
        $skill = $this->request->get('skill');
        return Skill::byName($skill);
    }

    public function termDefinition(): TermDefinition
    {
        return TermDefinition::make(...[
            $this->termName(),
            $this->unitsWeight(),
            $this->numOfUnits()
        ]);
    }

    public function uniqId(): string
    {
        return $this->request->get('uniqId');
    }

}
