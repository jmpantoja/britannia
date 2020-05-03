<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Message;

use Britannia\Infraestructure\Symfony\Admin\AdminFilterableInterface;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;

final class MessageAdmin extends AbstractAdmin implements AdminFilterableInterface
{
    /**
     * @var MessageTools
     */
    private MessageTools $adminTools;

    protected $maxPerPage = 30;
    protected $maxPageLinks = 10;

    public function __construct($code, $class, $baseControllerName, MessageTools $adminTools)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
    }


    public function datagridValues(): array
    {
        return $this->datagridValues;
    }

    protected function configureBatchActions($actions)
    {
        return [];
    }


    /**
     * @return MessageTools
     */
    public function adminTools(): MessageTools
    {
        return $this->adminTools;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        return $this->adminTools()
            ->query($query)
            ->build();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper): void
    {
        $this->adminTools()
            ->filters($datagridMapper)
            ->configure();
    }

    protected function configureListFields(ListMapper $listMapper): void
    {
        $this->adminTools()
            ->dataGrid($listMapper)
            ->configure();
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $this->adminTools()
            ->form($formMapper)
            ->configure($this->getSubject());
    }
}
