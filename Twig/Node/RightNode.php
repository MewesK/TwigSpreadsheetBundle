<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

/**
 * Class RightNode.
 */
class RightNode extends BaseNode
{
    /**
     * @param \Twig_Compiler $compiler
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write(self::CODE_FIX_CONTEXT)
            ->write(self::CODE_INSTANCE.'->startAlignment(\'right\');'.PHP_EOL)
            ->write("ob_start();\n")
            ->subcompile($this->getNode('body'))
            ->write('$rightValue = trim(ob_get_clean());'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->endAlignment($rightValue);'.PHP_EOL)
            ->write('unset($rightValue);'.PHP_EOL);
    }

    /**
     * @return string[]
     */
    public function getAllowedParents(): array
    {
        return [
            FooterNode::class,
            HeaderNode::class,
        ];
    }
}
