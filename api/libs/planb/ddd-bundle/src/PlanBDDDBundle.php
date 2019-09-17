<?php

declare(strict_types=1);

/*
 * This file is part of the Sonata Project package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PlanB\DDDBundle;

use PlanB\DDDBundle\DependencyInjection\Compiler\ModelManagerCompilerPass;
use PlanB\DDDBundle\DependencyInjection\DDDExtension;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @final since sonata-project/admin-bundle 3.52
 */
class PlanBDDDBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ModelManagerCompilerPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 10);
    }

    public function getContainerExtension()
    {
        return new DDDExtension();
    }

}
