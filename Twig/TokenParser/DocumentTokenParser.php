<?php

namespace MewesK\TwigSpreadsheetBundle\Twig\TokenParser;

use MewesK\TwigSpreadsheetBundle\Twig\Node\DocumentNode;

/**
 * Class DocumentTokenParser.
 */
class DocumentTokenParser extends BaseTokenParser
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
    public function configureParameters(\Twig_Token $token): array
    {
        return [
            'properties' => [
                'type' => self::PARAMETER_TYPE_ARRAY,
                'default' => new \Twig_Node_Expression_Array([], $token->getLine()),
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getAttributes(): array
    {
        return [
            'preCalculateFormulas' => $this->preCalculateFormulas,
            'diskCachingDirectory' => $this->diskCachingDirectory,
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function getNode(): string
    {
        return DocumentNode::class;
    }

    /**
     * {@inheritdoc}
     */
    public function getTag()
    {
        return 'xlsdocument';
    }
}
