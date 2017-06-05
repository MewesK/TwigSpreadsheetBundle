<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\TokenParser;

use MewesK\TwigSpreadsheetBundle\Twig\Node\HeaderNode;

/**
 * Class HeaderTokenParser.
 */
class HeaderTokenParser extends BaseTokenParser
{
    /**
     * {@inheritdoc}
     */
    public function configureParameters(\Twig_Token $token): array
    {
        return [
            'type' => [
                'type' => self::PARAMETER_TYPE_VALUE,
                'default' => new \Twig_Node_Expression_Constant('header', $token->getLine()),
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
        return HeaderNode::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'xlsheader';
    }
}
