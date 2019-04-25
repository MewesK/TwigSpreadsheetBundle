<?php

namespace MyWheels\TwigSpreadsheetBundle\Twig\Node;

/**
 * Class DrawingNode.
 */
class DrawingNode extends BaseNode
{
    /**
     * @param \Twig_Compiler $compiler
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write(self::CODE_FIX_CONTEXT)
            ->write(self::CODE_INSTANCE.'->startDrawing(')
                ->subcompile($this->getNode('path'))->raw(', ')
                ->subcompile($this->getNode('properties'))
            ->raw(');'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->endDrawing();'.PHP_EOL);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedParents(): array
    {
        return [
            SheetNode::class,
            AlignmentNode::class,
        ];
    }
}
