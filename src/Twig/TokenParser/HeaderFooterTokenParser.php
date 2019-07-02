<?php

namespace Erelke\TwigSpreadsheetBundle\Twig\TokenParser;

use Erelke\TwigSpreadsheetBundle\Twig\Node\HeaderFooterNode;
use Erelke\TwigSpreadsheetBundle\Wrapper\HeaderFooterWrapper;
use InvalidArgumentException;
use Twig\Node\Expression\ArrayExpression as Twig_Node_Expression_Array;
use Twig\Node\Expression\ConstantExpression as Twig_Node_Expression_Constant;
use Twig\Token as Twig_Token;
use Twig\Node\Node as Twig_Node;

/**
 * Class HeaderFooterTokenParser.
 */
class HeaderFooterTokenParser extends BaseTokenParser
{
    /**
     * @var string
     */
    private $baseType;

    /**
     * HeaderFooterTokenParser constructor.
     *
     * @param array  $attributes optional attributes for the corresponding node
     * @param string $baseType
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $attributes = [], string $baseType = HeaderFooterWrapper::BASETYPE_HEADER)
    {
        parent::__construct($attributes);

        $this->baseType = HeaderFooterWrapper::validateBaseType(strtolower($baseType));
    }

    /**
     * {@inheritdoc}
     */
    public function configureParameters(Twig_Token $token): array
    {
        return [
            'type' => [
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
     *
     * @throws InvalidArgumentException
     */
    public function createNode(array $nodes = [], int $lineNo = 0): Twig_Node
    {
        return new HeaderFooterNode($nodes, $this->getAttributes(), $lineNo, $this->getTag(), $this->baseType);
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'xls'.$this->baseType;
    }
}
