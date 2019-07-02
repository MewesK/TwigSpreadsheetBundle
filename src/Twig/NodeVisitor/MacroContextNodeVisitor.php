<?php

namespace Erelke\TwigSpreadsheetBundle\Twig\NodeVisitor;

use Erelke\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper;
use Twig\Environment as Twig_Environment;
use Twig\Node\Node as Twig_Node;
use Twig\NodeVisitor\AbstractNodeVisitor as Twig_BaseNodeVisitor;
use Twig\Node\Expression\MethodCallExpression as Twig_Node_Expression_MethodCall;
use Twig\Node\Expression\ConstantExpression as Twig_Node_Expression_Constant;
use Twig\Node\Expression\NameExpression as Twig_Node_Expression_Name;
use Twig\Node\Expression\ArrayExpression as Twig_Node_Expression_Array;;

/**
 * Class MacroContextNodeVisitor.
 */
class MacroContextNodeVisitor extends Twig_BaseNodeVisitor
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
    protected function doEnterNode(Twig_Node $node, Twig_Environment $env)
    {
        // add wrapper instance as argument on all method calls
        if ($node instanceof Twig_Node_Expression_MethodCall) {
            $keyNode = new Twig_Node_Expression_Constant(PhpSpreadsheetWrapper::INSTANCE_KEY, $node->getTemplateLine());

            // add wrapper even if it not exists, we fix that later
            $valueNode = new Twig_Node_Expression_Name(PhpSpreadsheetWrapper::INSTANCE_KEY, $node->getTemplateLine());
            $valueNode->setAttribute('ignore_strict_check', true);

            /**
             * @var Twig_Node_Expression_Array $argumentsNode
             */
            $argumentsNode = $node->getNode('arguments');
            $argumentsNode->addElement($valueNode, $keyNode);
        }

        return $node;
    }

    /**
     * {@inheritdoc}
     */
    protected function doLeaveNode(Twig_Node $node, Twig_Environment $env)
    {
        return $node;
    }
}
