<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

use MewesK\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper;

/**
 * Class XlsRowNode
 *
 * @package MewesK\TwigSpreadsheetBundle\Twig\Node
 */
class XlsRowNode extends \Twig_Node implements SyntaxAwareNodeInterface
{
    /**
     * @param \Twig_Node_Expression $index
     * @param \Twig_Node $body
     * @param int $line
     * @param string $tag
     */
    public function __construct(\Twig_Node_Expression $index, \Twig_Node $body, $line = 0, $tag = 'xlsrow')
    {
        parent::__construct(['index' => $index, 'body' => $body], [], $line, $tag);
    }

    /**
     * @param \Twig_Compiler $compiler
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write('$context = ' . PhpSpreadsheetWrapper::class . '::fixContext($context);' . PHP_EOL)
            ->write('$context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\']->setRowIndex(')
            ->subcompile($this->getNode('index'))
            ->raw(');' . PHP_EOL)
            ->write('$context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\']->startRow($context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\']->getRowIndex());' . PHP_EOL)
            ->write('$context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\']->setRowIndex(0);' . PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->addDebugInfo($this)
            ->write('$context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\']->endRow();' . PHP_EOL);
    }

    /**
     * @return string[]
     */
    public function getAllowedParents(): array
    {
        return [
            XlsSheetNode::class
        ];
    }

    /**
     * @return bool
     */
    public function canContainText(): bool
    {
        return false;
    }
}
