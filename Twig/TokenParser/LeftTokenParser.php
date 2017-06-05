<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\TokenParser;

use MewesK\TwigSpreadsheetBundle\Twig\Node\LeftNode;

/**
 * Class LeftTokenParser.
 */
class LeftTokenParser extends BaseTokenParser
{
    /**
     * {@inheritdoc}
     */
    public function getNode(): string
    {
        return LeftNode::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'xlsleft';
    }
}
