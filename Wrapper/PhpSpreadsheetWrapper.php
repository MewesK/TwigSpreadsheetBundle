<?php

namespace MewesK\TwigSpreadsheetBundle\Wrapper;

/**
 * Class PhpSpreadsheetWrapper
 *
 * @package MewesK\TwigSpreadsheetBundle\Wrapper
 */
class PhpSpreadsheetWrapper
{
    const INSTANCE_KEY = '_tsb';

    /**
     * @param array $context
     * @return array
     */
    public static function fixContext(array $context): array
    {
        if (!isset($context[self::INSTANCE_KEY]) && isset($context['varargs']) && is_array($context['varargs'])) {
            foreach ($context['varargs'] as $arg) {
                if ($arg instanceof self) {
                    $context[self::INSTANCE_KEY] = $arg;
                }
            }
        }

        return $context;
    }

    /**
     * @var XlsDocumentWrapper
     */
    private $documentWrapper;
    /**
     * @var XlsSheetWrapper
     */
    private $sheetWrapper;
    /**
     * @var XlsRowWrapper
     */
    private $rowWrapper;
    /**
     * @var XlsCellWrapper
     */
    private $cellWrapper;
    /**
     * @var XlsHeaderFooterWrapper
     */
    private $headerFooterWrapper;
    /**
     * @var XlsDrawingWrapper
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
     * @param array $context
     * @param \Twig_Environment $environment
     */
    public function __construct(array $context = [], \Twig_Environment $environment)
    {
        $this->documentWrapper = new XlsDocumentWrapper($context, $environment);
        $this->sheetWrapper = new XlsSheetWrapper($context, $environment, $this->documentWrapper);
        $this->rowWrapper = new XlsRowWrapper($context, $environment, $this->sheetWrapper);
        $this->cellWrapper = new XlsCellWrapper($context, $environment, $this->sheetWrapper);
        $this->headerFooterWrapper = new XlsHeaderFooterWrapper($context, $environment, $this->sheetWrapper);
        $this->drawingWrapper = new XlsDrawingWrapper($context, $environment, $this->sheetWrapper, $this->headerFooterWrapper);
    }

    //
    // Tags
    //

    /**
     * @param array $properties
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
     */
    public function endDocument()
    {
        $this->documentWrapper->end();
    }

    /**
     * @param int|string|null $index
     * @param array $properties
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
     * @param array $properties
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
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
     * @param string $type
     * @param array $properties
     * @throws \LogicException
     */
    public function startHeaderFooter(string $type, array $properties = [])
    {
        $this->headerFooterWrapper->start($type, $properties);
    }

    public function endHeaderFooter()
    {
        $this->headerFooterWrapper->end();
    }

    /**
     * @param null|string $type
     * @param array $properties
     * @throws \InvalidArgumentException
     */
    public function startAlignment(string $type = null, array $properties = [])
    {
        $this->headerFooterWrapper->startAlignment($type, $properties);
    }

    /**
     * @param null|string $value
     * @throws \InvalidArgumentException
     */
    public function endAlignment(string $value = null)
    {
        $this->headerFooterWrapper->endAlignment($value);
    }

    /**
     * @param string $path
     * @param array $properties
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

    // Getter / Setter

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
