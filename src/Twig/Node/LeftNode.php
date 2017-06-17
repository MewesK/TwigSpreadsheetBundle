<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

/**
 * Class LeftNode.
 */
class LeftNode extends BaseNode
{
    /**
     * @param \Twig_Compiler $compiler
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write(self::CODE_FIX_CONTEXT)
            ->write(self::CODE_INSTANCE.'->startAlignment(\'left\');'.PHP_EOL)
            ->write("ob_start();\n")
            ->subcompile($this->getNode('body'))
            ->write('$leftValue = trim(ob_get_clean());'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->endAlignment($leftValue);'.PHP_EOL)
            ->write('unset($leftValue);'.PHP_EOL);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedParents(): array
    {
        return [
            FooterNode::class,
            HeaderNode::class,
        ];
    }
}
