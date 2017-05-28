<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\TokenParser;

use MewesK\TwigSpreadsheetBundle\Twig\NodeHelper;
use Twig_Error_Syntax;
use Twig_Node;
use Twig_Node_Block;
use Twig_Node_BlockReference;
use Twig_Node_Print;
use Twig_Token;
use Twig_TokenParser;

/**
 * Class XlsBlockTokenParser
 * @package MewesK\TwigSpreadsheetBundle\Twig\TokenParser
 */
class XlsBlockTokenParser extends Twig_TokenParser
{
    /**
     * Based on final class method Twig_TokenParser_Block::parse
     *
     * @param Twig_Token $token
     * @return Twig_Node_BlockReference
     * @throws Twig_Error_Syntax
     */
    public function parse(Twig_Token $token)
    {
        $lineno = $token->getLine();
        $stream = $this->parser->getStream();
        $name = $stream->expect(Twig_Token::NAME_TYPE)->getValue();
        if ($this->parser->hasBlock($name)) {
            /** @noinspection PhpInternalEntityUsedInspection */
            /** @noinspection PhpUndefinedMethodInspection */
            throw new Twig_Error_Syntax(
                sprintf("The xlsblock '%s' has already been defined line %d.", $name, $this->parser->getBlock($name)->getTemplateLine()),
                $stream->getCurrent()->getLine(),
                $stream->getSourceContext()
            );
        }
        $this->parser->setBlock($name, $block = new Twig_Node_Block($name, new Twig_Node(array()), $lineno));
        $this->parser->pushLocalScope();
        $this->parser->pushBlockStack($name);

        if ($stream->nextIf(Twig_Token::BLOCK_END_TYPE)) {
            $body = $this->parser->subparse(array($this, 'decideBlockEnd'), true);
            if ($token = $stream->nextIf(Twig_Token::NAME_TYPE)) {
                $value = $token->getValue();

                if ($value !== $name) {
                    /** @noinspection PhpInternalEntityUsedInspection */
                    throw new Twig_Error_Syntax(
                        sprintf('Expected endxlsblock for block "%s" (but "%s" given).', $name, $value),
                        $stream->getCurrent()->getLine(),
                        $stream->getSourceContext()
                    );
                }
            }
        } else {
            $body = new Twig_Node(array(
                new Twig_Node_Print($this->parser->getExpressionParser()->parseExpression(), $lineno),
            ));
        }
        $stream->expect(Twig_Token::BLOCK_END_TYPE);

        $block->setNode('body', $body);
        $this->parser->popBlockStack();
        $this->parser->popLocalScope();

        $blockReference = new Twig_Node_BlockReference($name, $lineno, $this->getTag());

        /**
         * @var Twig_Node_Block $block
         */
        $block = $this->parser->getBlock($blockReference->getAttribute('name'));

        // prepare block
        NodeHelper::removeTextNodesRecursively($block, $this->parser);
        NodeHelper::fixMacroCallsRecursively($block);

        // mark for syntax checks
        foreach ($block->getIterator() as $node) {
            if ($node instanceof Twig_Node_Block) {
                $node->setAttribute('twigSpreadsheetBundle', true);
            }
        }

        return $blockReference;
    }

    /**
     * @param Twig_Token $token
     * @return bool
     */
    public function decideBlockEnd(Twig_Token $token)
    {
        return $token->test('endxlsblock');
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return 'xlsblock';
    }
}
