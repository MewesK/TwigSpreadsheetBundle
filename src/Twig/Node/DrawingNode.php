<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

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
            ->write('$drawingPath = ')
            ->subcompile($this->getNode('path'))
            ->raw(';'.PHP_EOL)
            ->write('$drawingProperties = ')
            ->subcompile($this->getNode('properties'))
            ->raw(';'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->startDrawing($drawingPath, $drawingProperties);'.PHP_EOL)
            ->write('unset($drawingPath, $drawingProperties);'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->endDrawing();'.PHP_EOL);
    }

    /**
     * @return string[]
     */
    public function getAllowedParents(): array
    {
        return [
            SheetNode::class,
            LeftNode::class,
            CenterNode::class,
            RightNode::class,
        ];
    }
}
