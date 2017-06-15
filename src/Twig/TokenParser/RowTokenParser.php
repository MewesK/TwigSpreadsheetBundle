<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\TokenParser;

use MewesK\TwigSpreadsheetBundle\Twig\Node\RowNode;

/**
 * Class RowTokenParser.
 */
class RowTokenParser extends BaseTokenParser
{
    /**
     * {@inheritdoc}
     */
    public function configureParameters(\Twig_Token $token): array
    {
        return [
            'index' => [
                'type' => self::PARAMETER_TYPE_VALUE,
                'default' => new \Twig_Node_Expression_Constant(null, $token->getLine()),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getNode(): string
    {
        return RowNode::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'xlsrow';
    }
}
