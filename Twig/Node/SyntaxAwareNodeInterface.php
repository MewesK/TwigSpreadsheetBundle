<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

/**
 * Interface SyntaxAwareNodeInterface
 *
 * @package MewesK\TwigSpreadsheetBundle\Twig\Node
 */
interface SyntaxAwareNodeInterface
{
    /**
     * @return string[]
     */
    public function getAllowedParents(): array;

    /**
     * @return bool
     */
    public function canContainText(): bool;
}
