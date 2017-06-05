<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\Node;

use MewesK\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper;

/**
 * Class DocumentNode.
 */
class DocumentNode extends BaseNode
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
     * DocumentNode constructor.
     *
     * @param array       $nodes
     * @param array       $attributes
     * @param int         $lineno
     * @param string|null $tag
     */
    public function __construct(array $nodes = [], array $attributes = [], int $lineno = 0, string $tag = null)
    {
        $this->preCalculateFormulas = $attributes['preCalculateFormulas'] ?? false;
        $this->diskCachingDirectory = $attributes['diskCachingDirectory'] ?? null;

        unset($attributes['preCalculateFormulas'], $attributes['diskCachingDirectory']);

        parent::__construct($nodes, $attributes, $lineno, $tag);
    }

    /**
     * @param \Twig_Compiler $compiler
     */
    public function compile(\Twig_Compiler $compiler)
    {
        $compiler->addDebugInfo($this)
            ->write("ob_start();\n")
            ->write('$documentProperties = ')
            ->subcompile($this->getNode('properties'))
            ->raw(';'.PHP_EOL)
            ->write(self::CODE_INSTANCE.' = new '.PhpSpreadsheetWrapper::class.'($context, $this->env);'.PHP_EOL)
            ->write(self::CODE_INSTANCE.'->startDocument($documentProperties);'.PHP_EOL)
            ->write('unset($documentProperties);'.PHP_EOL)
            ->subcompile($this->getNode('body'))
            ->addDebugInfo($this)
            ->write("ob_end_clean();\n")
            ->write(self::CODE_INSTANCE.'->endDocument('.
                ($this->preCalculateFormulas ? 'true' : 'false').', '.
                ($this->diskCachingDirectory ? '\''.$this->diskCachingDirectory.'\'' : 'null').');'.PHP_EOL)
            ->write('unset('.self::CODE_INSTANCE.');'.PHP_EOL);
    }

    /**
     * @return string[]
     */
    public function getAllowedParents(): array
    {
        return [];
    }
}
