<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

use MewesK\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper;
use Twig_Compiler;
use Twig_Node;
use Twig_Node_Expression;

/**
 * Class XlsDrawingNode
 *
 * @package MewesK\TwigSpreadsheetBundle\Twig\Node
 */
class XlsDrawingNode extends Twig_Node implements SyntaxAwareNodeInterface
{
    /**
     * @param Twig_Node_Expression $path
     * @param Twig_Node_Expression $properties
     * @param int $line
     * @param string $tag
     */
    public function __construct(Twig_Node_Expression $path, Twig_Node_Expression $properties, $line = 0, $tag = 'xlsdrawing')
    {
        parent::__construct(['path' => $path, 'properties' => $properties], [], $line, $tag);
    }

    /**
     * @param Twig_Compiler $compiler
     */
    public function compile(Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write('$context = ' . PhpSpreadsheetWrapper::class . '::fixContext($context);' . PHP_EOL)
            ->write('$drawingPath = ')
            ->subcompile($this->getNode('path'))
            ->raw(';' . PHP_EOL)
            ->write('$drawingProperties = ')
            ->subcompile($this->getNode('properties'))
            ->raw(';' . PHP_EOL)
            ->write('$context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\']->startDrawing($drawingPath, $drawingProperties);' . PHP_EOL)
            ->write('unset($drawingPath, $drawingProperties);' . PHP_EOL)
            ->write('$context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\']->endDrawing();' . PHP_EOL);
    }

    /**
     * @return string[]
     */
    public function getAllowedParents()
    {
        return [
            XlsSheetNode::class,
            XlsLeftNode::class,
            XlsCenterNode::class,
            XlsRightNode::class
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
