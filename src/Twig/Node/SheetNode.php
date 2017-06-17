<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

/**
 * Class SheetNode.
 */
class SheetNode extends BaseNode
{
    /**
     * @param \Twig_Compiler $compiler
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write(self::CODE_FIX_CONTEXT)
            ->write('$sheetIndex = ')
            ->subcompile($this->getNode('index'))
            ->raw(';'.PHP_EOL)
            ->write('$sheetProperties = ')
            ->subcompile($this->getNode('properties'))
            ->raw(';'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->startSheet($sheetIndex, $sheetProperties);'.PHP_EOL)
            ->write('unset($sheetIndex, $sheetProperties);'.PHP_EOL);

        if ($this->hasNode('body')) {
            $compiler->subcompile($this->getNode('body'));
        }

        $compiler->addDebugInfo($this)
            ->write(self::CODE_INSTANCE.'->endSheet();'.PHP_EOL);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedParents(): array
    {
        return [
            DocumentNode::class,
        ];
    }
}
