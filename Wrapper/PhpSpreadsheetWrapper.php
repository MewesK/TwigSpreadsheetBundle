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
     * @return mixed
     */
    public static function fixContext(array $context) {
        if (!array_key_exists(self::INSTANCE_KEY, $context) && is_array($context['varargs'])) {
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
     * @var int
     */
    private $cellIndex;
    /**
     * @var int
     */
    private $rowIndex;

    /**
     * PhpSpreadsheetWrapper constructor.
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
     * @param null|array $properties
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function startDocument(array $properties = null)
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
     * @param string $index
     * @param null|array $properties
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function startSheet($index, array $properties = null)
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
     * @param null|array $properties
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function startCell($value = null, array $properties = null)
    {
        $this->cellWrapper->start($this->cellIndex, $value, $properties);
    }

    public function endCell()
    {
        $this->cellWrapper->end();
    }

    /**
     * @param string $type
     * @param null|array $properties
     * @throws \LogicException
     */
    public function startHeaderFooter($type, array $properties = null)
    {
        $this->headerFooterWrapper->start($type, $properties);
    }

    public function endHeaderFooter()
    {
        $this->headerFooterWrapper->end();
    }

    /**
     * @param null|string $type
     * @param null|array $properties
     * @throws \InvalidArgumentException
     */
    public function startAlignment($type = null, array $properties = null)
    {
        $this->headerFooterWrapper->startAlignment($type, $properties);
    }

    /**
     * @param null|string $value
     * @throws \InvalidArgumentException
     */
    public function endAlignment($value = null)
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
    public function startDrawing($path, array $properties = null)
    {
        $this->drawingWrapper->start($path, $properties);
    }

    public function endDrawing()
    {
        $this->drawingWrapper->end();
    }

    // Getter / Setter

    /**
     * @return int
     */
    public function getCellIndex()
    {
        return $this->cellIndex;
    }

    /**
     * @param int $cellIndex
     */
    public function setCellIndex($cellIndex)
    {
        $this->cellIndex = $cellIndex;
    }

    /**
     * @return int
     */
    public function getRowIndex()
    {
        return $this->rowIndex;
    }

    /**
     * @param int $rowIndex
     */
    public function setRowIndex($rowIndex)
    {
        $this->rowIndex = $rowIndex;
    }
}
