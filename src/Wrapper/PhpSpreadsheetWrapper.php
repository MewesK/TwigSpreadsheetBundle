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
     * @var int|null
     */
    private $cellIndex;
    /**
     * @var int|null
     */
    private $rowIndex;

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
        $this->drawingWrapper = new DrawingWrapper($context, $environment, $this->sheetWrapper, $this->headerFooterWrapper);
    }

    /**
     * @param array $context
     *
     * @return array
     */
    public static function fixContext(array $context): array
    {
        if (!isset($context[self::INSTANCE_KEY]) && isset($context['varargs']) && is_array($context['varargs'])) {
            /**
             * @var array $args
             */
            $args = $context['varargs'];
            foreach ($args as $arg) {
                if ($arg instanceof self) {
                    $context[self::INSTANCE_KEY] = $arg;
                }
            }
        }

        return $context;
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
     * @param array           $properties
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \RuntimeException
     */
    public function startSheet($index = null, array $properties = [])
    {
        $this->sheetWrapper->start($index, $properties);
    }

    public function endSheet()
    {
        $this->sheetWrapper->end();
    }

    public function startRow()
    {
        $this->rowWrapper->start($this->rowIndex);
    }

    public function endRow()
    {
        $this->rowWrapper->end();
    }

    /**
     * @param null|mixed $value
     * @param array      $properties
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \RuntimeException
     */
    public function startCell($value = null, array $properties = [])
    {
        $this->cellWrapper->start($this->cellIndex, $value, $properties);
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
     * @param array  $properties
     *
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

    /**
     * @return int|null
     */
    public function getCellIndex()
    {
        return $this->cellIndex;
    }

    /**
     * @param int|null $cellIndex
     */
    public function setCellIndex(int $cellIndex = null)
    {
        $this->cellIndex = $cellIndex;
    }

    /**
     * @return int|null
     */
    public function getRowIndex()
    {
        return $this->rowIndex;
    }

    /**
     * @param int|null $rowIndex
     */
    public function setRowIndex(int $rowIndex = null)
    {
        $this->rowIndex = $rowIndex;
    }
}
