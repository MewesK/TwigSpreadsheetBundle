<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

use MewesK\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper;

/**
 * Class DocumentNode.
 */
class DocumentNode extends BaseNode
{
    /**
     * @param \Twig_Compiler $compiler
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write("ob_start();\n")
            ->write('$documentProperties = ')
            ->subcompile($this->getNode('properties'))
            ->raw(';'.PHP_EOL)
            ->write(self::CODE_INSTANCE.' = new '.PhpSpreadsheetWrapper::class.'($context, $this->env);'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->startDocument($documentProperties);'.PHP_EOL)
            ->write('unset($documentProperties);'.PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->addDebugInfo($this)
            ->write("ob_end_clean();\n")
            ->write(self::CODE_INSTANCE.'->endDocument('.
                ($this->getAttribute('preCalculateFormulas') ? 'true' : 'false').', '.
                ($this->getAttribute('diskCachingDirectory') ? '\''.$this->getAttribute('diskCachingDirectory').'\'' : 'null').');'.PHP_EOL)
            ->write('unset('.self::CODE_INSTANCE.');'.PHP_EOL);
    }

    /**
     * @return string[]
     */
    public function getAllowedParents(): array
    {
        return [];
    }
}
