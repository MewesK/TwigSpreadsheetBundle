<?php

namespace Erelke\TwigSpreadsheetBundle\Twig\TokenParser;

use Erelke\TwigSpreadsheetBundle\Twig\Node\DocumentNode;
use Twig\Node\Node as Twig_Node;
use Twig\Node\Expression\ArrayExpression as Twig_Node_Expression_Array;
use Twig\Token as Twig_Token;

/**
 * Class DocumentTokenParser.
 */
class DocumentTokenParser extends BaseTokenParser
{
    /**
     * {@inheritdoc}
     */
    public function configureParameters(Twig_Token $token): array
    {
        return [
            'properties' => [
                'type' => self::PARAMETER_TYPE_ARRAY,
                'default' => new Twig_Node_Expression_Array([], $token->getLine()),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function createNode(array $nodes = [], int $lineNo = 0): Twig_Node
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
