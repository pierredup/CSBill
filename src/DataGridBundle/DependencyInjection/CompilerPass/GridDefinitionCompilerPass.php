<?php

declare(strict_types=1);

/*
 * This file is part of SolidInvoice project.
 *
 * (c) 2013-2017 Pierre du Plessis <info@customscripts.co.za>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace SolidInvoice\DataGridBundle\DependencyInjection\CompilerPass;

use SolidInvoice\DataGridBundle\DependencyInjection\GridConfiguration;
use SolidInvoice\DataGridBundle\Repository\GridRepository;
use SolidInvoice\MoneyBundle\Formatter\MoneyFormatter;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\HttpKernel\KernelInterface;
use Symfony\Component\Yaml\Yaml;

class GridDefinitionCompilerPass implements CompilerPassInterface
{
    /**
     * @var KernelInterface
     */
    private $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        $resourceLocator = new FileLocator($this->kernel);
        $definition = $container->getDefinition(GridRepository::class);

        $configs = [];

        foreach ($this->kernel->getBundles() as $bundle) {
            try {
                $file = $resourceLocator->locate(sprintf('@%s/Resources/config/grid.yml', $bundle->getName()));
            } catch (\InvalidArgumentException $e) {
                continue;
            }

            $container->addResource(new FileResource($file));

            $grid = Yaml::parse(file_get_contents($file));

            $config = $this->processConfiguration($grid);

            $this->setGridDefinition($definition, $config);
        }

        $container->setParameter('grid.definitions', $configs);
    }

    private function processConfiguration(array $grid)
    {
        $process = new Processor();
        $config = new GridConfiguration();

        return $process->processConfiguration($config, $grid);
    }

    private function setGridDefinition(Definition $gridService, array $config)
    {
        foreach ($config as $gridName => $gridConfig) {
            $gridDefinition = new Definition('SolidInvoice\DataGridBundle\Grid');

            $gridConfig['name'] = $gridName;

            $gridDefinition->addArgument($this->getGridSource($gridConfig['source']));
            $gridDefinition->addArgument($this->getFilterService($gridConfig));
            $gridDefinition->addArgument($gridConfig);
            $gridDefinition->addArgument(new Reference(MoneyFormatter::class));

            $gridService->addMethodCall('addGrid', [$gridName, $gridDefinition]);
        }
    }

    private function getGridSource(array $arguments): Definition
    {
        array_unshift($arguments, new Reference('doctrine'));

        return new Definition('SolidInvoice\DataGridBundle\Source\ORMSource', array_values($arguments));
    }

    private function getFilterService(array &$gridData): Definition
    {
        $definition = new Definition('SolidInvoice\DataGridBundle\Filter\ChainFilter');

        if (true === $gridData['properties']['sortable']) {
            $sortFilter = new Definition('SolidInvoice\DataGridBundle\Filter\SortFilter');
            $definition->addMethodCall('addFilter', [$sortFilter]);
        }

        if (true === $gridData['properties']['sortable']) {
            $paginateFilter = new Definition('SolidInvoice\DataGridBundle\Filter\PaginateFilter');
            $definition->addMethodCall('addFilter', [$paginateFilter]);
        }

        if (!empty($gridData['search']['fields'])) {
            $searchFilter = new Definition(
                'SolidInvoice\DataGridBundle\Filter\SearchFilter',
                [$gridData['search']['fields']]
            );
            $definition->addMethodCall('addFilter', [$searchFilter]);
            $gridData['properties']['search'] = true;
        }

        return $definition;
    }
}
