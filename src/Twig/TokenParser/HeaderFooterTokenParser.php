<?php

namespace MyWheels\TwigSpreadsheetBundle\Twig\TokenParser;

use MyWheels\TwigSpreadsheetBundle\Twig\Node\HeaderFooterNode;
use MyWheels\TwigSpreadsheetBundle\Wrapper\HeaderFooterWrapper;

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
     * @throws \InvalidArgumentException
     */
    public function __construct(array $attributes = [], string $baseType = HeaderFooterWrapper::BASETYPE_HEADER)
    {
        parent::__construct($attributes);

        $this->baseType = HeaderFooterWrapper::validateBaseType(strtolower($baseType));
    }

    /**
     * {@inheritdoc}
     */
    public function configureParameters(\Twig_Token $token): array
    {
        return [
            'type' => [
                'type' => self::PARAMETER_TYPE_VALUE,
                'default' => new \Twig_Node_Expression_Constant(null, $token->getLine()),
            ],
            'properties' => [
                'type' => self::PARAMETER_TYPE_ARRAY,
                'default' => new \Twig_Node_Expression_Array([], $token->getLine()),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @throws \InvalidArgumentException
     */
    public function createNode(array $nodes = [], int $lineNo = 0): \Twig_Node
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
