<?php

namespace Erelke\TwigSpreadsheetBundle\Twig\TokenParser;

use Erelke\TwigSpreadsheetBundle\Twig\Node\RowNode;
use Twig\Node\Expression\ConstantExpression as Twig_Node_Expression_Constant;
use Twig\Token as Twig_Token;
use Twig\Node\Node as Twig_Node;

/**
 * Class RowTokenParser.
 */
class RowTokenParser extends BaseTokenParser
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
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function createNode(array $nodes = [], int $lineNo = 0): Twig_Node
    {
        return new RowNode($nodes, $this->getAttributes(), $lineNo, $this->getTag());
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'xlsrow';
    }
}
