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
use Britannia\Domain\VO\Mark\TypeOfTerm;
use Britannia\Infraestructure\Symfony\Form\Type\Mark\TermHasMarksType;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

final class MarkForm extends AdminForm
{

    public function configure(Course $course)
    {

        if (!$course->numOfUnits() === 0) {
            $message = sprintf('no se puede editar las calificaciones de un curso sin unidades definidas');
            throw new NotFoundHttpException($message);
        }

        $terms = $this->calculeUnitsByTerm($course);

        foreach ($terms as $key => $units) {
            $name = $this->tabName($key);
            $this->tab($name);
            $this->group($name)
                ->add($key, TermHasMarksType::class, [
                    'by_reference' => true,
                    'mapped' => false,
                    'label' => $key,
                    'units' => $units,
                    'course' => $course,
                ]);

        }
    }

    private function calculeUnitsByTerm(Course $course): array
    {
        $data = [];
        $units = $course->units();

        foreach ($units as $unit) {
            $term = $unit->term()->getName();
            $data[$term] ??= [];
            $data[$term][] = $unit;
        }

        return $data;
    }

    private function tabName(string $key): string
    {
        return TypeOfTerm::byName($key)->getValue();
    }
}
