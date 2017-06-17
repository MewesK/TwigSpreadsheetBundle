<?php

namespace MewesK\TwigSpreadsheetBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration.
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     *
     * @throws \RuntimeException
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('mewes_k_twig_spreadsheet');

        $rootNode
            ->children()
                ->booleanNode('pre_calculate_formulas')->defaultTrue()->info('Pre-calculating formulas can be slow in certain cases. Disabling this option can improve the performance but the resulting documents won\'t show the result of any formulas when opened in an external spreadsheet software.')->end()
                ->scalarNode('disk_caching_directory')->defaultNull()->info('Using disk caching can improve memory consumption by writing data to disk temporary. Works only for .XLSX and .ODS documents.')->example('"%kernel.cache_dir%/spreadsheet"')->end()
            ->end();

        return $treeBuilder;
    }
}
