<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\TokenParser;

use MewesK\TwigSpreadsheetBundle\Twig\Node\RightNode;

/**
 * Class RightTokenParser.
 */
class RightTokenParser extends BaseTokenParser
{
    /**
     * {@inheritdoc}
     */
    public function getNode(): string
    {
        return RightNode::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'xlsright';
    }
}
