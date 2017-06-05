<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

/**
 * Class FooterNode.
 */
class FooterNode extends BaseNode
{
    /**
     * @param \Twig_Compiler $compiler
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write(self::CODE_FIX_CONTEXT)
            ->write('$footerType = ')
            ->subcompile($this->getNode('type'))
            ->raw(';'.PHP_EOL)
            ->write('$footerType = $footerType ? $footerType : \'footer\';'.PHP_EOL)
            ->write('$footerProperties = ')
            ->subcompile($this->getNode('properties'))
            ->raw(';'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->startHeaderFooter($footerType, $footerProperties);'.PHP_EOL)
            ->write('unset($footerType, $footerProperties);'.PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->addDebugInfo($this)
            ->write(self::CODE_INSTANCE.'->endHeaderFooter();'.PHP_EOL);
    }

    /**
     * @return string[]
     */
    public function getAllowedParents(): array
    {
        return [
            SheetNode::class,
        ];
    }
}
