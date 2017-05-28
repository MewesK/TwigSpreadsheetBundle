<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\TokenParser;

/**
 * Class AbstractTokenParser
 * @package MewesK\TwigSpreadsheetBundle\Twig\TokenParser
 */
abstract class AbstractTokenParser extends \Twig_TokenParser
{
    /**
     * @return \Twig_Node
     */
    protected function parseBody(): \Twig_Node
    {
        $body = $this->parser->subparse(function (\Twig_Token $token) {
                return $token->test('end' . $this->getTag());
            },
            true
        );
        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);

        return $body;
    }

    /**
     * @param \Twig_Token $token
     * @return mixed|\Twig_Node_Expression_Array|\Twig_Node_Expression_Conditional|\Twig_Node_Expression_GetAttr|\Twig_Node_Expression_Unary_Not
     */
    protected function parseProperties(\Twig_Token $token)
    {
        $properties = new \Twig_Node_Expression_Array([], $token->getLine());

        if (!$this->parser->getStream()->test(\Twig_Token::BLOCK_END_TYPE)) {
            $properties = $this->parser->getExpressionParser()->parseExpression();
        }

        return $properties;
    }
}
