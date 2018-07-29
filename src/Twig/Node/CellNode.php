<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

/**
 * Class CellNode.
 */
class CellNode extends BaseNode
{
    /**
     * @param \Twig_Compiler $compiler
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write(self::CODE_FIX_CONTEXT)
            ->write(self::CODE_INSTANCE.'->startCell(')
                ->subcompile($this->getNode('index'))
                ->raw(', ')
                ->subcompile($this->getNode('properties'))
            ->raw(');'.PHP_EOL)
            ->write("ob_start();\n")
            ->subcompile($this->getNode('body'))
            ->write(self::CODE_INSTANCE.'->setCellValue(trim(ob_get_clean()));'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->endCell();'.PHP_EOL);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedParents(): array
    {
        return [
            RowNode::class,
        ];
    }
}
