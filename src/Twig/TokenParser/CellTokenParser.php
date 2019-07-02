<?php

namespace Erelke\TwigSpreadsheetBundle\Twig\TokenParser;

use Erelke\TwigSpreadsheetBundle\Twig\Node\CellNode;
use Twig\Node\Node as Twig_Node;
use Twig\Node\Expression\ArrayExpression as Twig_Node_Expression_Array;
use Twig\Node\Expression\ConstantExpression as Twig_Node_Expression_Constant;
use Twig\Token as Twig_Token;

/**
 * Class CellTokenParser.
 */
class CellTokenParser extends BaseTokenParser
{
    /**
     * {@inheritdoc}
     */
    public function configureParameters(Twig_Token $token): array
    {
        return [
            'index' => [
                'type' => self::PARAMETER_TYPE_VALUE,
                'default' => new Twig_Node_Expression_Constant(null, $token->getLine()),
            ],
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
        return new CellNode($nodes, $this->getAttributes(), $lineNo, $this->getTag());
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'xlscell';
    }
}
