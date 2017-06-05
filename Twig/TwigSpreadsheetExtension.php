<?php

namespace MewesK\TwigSpreadsheetBundle\Twig;

use MewesK\TwigSpreadsheetBundle\Twig\NodeVisitor\MacroContextNodeVisitor;
use MewesK\TwigSpreadsheetBundle\Twig\NodeVisitor\SyntaxCheckNodeVisitor;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\CellTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\CenterTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\DocumentTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\DrawingTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\FooterTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\HeaderTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\LeftTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\RightTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\RowTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\SheetTokenParser;

/**
 * Class TwigSpreadsheetExtension.
 */
class TwigSpreadsheetExtension extends \Twig_Extension
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
     * @param bool        $preCalculateFormulas
     * @param null|string $diskCachingDirectory
     */
    public function __construct($preCalculateFormulas = true, $diskCachingDirectory = null)
    {
        $this->preCalculateFormulas = $preCalculateFormulas;
        $this->diskCachingDirectory = $diskCachingDirectory;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('xlsmergestyles', [$this, 'mergeStyles']),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return [
            new CellTokenParser(),
            new CenterTokenParser(),
            new DocumentTokenParser([
                'preCalculateFormulas' => $this->preCalculateFormulas,
                'diskCachingDirectory' => $this->diskCachingDirectory,
            ]),
            new DrawingTokenParser(),
            new FooterTokenParser(),
            new HeaderTokenParser(),
            new LeftTokenParser(),
            new RightTokenParser(),
            new RowTokenParser(),
            new SheetTokenParser(),
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getNodeVisitors()
    {
        return [
            new MacroContextNodeVisitor(),
            new SyntaxCheckNodeVisitor(),
        ];
    }

    /**
     * @param array $style1
     * @param array $style2
     *
     * @throws \Twig_Error_Runtime
     *
     * @return array
     */
    public function mergeStyles(array $style1, array $style2): array
    {
        if (!is_array($style1) || !is_array($style2)) {
            throw new \Twig_Error_Runtime('The xlsmergestyles function only works with arrays.');
        }

        return array_merge_recursive($style1, $style2);
    }
}
