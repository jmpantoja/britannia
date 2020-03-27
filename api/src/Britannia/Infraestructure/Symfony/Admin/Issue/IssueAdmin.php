<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Issue;

use Britannia\Domain\Entity\Issue\Issue;
use Britannia\Domain\Entity\Staff\StaffMember;
use Britannia\Infraestructure\Symfony\Admin\AdminFilterableInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Security\Core\Security;

final class IssueAdmin extends AbstractAdmin implements AdminFilterableInterface
{
    protected $datagridValues = [
        'status' => ['value' => [
            'status' => 1,
            'recipient' => 2,
        ]],
        '_sort_order' => 'DESC',
        '_sort_by' => 'createdAt',
    ];

    /**
     * @var IssueTools
     */
    private IssueTools $adminTools;

    protected $maxPerPage = 50;
    protected $maxPageLinks = 10;
    /**
     * @var Security
     */
    private Security $security;

    public function __construct($code, $class, $baseControllerName, IssueTools $adminTools, Security $security)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
        $this->dataGridValues();
        $this->security = $security;
    }

    protected function adminTools()
    {
        return $this->adminTools;
    }

    /**
     * @inheritDoc
     */
    public function datagridValues(): array
    {
        return $this->datagridValues;
    }

    public function getBatchActions()
    {
        return [];
    }

    public function configureActionButtons($action, $object = null)
    {
        $list = parent::configureActionButtons($action, $object);

        $temp = [];
        if (in_array($action, ['show', 'edit'])) {
            $temp['mark_as_read'] = [
                "template" => "admin/issue/Button/mark_as_read_button.html.twig"
            ];
        }
        return array_merge($temp, $list);
    }


    protected function configureRoutes(RouteCollection $collection)
    {

        $this->adminTools()
            ->routes($collection, $this->getRouterIdParameter())
            ->build();

    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $user = $this->getUser();
        $this->adminTools()
            ->filters($datagridMapper)
            ->configure($user);
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $this->adminTools()
            ->dataGrid($listMapper)
            ->configure();
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $issue = $this->getSubject();
        $this->adminTools()
            ->form($formMapper)
            ->configure($issue);
    }

    public function checkAccess($action, $object = null)
    {
        if ('edit' === $action and $this->hasAccess($action, $object)) {
            return;
        }

        parent::checkAccess($action, $object);
    }


    public function hasAccess($action, $object = null)
    {
        if ('edit' === $action and $object instanceof Issue) {
            $user = $this->getUser();
            return $object->equalAuthor($user) ? true : false;
        }

        if ('read' === $action and $object instanceof Issue) {
            $user = $this->getUser();
            return $object->containsRecipient($user);
        }

        return parent::hasAccess($action, $object);
    }


    protected function getUser(): StaffMember
    {
        return $this->security->getUser();
    }

}
