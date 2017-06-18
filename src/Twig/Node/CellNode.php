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
            ->write(self::CODE_INSTANCE.'->setCellIndex(')
                ->subcompile($this->getNode('index'))
            ->raw(');'.PHP_EOL)
            ->write("ob_start();\n")
            ->subcompile($this->getNode('body'))
            ->write('$cellValue = trim(ob_get_clean());'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->startCell($cellValue, ')
                ->subcompile($this->getNode('properties'))
            ->raw(');'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->endCell();'.PHP_EOL)
            ->write('unset($cellValue);'.PHP_EOL);
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
