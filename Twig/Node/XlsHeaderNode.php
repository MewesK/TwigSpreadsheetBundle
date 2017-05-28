<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

use MewesK\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper;

/**
 * Class XlsHeaderNode
 *
 * @package MewesK\TwigSpreadsheetBundle\Twig\Node
 */
class XlsHeaderNode extends \Twig_Node implements SyntaxAwareNodeInterface
{
    /**
     * @param \Twig_Node_Expression $type
     * @param \Twig_Node_Expression $properties
     * @param \Twig_Node $body
     * @param int $line
     * @param string $tag
     */
    public function __construct(\Twig_Node_Expression $type, \Twig_Node_Expression $properties, \Twig_Node $body, $line = 0, $tag = 'xlsheader')
    {
        parent::__construct(['type' => $type, 'properties' => $properties, 'body' => $body], [], $line, $tag);
    }

    /**
     * @param \Twig_Compiler $compiler
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write('$context = ' . PhpSpreadsheetWrapper::class . '::fixContext($context);' . PHP_EOL)
            ->write('$headerType = ')
            ->subcompile($this->getNode('type'))
            ->raw(';' . PHP_EOL)
            ->write('$headerType = $headerType ? $headerType : \'header\';' . PHP_EOL)
            ->write('$headerProperties = ')
            ->subcompile($this->getNode('properties'))
            ->raw(';' . PHP_EOL)
            ->write('$context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\']->startHeaderFooter($headerType, $headerProperties);' . PHP_EOL)
            ->write('unset($headerType, $headerProperties);' . PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->addDebugInfo($this)
            ->write('$context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\']->endHeaderFooter();' . PHP_EOL);
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
