<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\NodeVisitor;

use MewesK\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper;
use Twig\Node\Expression\ConstantExpression;
use Twig\Node\Expression\MethodCallExpression;
use Twig\Node\Expression\NameExpression;
use Twig\NodeVisitor\AbstractNodeVisitor;

/**
 * Class MacroContextFixNodeVisitor
 *
 * @package MewesK\TwigSpreadsheetBundle\Twig\NodeVisitor
 */
class MacroContextFixNodeVisitor extends AbstractNodeVisitor
{
    /**
     * {@inheritdoc}
     */
    protected function doEnterNode(\Twig_Node $node, \Twig_Environment $env)
    {
        // Add 'spreadsheetWrapper' as argument on method/macro calls
        if ($node instanceof MethodCallExpression) {
            /**
             * @var \Twig_Node_Expression_Array $argumentsNode
             */
            $argumentsNode = $node->getNode('arguments');
            $argumentsNode->addElement(
                new NameExpression(PhpSpreadsheetWrapper::INSTANCE_KEY, $node->getTemplateLine()),
                new ConstantExpression(PhpSpreadsheetWrapper::INSTANCE_KEY, $node->getTemplateLine())
            );
        }

        return $node;
    }

    /**
     * {@inheritdoc}
     */
    protected function doLeaveNode(\Twig_Node $node, \Twig_Environment $env)
    {
        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 0;
    }
}
