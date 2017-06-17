<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

/**
 * Class HeaderNode.
 */
class HeaderNode extends BaseNode
{
    /**
     * @param \Twig_Compiler $compiler
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write(self::CODE_FIX_CONTEXT)
            ->write('$headerType = ')
            ->subcompile($this->getNode('type'))
            ->raw(';'.PHP_EOL)
            ->write('$headerType = $headerType ? $headerType : \'header\';'.PHP_EOL)
            ->write('$headerProperties = ')
            ->subcompile($this->getNode('properties'))
            ->raw(';'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->startHeaderFooter($headerType, $headerProperties);'.PHP_EOL)
            ->write('unset($headerType, $headerProperties);'.PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->addDebugInfo($this)
            ->write(self::CODE_INSTANCE.'->endHeaderFooter();'.PHP_EOL);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedParents(): array
    {
        return [
            SheetNode::class,
        ];
    }
}
