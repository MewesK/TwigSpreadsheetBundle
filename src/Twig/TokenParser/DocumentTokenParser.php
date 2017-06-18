<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\TokenParser;

use MewesK\TwigSpreadsheetBundle\Twig\Node\DocumentNode;

/**
 * Class DocumentTokenParser.
 */
class DocumentTokenParser extends BaseTokenParser
{
    /**
     * {@inheritdoc}
     */
    public function configureParameters(\Twig_Token $token): array
    {
        return [
            'properties' => [
                'type' => self::PARAMETER_TYPE_ARRAY,
                'default' => new \Twig_Node_Expression_Array([], $token->getLine()),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function createNode(array $nodes = [], int $lineNo = 0): \Twig_Node
    {
        return new DocumentNode($nodes, $this->getAttributes(), $lineNo, $this->getTag());
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'xlsdocument';
    }
}
