<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\TokenParser;

use MewesK\TwigSpreadsheetBundle\Twig\Node\DrawingNode;

/**
 * Class DrawingTokenParser.
 */
class DrawingTokenParser extends BaseTokenParser
{
    /**
     * {@inheritdoc}
     */
    public function configureParameters(\Twig_Token $token): array
    {
        return [
            'path' => [
                'type' => self::PARAMETER_TYPE_VALUE,
                'default' => false,
            ],
            'properties' => [
                'type' => self::PARAMETER_TYPE_ARRAY,
                'default' => new \Twig_Node_Expression_Array([], $token->getLine()),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getNode(): string
    {
        return DrawingNode::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'xlsdrawing';
    }

    /**
     * {@inheritdoc}
     */
    public function hasBody(): bool
    {
        return false;
    }
}
