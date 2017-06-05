<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\TokenParser;

use MewesK\TwigSpreadsheetBundle\Twig\Node\CenterNode;

/**
 * Class CenterTokenParser.
 */
class CenterTokenParser extends BaseTokenParser
{
    /**
     * {@inheritdoc}
     */
    public function getNode(): string
    {
        return CenterNode::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'xlscenter';
    }
}
