<?php

namespace MewesK\TwigSpreadsheetBundle\Wrapper;

/**
 * Class PhpSpreadsheetWrapper.
 */
class PhpSpreadsheetWrapper
{
    /**
     * @var string
     */
    const INSTANCE_KEY = '_tsb';

    /**
     * Copies the PhpSpreadsheetWrapper instance from 'varargs' to '_tsb'. This is necessary for all Twig code running
     * in sub-functions (e.g. block, macro, ...) since the root context is lost. To fix the sub-context a reference to
     * the PhpSpreadsheetWrapper instance is included in all function calls.
     *
     * @param array $context
     *
     * @return array
     */
    public static function fixContext(array $context): array
    {
        if (!isset($context[self::INSTANCE_KEY]) && isset($context['varargs']) && \is_array($context['varargs'])) {
            foreach ($context['varargs'] as $arg) {
                if ($arg instanceof self) {
                    $context[self::INSTANCE_KEY] = $arg;
                    break;
                }
            }
        }
        return $context;
    }

    /**
     * @var DocumentWrapper
     */
    private $documentWrapper;
    /**
     * @var SheetWrapper
     */
    private $sheetWrapper;
    /**
     * @var RowWrapper
     */
    private $rowWrapper;
    /**
     * @var CellWrapper
     */
    private $cellWrapper;
    /**
     * @var HeaderFooterWrapper
     */
    private $headerFooterWrapper;
    /**
     * @var DrawingWrapper
     */
    private $drawingWrapper;

    /**
     * PhpSpreadsheetWrapper constructor.
     *
     * @param array             $context
     * @param \Twig_Environment $environment
     * @param array             $attributes
     */
    public function __construct(array $context, \Twig_Environment $environment, array $attributes = [])
    {
        $this->documentWrapper = new DocumentWrapper($context, $environment, $attributes);
        $this->sheetWrapper = new SheetWrapper($context, $environment, $this->documentWrapper);
        $this->rowWrapper = new RowWrapper($context, $environment, $this->sheetWrapper);
        $this->cellWrapper = new CellWrapper($context, $environment, $this->sheetWrapper);
        $this->headerFooterWrapper = new HeaderFooterWrapper($context, $environment, $this->sheetWrapper);
        $this->drawingWrapper = new DrawingWrapper($context, $environment, $this->sheetWrapper, $this->headerFooterWrapper, $attributes);
    }

    /**
     * @return int|null
     */
    public function getCurrentColumn()
    {
        return $this->sheetWrapper->getColumn();
    }

    /**
     * @return int|null
     */
    public function getCurrentRow()
    {
        return $this->sheetWrapper->getRow();
    }

    /**
     * @param array $properties
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \RuntimeException
     */
    public function startDocument(array $properties = [])
    {
        $this->documentWrapper->start($properties);
    }

    /**
     * @throws \RuntimeException
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public function endDocument()
    {
        $this->documentWrapper->end();
    }

    /**
     * @param int|string|null $index
     * @param array $properties
     *
     * @throws \LogicException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \RuntimeException
     */
    public function startSheet($index = null, array $properties = [])
    {
        $this->sheetWrapper->start($index, $properties);
    }

    /**
     * @throws \LogicException
     * @throws \Exception
     */
    public function endSheet()
    {
        $this->sheetWrapper->end();
    }

    /**
     * @param int|null $index
     *
     * @throws \LogicException
     */
    public function startRow(int $index = null)
    {
        $this->rowWrapper->start($index);
    }

    /**
     * @throws \LogicException
     */
    public function endRow()
    {
        $this->rowWrapper->end();
    }

    /**
     * @param int|null   $index
     * @param array      $properties
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function startCell(int $index = null, array $properties = [])
    {
        $this->cellWrapper->start($index, $properties);
    }

    /**
     * @param null|mixed $value
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function setCellValue($value = null)
    {
        $this->cellWrapper->value($value);
    }

    public function endCell()
    {
        $this->cellWrapper->end();
    }

    /**
     * @param string      $baseType
     * @param string|null $type
     * @param array       $properties
     *
     * @throws \LogicException
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     */
    public function startHeaderFooter(string $baseType, string $type = null, array $properties = [])
    {
        $this->headerFooterWrapper->start($baseType, $type, $properties);
    }

    /**
     * @throws \LogicException
     * @throws \InvalidArgumentException
     */
    public function endHeaderFooter()
    {
        $this->headerFooterWrapper->end();
    }

    /**
     * @param null|string $type
     * @param array       $properties
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function startAlignment(string $type = null, array $properties = [])
    {
        $this->headerFooterWrapper->startAlignment($type, $properties);
    }

    /**
     * @param null|string $value
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     */
    public function endAlignment(string $value = null)
    {
        $this->headerFooterWrapper->endAlignment($value);
    }

    /**
     * @param string $path
     * @param array $properties
     *
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function startDrawing(string $path, array $properties = [])
    {
        $this->drawingWrapper->start($path, $properties);
    }

    public function endDrawing()
    {
        $this->drawingWrapper->end();
    }
}
