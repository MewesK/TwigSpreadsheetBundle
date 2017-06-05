<?php

namespace MewesK\TwigSpreadsheetBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\ConfigurableExtension;

/**
 * Class MewesKTwigSpreadsheetExtension.
 */
class MewesKTwigSpreadsheetExtension extends ConfigurableExtension
{
    /**
     * @param array            $mergedConfig
     * @param ContainerBuilder $container
     *
     * @throws \Exception
     */
    protected function loadInternal(array $mergedConfig, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $container->setParameter('mewes_k_twig_spreadsheet.pre_calculate_formulas', $mergedConfig['pre_calculate_formulas']);
        $container->setParameter('mewes_k_twig_spreadsheet.disk_caching_directory', $mergedConfig['disk_caching_directory']);
    }
}
