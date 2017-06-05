<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

/**
 * Class SyntaxAwareNode
 *
 * @package MewesK\TwigSpreadsheetBundle\Twig\Node
 */
abstract class SyntaxAwareNode extends \Twig_Node
{
    /**
     * @return string[]
     */
    public abstract function getAllowedParents(): array;
}
