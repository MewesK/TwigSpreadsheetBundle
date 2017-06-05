<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\NodeVisitor;

use MewesK\TwigSpreadsheetBundle\Twig\Node\BaseNode;

/**
 * Class SyntaxCheckNodeVisitor.
 */
class SyntaxCheckNodeVisitor extends \Twig_BaseNodeVisitor
{
    /**
     * @var array
     */
    protected $path = [];

    /**
     * {@inheritdoc}
     */
    public function getPriority()
    {
        return 0;
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Twig_Error_Syntax
     */
    protected function doEnterNode(\Twig_Node $node, \Twig_Environment $env)
    {
        if ($node instanceof BaseNode) {
            /*
             * @var BaseNode $node
             */
            try {
                $this->checkAllowedParents($node);
            } catch (\Twig_Error_Syntax $e) {
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
    protected function doLeaveNode(\Twig_Node $node, \ Twig_Environment $env)
    {
        array_pop($this->path);

        return $node;
    }

    /**
     * @param BaseNode $node
     *
     * @throws \Twig_Error_Syntax
     */
    private function checkAllowedParents(BaseNode $node)
    {
        $parentName = null;

        foreach (array_reverse($this->path) as $className) {
            if (strpos($className, 'MewesK\\TwigSpreadsheetBundle\\Twig\\Node\\') === 0) {
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
