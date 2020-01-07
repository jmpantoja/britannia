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


use Britannia\Domain\Entity\Assessment\TermList;
use Britannia\Domain\Entity\Course\Course;
use Britannia\Domain\VO\Mark\TermName;
use Britannia\Infraestructure\Symfony\Form\Type\Assessment\SetOfSkillsType;
use Britannia\Infraestructure\Symfony\Form\Type\Assessment\TermListType;
use PlanB\DDD\Domain\VO\Percent;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use PlanB\DDDBundle\Symfony\Form\Type\PercentageType;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class MarkForm extends AdminForm
{

    public function configure(Course $course)
    {
      //  $this->assertNumOfUnits($course);

        $data = $this->organizeByTermName($course);

        foreach ($data as $key => $terms) {
            $name = $this->tabName($key);
            $this->tab($name);
            $this->group($name)
                ->add($key, TermListType::class, [
                    'mapped' => false,
                    'data' => TermList::collect($terms)
                ]);
        }

    }

//    /**
//     * @param Course $course
//     */
//    private function assertNumOfUnits(Course $course): void
//    {
//        if ($course->numOfUnits() === 0) {
//            $message = sprintf('no se puede editar las calificaciones de un curso sin unidades definidas');
//            throw new NotFoundHttpException($message);
//        }
//    }

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

    private function tabName(string $term): string
    {
        return TermName::byName($term)->getValue();
    }

}
