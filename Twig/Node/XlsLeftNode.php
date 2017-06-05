<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

use MewesK\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper;

/**
 * Class XlsLeftNode
 *
 * @package MewesK\TwigSpreadsheetBundle\Twig\Node
 */
class XlsLeftNode extends SyntaxAwareNode
{
    /**
     * @param \Twig_Node $body
     * @param int $line
     * @param string $tag
     */
    public function __construct(\Twig_Node $body, $line = 0, $tag = 'xlsleft')
    {
        parent::__construct(['body' => $body], [], $line, $tag);
    }

    /**
     * @param \Twig_Compiler $compiler
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write('$context = ' . PhpSpreadsheetWrapper::class . '::fixContext($context);' . PHP_EOL)
            ->write('$context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\']->startAlignment(\'left\');' . PHP_EOL)
            ->write("ob_start();\n")
            ->subcompile($this->getNode('body'))
            ->write('$leftValue = trim(ob_get_clean());' . PHP_EOL)
            ->write('$context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\']->endAlignment($leftValue);' . PHP_EOL)
            ->write('unset($leftValue);' . PHP_EOL);
    }

    /**
     * @return string[]
     */
    public function getAllowedParents(): array
    {
        return [
            XlsFooterNode::class,
            XlsHeaderNode::class
        ];
    }
}
