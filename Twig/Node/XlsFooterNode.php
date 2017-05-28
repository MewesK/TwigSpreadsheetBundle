<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

use MewesK\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper;

/**
 * Class XlsFooterNode
 *
 * @package MewesK\TwigSpreadsheetBundle\Twig\Node
 */
class XlsFooterNode extends \Twig_Node implements SyntaxAwareNodeInterface
{
    /**
     * @param \Twig_Node_Expression $type
     * @param \Twig_Node_Expression $properties
     * @param \Twig_Node $body
     * @param int $line
     * @param string $tag
     */
    public function __construct(\Twig_Node_Expression $type, \Twig_Node_Expression $properties, \Twig_Node $body, $line = 0, $tag = 'xlsfooter')
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
            ->write('$footerType = ')
            ->subcompile($this->getNode('type'))
            ->raw(';' . PHP_EOL)
            ->write('$footerType = $footerType ? $footerType : \'footer\';' . PHP_EOL)
            ->write('$footerProperties = ')
            ->subcompile($this->getNode('properties'))
            ->raw(';' . PHP_EOL)
            ->write('$context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\']->startHeaderFooter($footerType, $footerProperties);' . PHP_EOL)
            ->write('unset($footerType, $footerProperties);' . PHP_EOL)
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
