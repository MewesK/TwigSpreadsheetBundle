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
            ->write(self::CODE_INSTANCE.' = new '.PhpSpreadsheetWrapper::class.'($context, $this->env, ')
                ->repr($this->attributes)
            ->raw(');'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->startDocument(')
                ->subcompile($this->getNode('properties'))
            ->raw(');'.PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->addDebugInfo($this)
            ->write("ob_end_clean();\n")
            ->write(self::CODE_INSTANCE.'->endDocument();'.PHP_EOL)
            ->write('unset('.self::CODE_INSTANCE.');'.PHP_EOL);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedParents(): array
    {
        return [];
    }
}
