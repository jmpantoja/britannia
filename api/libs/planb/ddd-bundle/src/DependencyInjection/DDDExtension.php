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

namespace PlanB\DDDBundle\DependencyInjection;

use PlanB\DDD\Application\UseCase\UseCaseInterface;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\Yaml\Yaml;

class DDDExtension extends Extension implements PrependExtensionInterface
{

    /**
     * Loads a specific configuration.
     *
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $container->registerForAutoconfiguration(UseCaseInterface::class)->addTag('tactician.handler', [
            'typehints' => true
        ]);

        $loader = new YamlFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config')
        );
        $loader->load('services.yaml');
    }

    /**
     * Allow an extension to prepend the extension configurations.
     */
    public function prepend(ContainerBuilder $container)
    {

        $extensions = array_keys($container->getExtensions());

        $pathToPackages = __DIR__ . '/../../config/packages/';
        $config = $this->loadPackagesConfig($pathToPackages);

        foreach ($extensions as $extension) {

            if (isset($config[$extension])) {
                  $container->prependExtensionConfig($extension, $config[$extension]);
            }
        }
    }

    private function loadPackagesConfig(string $pathToDir)
    {
        $finder = new Finder();
        $finder->name('*.yaml')->in($pathToDir);


        foreach ($finder as $file) {
            $data[] = Yaml::parseFile($file->getPathname());
        }

        return array_merge(...$data);
    }
}
