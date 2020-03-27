<?php

declare(strict_types=1);

namespace Britannia\Infraestructure\Symfony\Admin\Setting;

use PlanB\DDDBundle\Symfony\Form\Type\WYSIWYGType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;

final class SettingAdmin extends AbstractAdmin
{
    /**
     * @var SettingTools
     */
    private SettingTools $adminTools;

    protected $maxPerPage = 50;
    protected $maxPageLinks = 10;

    public function __construct($code, $class, $baseControllerName, SettingTools $adminTools)
    {
        parent::__construct($code, $class, $baseControllerName);
        $this->adminTools = $adminTools;
    }

    /**
     * @return SettingTools
     */
    public function adminTools(): SettingTools
    {
        return $this->adminTools;
    }

    protected function configureFormFields(FormMapper $formMapper): void
    {
        $this->adminTools()
            ->form($formMapper)
            ->configure();
    }

}
