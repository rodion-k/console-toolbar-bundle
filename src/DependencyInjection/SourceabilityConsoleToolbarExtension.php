<?php

namespace Sourceability\ConsoleToolbarBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

class SourceabilityConsoleToolbarExtension extends ConfigurableExtension
{
    /**
     * @param array<mixed> $mergedConfig
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container): void
    {
        /** @var array{toolbar: array{enabled: bool, hidden_panels: array<mixed>, max_column_width: int}} $mergedConfig */
        $toolbar = $mergedConfig['toolbar'];

        if (!$toolbar['enabled']) {
            return;
        }

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $container
            ->getDefinition('sourceability.console_toolbar.console.profiler_toolbar_renderer')
            ->replaceArgument('$hiddenPanels', $toolbar['hidden_panels'])
            ->replaceArgument('$maxColumnWidth', $toolbar['max_column_width'])
        ;
    }
}
