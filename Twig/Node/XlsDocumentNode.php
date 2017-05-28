<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

use MewesK\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper;
use Twig_Compiler;
use Twig_Node;
use Twig_Node_Expression;

/**
 * Class XlsDocumentNode
 *
 * @package MewesK\TwigSpreadsheetBundle\Twig\Node
 */
class XlsDocumentNode extends Twig_Node implements SyntaxAwareNodeInterface
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
     * @param Twig_Node_Expression $properties
     * @param Twig_Node $body
     * @param int $line
     * @param string $tag
     * @param bool $preCalculateFormulas
     * @param null|string $diskCachingDirectory
     */
    public function __construct(Twig_Node_Expression $properties, Twig_Node $body, $line = 0, $tag = 'xlsdocument', $preCalculateFormulas = true, $diskCachingDirectory = null)
    {
        parent::__construct(['properties' => $properties, 'body' => $body], [], $line, $tag);
        $this->preCalculateFormulas = $preCalculateFormulas;
        $this->diskCachingDirectory = $diskCachingDirectory;
    }

    /**
     * @param Twig_Compiler $compiler
     */
    public function compile(Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write("ob_start();\n")
            ->write('$documentProperties = ')
            ->subcompile($this->getNode('properties'))
            ->raw(';' . PHP_EOL)
            ->write('$context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\'] = new ' . PhpSpreadsheetWrapper::class . '($context, $this->env);' . PHP_EOL)
            ->write('$context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\']->startDocument($documentProperties);' . PHP_EOL)
            ->write('unset($documentProperties);' . PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->addDebugInfo($this)
            ->write("ob_end_clean();\n")
            ->write('$context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\']->endDocument(' .
                ($this->preCalculateFormulas ? 'true' : 'false') . ', ' .
                ($this->diskCachingDirectory ? '\'' . $this->diskCachingDirectory . '\'' : 'null') . ');' . PHP_EOL)
            ->write('unset($context[\'' . PhpSpreadsheetWrapper::INSTANCE_KEY . '\']);' . PHP_EOL);
    }

    /**
     * @return string[]
     */
    public function getAllowedParents()
    {
        return [];
    }

    /**
     * @return bool
     */
    public function canContainText()
    {
        return false;
    }
}
