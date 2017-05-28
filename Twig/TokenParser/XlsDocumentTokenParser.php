<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\TokenParser;

use MewesK\TwigSpreadsheetBundle\Twig\Node\XlsDocumentNode;
use MewesK\TwigSpreadsheetBundle\Twig\NodeHelper;
use Twig_Token;

/**
 * Class XlsDocumentTokenParser
 * @package MewesK\TwigSpreadsheetBundle\Twig\TokenParser
 */
class XlsDocumentTokenParser extends AbstractTokenParser
{
    /**
     * @var bool
     */
    private $preCalculateFormulas;
    /**
     * @var null|string
     */
    private $diskCachingDirectory;

    /**
     * @param bool $preCalculateFormulas
     * @param null|string $diskCachingDirectory
     */
    public function __construct($preCalculateFormulas = true, $diskCachingDirectory = null)
    {
        $this->preCalculateFormulas = $preCalculateFormulas;
        $this->diskCachingDirectory = $diskCachingDirectory;
    }

    /**
     * @param Twig_Token $token
     *
     * @return XlsDocumentNode
     * @throws \Twig_Error_Syntax
     */
    public function parse(Twig_Token $token)
    {
        // parse attributes
        $properties = $this->parseProperties($token);
        $this->parser->getStream()->expect(Twig_Token::BLOCK_END_TYPE);

        // parse body
        $body = $this->parseBody();
        NodeHelper::removeTextNodesRecursively($body, $this->parser);
        NodeHelper::fixMacroCallsRecursively($body);

        // return node
        return new XlsDocumentNode($properties, $body, $token->getLine(), $this->getTag(), $this->preCalculateFormulas, $this->diskCachingDirectory);
    }

    /**
     * @return string
     */
    public function getTag()
    {
        return 'xlsdocument';
    }
}
