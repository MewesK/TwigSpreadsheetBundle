<?php

namespace MyWheels\TwigSpreadsheetBundle\Twig\NodeVisitor;

use MyWheels\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper;

/**
 * Class MacroContextNodeVisitor.
 */
class MacroContextNodeVisitor extends \Twig_BaseNodeVisitor
{
    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     */
    protected function doEnterNode(\Twig_Node $node, \Twig_Environment $env)
    {
        // add wrapper instance as argument on all method calls
        if ($node instanceof \Twig_Node_Expression_MethodCall) {
            $keyNode = new \Twig_Node_Expression_Constant(PhpSpreadsheetWrapper::INSTANCE_KEY, $node->getTemplateLine());

            // add wrapper even if it not exists, we fix that later
            $valueNode = new \Twig_Node_Expression_Name(PhpSpreadsheetWrapper::INSTANCE_KEY, $node->getTemplateLine());
            $valueNode->setAttribute('ignore_strict_check', true);

            /**
             * @var \Twig_Node_Expression_Array $argumentsNode
             */
            $argumentsNode = $node->getNode('arguments');
            $argumentsNode->addElement($valueNode, $keyNode);
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
}
