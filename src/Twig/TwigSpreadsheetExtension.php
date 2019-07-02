<?php

namespace Erelke\TwigSpreadsheetBundle\Twig;

use Erelke\TwigSpreadsheetBundle\Helper\Arrays;
use Erelke\TwigSpreadsheetBundle\Twig\NodeVisitor\MacroContextNodeVisitor;
use Erelke\TwigSpreadsheetBundle\Twig\NodeVisitor\SyntaxCheckNodeVisitor;
use Erelke\TwigSpreadsheetBundle\Twig\TokenParser\AlignmentTokenParser;
use Erelke\TwigSpreadsheetBundle\Twig\TokenParser\CellTokenParser;
use Erelke\TwigSpreadsheetBundle\Twig\TokenParser\DocumentTokenParser;
use Erelke\TwigSpreadsheetBundle\Twig\TokenParser\DrawingTokenParser;
use Erelke\TwigSpreadsheetBundle\Twig\TokenParser\HeaderFooterTokenParser;
use Erelke\TwigSpreadsheetBundle\Twig\TokenParser\RowTokenParser;
use Erelke\TwigSpreadsheetBundle\Twig\TokenParser\SheetTokenParser;
use Erelke\TwigSpreadsheetBundle\Wrapper\HeaderFooterWrapper;
use Erelke\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper;
use InvalidArgumentException;
use function is_array;
use Twig\Error\RuntimeError as Twig_Error_Runtime;
use Twig\Extension\AbstractExtension as Twig_Extension;
use Twig\TwigFunction as Twig_SimpleFunction;

/**
 * Class TwigSpreadsheetExtension.
 */
class TwigSpreadsheetExtension extends Twig_Extension
{
    /**
     * @var array
     */
    private $attributes;

    /**
     * TwigSpreadsheetExtension constructor.
     *
     * @param array $attributes
     */
    public function __construct(array $attributes = [])
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return [
            new Twig_SimpleFunction('xlsmergestyles', [$this, 'mergeStyles']),
            new Twig_SimpleFunction('xlscellindex', [$this, 'getCurrentColumn'], ['needs_context' => true]),
            new Twig_SimpleFunction('xlsrowindex', [$this, 'getCurrentRow'], ['needs_context' => true]),
        ];
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    public function getTokenParsers()
    {
        return [
            new AlignmentTokenParser([], HeaderFooterWrapper::ALIGNMENT_CENTER),
            new AlignmentTokenParser([], HeaderFooterWrapper::ALIGNMENT_LEFT),
            new AlignmentTokenParser([], HeaderFooterWrapper::ALIGNMENT_RIGHT),
            new CellTokenParser(),
            new DocumentTokenParser($this->attributes),
            new DrawingTokenParser(),
            new HeaderFooterTokenParser([], HeaderFooterWrapper::BASETYPE_FOOTER),
            new HeaderFooterTokenParser([], HeaderFooterWrapper::BASETYPE_HEADER),
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
     * @throws Twig_Error_Runtime
     *
     * @return array
     */
    public function mergeStyles(array $style1, array $style2): array
    {
        if (!is_array($style1) || !is_array($style2)) {
            throw new Twig_Error_Runtime('The xlsmergestyles function only works with arrays.');
        }
        return Arrays::mergeRecursive($style1, $style2);
    }

    /**
     * @param array $context
     *
     * @throws Twig_Error_Runtime
     *
     * @return int|null
     */
    public function getCurrentColumn(array $context) {
        if (!isset($context[PhpSpreadsheetWrapper::INSTANCE_KEY])) {
            throw new Twig_Error_Runtime('The PhpSpreadsheetWrapper instance is missing.');
        }
        return $context[PhpSpreadsheetWrapper::INSTANCE_KEY]->getCurrentColumn();
    }

    /**
     * @param array $context
     *
     * @throws Twig_Error_Runtime
     *
     * @return int|null
     */
    public function getCurrentRow(array $context) {
        if (!isset($context[PhpSpreadsheetWrapper::INSTANCE_KEY])) {
            throw new Twig_Error_Runtime('The PhpSpreadsheetWrapper instance is missing.');
        }
        return $context[PhpSpreadsheetWrapper::INSTANCE_KEY]->getCurrentRow();
    }
}
