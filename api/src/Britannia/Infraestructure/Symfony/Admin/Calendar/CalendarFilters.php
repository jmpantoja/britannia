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

namespace Britannia\Infraestructure\Symfony\Admin\Calendar;


use Britannia\Domain\Repository\CalendarRepositoryInterface;
use PlanB\DDDBundle\Sonata\Admin\AdminFilter;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class CalendarFilters extends AdminFilter
{

    /**
     * @var CalendarRepositoryInterface
     */
    private CalendarRepositoryInterface $repository;

    public function setRepository(CalendarRepositoryInterface $repository): self
    {
        $this->repository = $repository;
        return $this;
    }

    public function configure()
    {
        $this->add('month', 'doctrine_orm_choice', [
            'label' => 'Mes',
            'show_filter' => true
        ], ChoiceType::class, [
            'choices' => [
                'Enero' => 1,
                'Febrero' => 2,
                'Marzo' => 3,
                'Abril' => 4,
                'Mayo' => 5,
                'Junio' => 6,
                'Julio' => 7,
                'Agosto' => 8,
                'Septiembre' => 9,
                'Octubre' => 10,
                'Noviembre' => 11,
                'Diciembre' => 12,
            ],
            'placeholder' => null
        ]);

        $this->add('year', 'doctrine_orm_choice', [
            'label' => 'Año',
            'show_filter' => true
        ], ChoiceType::class, [
            'choices' => $this->yearOptions(),
            'placeholder' => null
        ]);
    }

    private function yearOptions(): array
    {
        $years = $this->repository->getAvailableYears();
        $availables = [];

        foreach ($years as $year) {
            $availables[$year] = $year;
        }

        return $availables;
    }
}
