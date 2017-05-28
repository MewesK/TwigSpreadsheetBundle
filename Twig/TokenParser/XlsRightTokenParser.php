<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\TokenParser;

use MewesK\TwigSpreadsheetBundle\Twig\Node\XlsRightNode;

/**
 * Class XlsRightTokenParser
 *
 * @package MewesK\TwigSpreadsheetBundle\Twig\TokenParser
 */
class XlsRightTokenParser extends AbstractTokenParser
{
    /**
     * @param \Twig_Token $token
     *
     * @return XlsRightNode
     * @throws \Twig_Error_Syntax
     */
    public function parse(\Twig_Token $token)
    {
        // parse attributes
        $this->parser->getStream()->expect(\Twig_Token::BLOCK_END_TYPE);

        // parse body
        $body = $this->parseBody();

        // return node
        return new XlsRightNode($body, $token->getLine(), $this->getTag());
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return 'xlsright';
    }
}
