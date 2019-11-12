<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin;

use Britannia\Domain\Entity\Calendar\Calendar;
use Britannia\Domain\Repository\CalendarRepositoryInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Admin\AdminInterface;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class CalendarAdmin extends AbstractAdmin
{
    /**
     * @var CalendarRepositoryInterface
     */
    private $repository;

    public function __construct(string $code, string $class, string $baseControllerName, CalendarRepositoryInterface $repository)
    {
        parent::__construct($code, $class, $baseControllerName);

        $this->repository = $repository;

        $today = new \DateTime();

        $this->datagridValues = [
            'month' => array('value' => $today->format('m')),
            'year' => array('value' => $today->format('Y'))
        ];
    }

    protected function configureBatchActions($actions)
    {
        if ($this->hasAccess('edit')) {
            $actions['to-holiday'] = [
                'label' => 'A Festivo',
                'ask_confirmation' => false
            ];

            $actions['to-workday'] = [
                'label' => 'A Laborable',
                'ask_confirmation' => false
            ];
        }

        return $actions;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept(['list', 'batch']);
        return $collection;
    }

    public function configureActionButtons($action, $object = null)
    {
        $actionButtons = parent::configureActionButtons($action, $object);

        unset($actionButtons['create']);

        return $actionButtons;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('month', 'doctrine_orm_choice', [
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
            ])
            ->add('year', 'doctrine_orm_choice', [
                'show_filter' => true
            ], ChoiceType::class, [
                'choices' => $this->getYearOptions(),
                'placeholder' => null
            ]);
    }

    private function getYearOptions(): array
    {
        $years = $this->repository->getAvailableYears();
        $availables = [];

        foreach ($years as $year) {
            $availables[$year] = $year;
        }

        return $availables;
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('workDay', null, [
                'editable' => true,
                'header_style' => 'width:30px',
                'label' => 'Laborable',
                'row_align' => 'center'
            ])
            ->add('date', null, [
                'template' => 'admin/calendar/calendar_resume_column.html.twig'
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $formMapper
            ->add('weekday')
            ->add('holiday')
            ->add('date');
    }

    protected function configureShowFields(ShowMapper $showMapper): void
    {
        $showMapper
            ->add('weekday')
            ->add('holiday')
            ->add('date');
    }
}
