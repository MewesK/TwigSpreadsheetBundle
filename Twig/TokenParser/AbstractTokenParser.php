<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\TokenParser;

use Twig\Node\Expression\ArrayExpression;
use Twig\Node\Expression\ConditionalExpression;
use Twig\Node\Expression\GetAttrExpression;
use Twig\Node\Node;
use Twig\Token;
use Twig\TokenParser\AbstractTokenParser as BaseTokenParser;

/**
 * Class AbstractTokenParser
 * @package MewesK\TwigSpreadsheetBundle\Twig\TokenParser
 */
abstract class AbstractTokenParser extends BaseTokenParser
{
    /**
     * @return Node
     */
    protected function parseBody()
    {
        $body = $this->parser->subparse(function (Token $token) {
                return $token->test('end' . $this->getTag());
            },
            true
        );
        $this->parser->getStream()->expect(Token::BLOCK_END_TYPE);

        return $body;
    }

    /**
     * @param Token $token
     * @return mixed|ArrayExpression|ConditionalExpression|GetAttrExpression
     */
    protected function parseProperties(Token $token)
    {
        $properties = new ArrayExpression([], $token->getLine());

        if (!$this->parser->getStream()->test(Token::BLOCK_END_TYPE)) {
            $properties = $this->parser->getExpressionParser()->parseExpression();
        }

        return $properties;
    }
}
