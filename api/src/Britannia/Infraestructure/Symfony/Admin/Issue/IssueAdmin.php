<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Issue;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

final class IssueAdmin extends AbstractAdmin
{
    /**
     * @var IssueTools
     */
    private IssueTools $adminTools;

    public function __construct($code, $class, $baseControllerName, IssueTools $adminTools)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
    }

    protected function adminTools()
    {
        return $this->adminTools;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $datagridMapper
            ->add('subject')
            ->add('message');
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $listMapper
            ->add('subject')
            ->add('message')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $issue = $this->getSubject();

        $this->adminTools()
            ->form($formMapper)
            ->configure($issue);
    }

}
