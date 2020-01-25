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


use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Assessment\CourseTerm;
use Britannia\Domain\VO\Assessment\TermName;
use Britannia\Infraestructure\Symfony\Form\Type\Assessment\CommentListType;
use Britannia\Infraestructure\Symfony\Form\Type\Assessment\DiagnosticExamListType;
use Britannia\Infraestructure\Symfony\Form\Type\Assessment\FinalExamListType;
use Britannia\Infraestructure\Symfony\Form\Type\Assessment\OtherSkillExamType;
use Britannia\Infraestructure\Symfony\Form\Type\Assessment\TermListType;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;

final class MarkForm extends AdminForm
{

    public function configure(Course $course)
    {
        $data = $this->organizeByTermName($course);

        $otherSkills = $course->otherSkills();

        $this->tab('Prueba de nivel');
        $this->group('Prueba de nivel')
            ->add('diagnostic', DiagnosticExamListType::class, [
                'mapped' => false,
                'label' => false,
                'data' => $course,
            ]);

        foreach ($data as $key => $courseTerm) {

            $this->tab($courseTerm->name());
            $this->group('Unidades')
                ->add($key, TermListType::class, [
                    'allow_extra_fields' => true,
                    'mapped' => false,
                    'label' => false,
                    'data' => $courseTerm,
                ]);

            $this->group('Comentarios')
                ->add($key . '-c', CommentListType::class, [
                    'mapped' => false,
                    'label' => false,
                    'data' => $courseTerm
                ]);

            foreach ($otherSkills as $skill) {
                $name = sprintf('%s-%s', $key, $skill->getName());
                $this->group($skill->getValue())
                    ->add($name, OtherSkillExamType::class, [
                        'mapped' => false,
                        'label' => false,
                        'data' => $courseTerm,
                        'skill' => $skill
                    ]);
            }
        }


        $this->tab('Examen final');
        $this->group('Examen final')
            ->add('final', FinalExamListType::class, [
                'mapped' => false,
                'label' => false,
                'data' => $course,
            ]);

    }

    /**
     * @param Course $course
     * @return CourseTerm []
     */
    private function organizeByTermName(Course $course): array
    {
        $total = $course->numOfTerms();

        for ($num = 0; $num < $total; $num++) {
            $termName = TermName::byOrdinal($num);
            $key = (string)$termName;

            $list[$key] = CourseTerm::make($course, $termName);
        }

        return $list;
    }


}
