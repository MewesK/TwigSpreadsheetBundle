<?php

namespace MewesK\TwigSpreadsheetBundle\Twig;

use MewesK\TwigSpreadsheetBundle\Twig\NodeVisitor\MacroContextFixNodeVisitor;
use MewesK\TwigSpreadsheetBundle\Twig\NodeVisitor\SyntaxCheckNodeVisitor;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\XlsCellTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\XlsCenterTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\XlsDocumentTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\XlsDrawingTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\XlsFooterTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\XlsHeaderTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\XlsLeftTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\XlsRightTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\XlsRowTokenParser;
use MewesK\TwigSpreadsheetBundle\Twig\TokenParser\XlsSheetTokenParser;
use Twig\Error\RuntimeError;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

/**
 * Class TwigSpreadsheetExtension
 *
 * @package MewesK\TwigSpreadsheetBundle\Twig
 */
class TwigSpreadsheetExtension extends AbstractExtension
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
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new TwigFunction('xlsmergestyles', [$this, 'mergeStyles'])
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getTokenParsers()
    {
        return [
            new XlsCellTokenParser(),
            new XlsCenterTokenParser(),
            new XlsDocumentTokenParser($this->preCalculateFormulas, $this->diskCachingDirectory),
            new XlsDrawingTokenParser(),
            new XlsFooterTokenParser(),
            new XlsHeaderTokenParser(),
            new XlsLeftTokenParser(),
            new XlsRightTokenParser(),
            new XlsRowTokenParser(),
            new XlsSheetTokenParser()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getNodeVisitors()
    {
        return [
            new MacroContextFixNodeVisitor(),
            new SyntaxCheckNodeVisitor()
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'spreadsheet_extension';
    }

    /**
     * @param array $style1
     * @param array $style2
     *
     * @return array
     * @throws \Twig\Error\RuntimeError
     */
    public function mergeStyles(array $style1, array $style2)
    {
        if (!is_array($style1) || !is_array($style2)) {
            throw new RuntimeError('The xlsmergestyles function only works with arrays.');
        }

        return array_merge_recursive($style1, $style2);
    }
} 
