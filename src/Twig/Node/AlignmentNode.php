<?php

namespace Erelke\TwigSpreadsheetBundle\Twig\Node;

use Erelke\TwigSpreadsheetBundle\Wrapper\HeaderFooterWrapper;
use InvalidArgumentException;
use Twig\Compiler as Twig_Compiler;

/**
 * Class AlignmentNode.
 */
class AlignmentNode extends BaseNode
{
    /**
     * @var string
     */
    private $alignment;

    /**
     * AlignmentNode constructor.
     *
     * @param array       $nodes
     * @param array       $attributes
     * @param int         $lineNo
     * @param string|null $tag
     * @param string      $alignment
     *
     * @throws InvalidArgumentException
     */
    public function __construct(array $nodes = [], array $attributes = [], int $lineNo = 0, string $tag = null, string $alignment = HeaderFooterWrapper::ALIGNMENT_CENTER)
    {
        parent::__construct($nodes, $attributes, $lineNo, $tag);

        $this->alignment = HeaderFooterWrapper::validateAlignment(strtolower($alignment));
    }

    /**
     * @param Twig_Compiler $compiler
     */
    public function compile(Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write(self::CODE_FIX_CONTEXT)
            ->write(self::CODE_INSTANCE.'->startAlignment(')
                ->repr($this->alignment)
            ->raw(');'.PHP_EOL)
            ->write("ob_start();\n")
            ->subcompile($this->getNode('body'))
            ->write('$alignmentValue = trim(ob_get_clean());'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->endAlignment($alignmentValue);'.PHP_EOL)
            ->write('unset($alignmentValue);'.PHP_EOL);
    }

    /**
     * {@inheritdoc}
     */
    public function getAllowedParents(): array
    {
        return [
            HeaderFooterNode::class,
        ];
    }
}
