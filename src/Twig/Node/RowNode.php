<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

/**
 * Class RowNode.
 */
class RowNode extends BaseNode
{
    /**
     * @param \Twig_Compiler $compiler
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write(self::CODE_FIX_CONTEXT)
            ->write(self::CODE_INSTANCE.'->setRowIndex(')
            ->subcompile($this->getNode('index'))
            ->raw(');'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->startRow('.self::CODE_INSTANCE.'->getRowIndex());'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->setRowIndex(0);'.PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->addDebugInfo($this)
            ->write(self::CODE_INSTANCE.'->endRow();'.PHP_EOL);
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
