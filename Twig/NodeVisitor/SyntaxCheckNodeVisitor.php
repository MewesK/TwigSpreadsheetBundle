<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\NodeVisitor;

use MewesK\TwigSpreadsheetBundle\Twig\Node\SyntaxAwareNodeInterface;

/**
 * Class SyntaxCheckNodeVisitor
 *
 * @package MewesK\TwigSpreadsheetBundle\Twig\NodeVisitor
 */
class SyntaxCheckNodeVisitor extends \Twig_BaseNodeVisitor
{
    /**
     * @var array
     */
    protected $path = [];

    /**
     * {@inheritdoc}
     * @throws \Twig_Error_Syntax
     */
    protected function doEnterNode(\Twig_Node $node, \Twig_Environment $env)
    {
        if ($node instanceof SyntaxAwareNodeInterface) {
            /**
             * @var SyntaxAwareNodeInterface $node
             */
            try {
                $this->checkAllowedParents($node);
            } catch(\Twig_Error_Syntax $e) {
                // reset path since throwing an error prevents doLeaveNode to be called
                $this->path = [];
                throw $e;
            }
        }

        $this->path[] = $node !== null ? get_class($node) : null;

        return $node;
    }

    /**
     * {@inheritdoc}
     */
    protected function doLeaveNode(\Twig_Node $node,\ Twig_Environment $env)
    {
        array_pop($this->path);

        return $node;
    }

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 0;
    }

    /**
     * @param SyntaxAwareNodeInterface $node
     * @throws \Twig_Error_Syntax
     */
    private function checkAllowedParents(SyntaxAwareNodeInterface $node)
    {
        $parentName = null;

        foreach (array_reverse($this->path) as $className) {
            if ($className !== null && strpos($className, 'MewesK\TwigSpreadsheetBundle\Twig\Node\Xls') === 0) {
                $parentName = $className;
                break;
            }
        }

        if ($parentName === null) {
            return;
        }

        foreach ($node->getAllowedParents() as $className) {
            if ($className === $parentName) {
                return;
            }
        }

        throw new \Twig_Error_Syntax(sprintf('Node "%s" is not allowed inside of Node "%s".', get_class($node), $parentName));
    }
}
