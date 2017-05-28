<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

use MewesK\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper;
use Twig_Compiler;
use Twig_Node;
use Twig_Node_Expression;

/**
 * Class XlsSheetNode
 *
 * @package MewesK\TwigSpreadsheetBundle\Twig\Node
 */
class XlsSheetNode extends Twig_Node implements SyntaxAwareNodeInterface
{
    /**
     * @param Twig_Node_Expression $index
     * @param Twig_Node_Expression $properties
     * @param Twig_Node $body
     * @param int $line
     * @param string $tag
     */
    public function __construct(Twig_Node_Expression $index, Twig_Node_Expression $properties, Twig_Node $body, $line = 0, $tag = 'xlssheet')
    {
        parent::__construct(['index' => $index, 'properties' => $properties, 'body' => $body], [], $line, $tag);
    }

    /**
     * @param Twig_Compiler $compiler
     */
    public function compile(Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write('$context = ' . PhpSpreadsheetWrapper::class . '::fixContext($context);' . PHP_EOL)
            ->write('$sheetIndex = ')
            ->subcompile($this->getNode('index'))
            ->raw(';' . PHP_EOL)
            ->write('$sheetProperties = ')
            ->subcompile($this->getNode('properties'))
            ->raw(';' . PHP_EOL)
            ->write('$context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\']->startSheet($sheetIndex, $sheetProperties);' . PHP_EOL)
            ->write('unset($sheetIndex, $sheetProperties);' . PHP_EOL);

        if ($this->hasNode('body')) {
            $compiler->subcompile($this->getNode('body'));
        }

        $compiler->addDebugInfo($this)->write('$context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\']->endSheet();' . PHP_EOL);
    }

    /**
     * @return string[]
     */
    public function getAllowedParents()
    {
        return [
            XlsDocumentNode::class
        ];
    }

    /**
     * @return bool
     */
    public function canContainText()
    {
        return false;
    }
}
