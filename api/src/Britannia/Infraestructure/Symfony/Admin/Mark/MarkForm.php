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
use Britannia\Domain\VO\Assessment\TermName;
use Britannia\Infraestructure\Symfony\Form\Type\Assessment\TermListType;
use PlanB\DDDBundle\Sonata\Admin\AdminForm;

final class MarkForm extends AdminForm
{

    public function configure(Course $course)
    {
        $data = $this->organizeByTermName($course);

        $this->tab('Diagnostic Test');

        foreach ($data as $key => $terms) {
            $name = $this->tabName($key);
            $this->tab($name);
            $this->group('Unidades')
                ->add($key, TermListType::class, [
                    'mapped' => false,
                    'label' => false,
                    'data' => TermList::collect($terms)
                ]);

            $this->group('Comentarios')
                ->add($key . '-c', TermListType::class, [
                    'mapped' => false,
                    'label' => false,
                    'data' => TermList::collect($terms)
                ]);


            $this->group('Verbos Irregulares')
                ->add($key . '-v', TermListType::class, [
                    'mapped' => false,
                    'label' => false,
                    'data' => TermList::collect($terms)
                ]);


            $this->group('Alphabet')
                ->add($key . '-a', TermListType::class, [
                    'mapped' => false,
                    'label' => false,
                    'data' => TermList::collect($terms)
                ]);
        }

        $this->tab('Final Test');

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

    private function tabName(string $term): string
    {
        return TermName::byName($term)->getValue();
    }

}
