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
        $treeBuilder = new TreeBuilder('mewes_k_twig_spreadsheet');
        $rootNode = $treeBuilder->getRootNode();

        $rootNode
            ->children()
                ->booleanNode('pre_calculate_formulas')
                    ->defaultTrue()
                    ->info('Disabling formula calculations can improve the performance but the resulting documents won\'t immediately show formula results in external programs.')
                ->end()
                ->arrayNode('cache')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->scalarNode('bitmap')
                            ->defaultValue('%kernel.cache_dir%/spreadsheet/bitmap')
                            ->cannotBeEmpty()
                            ->info('Using a bitmap cache is necessary, PhpSpreadsheet supports only local files.')
                        ->end()
                        ->scalarNode('xml')
                            ->defaultFalse()
                            ->example('"%kernel.cache_dir%/spreadsheet/xml"')
                            ->info('Using XML caching can improve memory consumption by writing data to disk. Works only for .xlsx and .ods documents.')
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('csv_writer')
                    ->addDefaultsIfNotSet()
                    ->info('See PhpOffice\PhpSpreadsheet\Writer\Csv.php for more information.')
                    ->children()
                        ->scalarNode('delimiter')
                            ->defaultValue(',')
                        ->end()
                        ->scalarNode('enclosure')
                            ->defaultValue('"')
                        ->end()
                        ->booleanNode('excel_compatibility')
                            ->defaultFalse()
                        ->end()
                        ->booleanNode('include_separator_line')
                            ->defaultFalse()
                        ->end()
                        ->scalarNode('line_ending')
                            ->defaultValue(PHP_EOL)
                        ->end()
                        ->integerNode('sheet_index')
                            ->defaultValue(0)
                        ->end()
                        ->booleanNode('use_bom')
                            ->defaultFalse()
                        ->end()
                    ->end()
                ->end()
            ->end();

        return $treeBuilder;
    }
}
