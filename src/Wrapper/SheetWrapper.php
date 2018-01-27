<?php

namespace MewesK\TwigSpreadsheetBundle\Wrapper;

use PhpOffice\PhpSpreadsheet\Exception;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\ColumnDimension;
use PhpOffice\PhpSpreadsheet\Worksheet\RowDimension;

/**
 * Class SheetWrapper.
 */
class SheetWrapper extends BaseWrapper
{
    /**
     * @var int
     */
    const COLUMN_DEFAULT = 1;
    /**
     * @var int
     */
    const ROW_DEFAULT = 1;

    /**
     * @var DocumentWrapper
     */
    protected $documentWrapper;

    /**
     * @var Worksheet|null
     */
    protected $object;
    /**
     * @var null|int
     */
    protected $row;
    /**
     * @var null|int
     */
    protected $column;

    /**
     * SheetWrapper constructor.
     *
     * @param array             $context
     * @param \Twig_Environment $environment
     * @param DocumentWrapper   $documentWrapper
     */
    public function __construct(array $context, \Twig_Environment $environment, DocumentWrapper $documentWrapper)
    {
        parent::__construct($context, $environment);

        $this->documentWrapper = $documentWrapper;

        $this->object = null;
        $this->row = null;
        $this->column = null;
    }

    /**
     * @param int|string|null $index
     * @param array $properties
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \RuntimeException
     * @throws \LogicException
     */
    public function start($index, array $properties = [])
    {
        if ($this->documentWrapper->getObject() === null) {
            throw new \LogicException();
        }

        if (is_int($index) && $index < $this->documentWrapper->getObject()->getSheetCount()) {
            $this->object = $this->documentWrapper->getObject()->setActiveSheetIndex($index);
        } elseif (is_string($index)) {
            if (!$this->documentWrapper->getObject()->sheetNameExists($index)) {
                // create new sheet with a name
                $this->documentWrapper->getObject()->createSheet()->setTitle($index);
            }
            $this->object = $this->documentWrapper->getObject()->setActiveSheetIndexByName($index);
        } else {
            // create new sheet without a name
            $this->documentWrapper->getObject()->createSheet();
            $this->object = $this->documentWrapper->getObject()->setActiveSheetIndex(0);
        }

        $this->parameters['index'] = $index;
        $this->parameters['properties'] = $properties;

        $this->setProperties($properties);
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \LogicException
     */
    public function end()
    {
        if ($this->object === null) {
            throw new \LogicException();
        }

        // auto-size columns
        if (
            isset($this->parameters['properties']['columnDimension']) &&
            is_array($this->parameters['properties']['columnDimension'])
        ) {
            /**
             * @var array $columnDimension
             */
            $columnDimension = $this->parameters['properties']['columnDimension'];
            foreach ($columnDimension as $key => $value) {
                if (isset($value['autoSize'])) {
                    if ($key === 'default') {
                        try {
                            $cellIterator = $this->object->getRowIterator()->current()->getCellIterator();
                            $cellIterator->setIterateOnlyExistingCells(true);

                            foreach ($cellIterator as $cell) {
                                $this->object->getColumnDimension($cell->getColumn())->setAutoSize($value['autoSize']);
                            }
                        } catch (Exception $e) {
                            // ignore exceptions thrown when no cells are defined
                        }
                    } else {
                        $this->object->getColumnDimension($key)->setAutoSize($value['autoSize']);
                    }
                }
            }
        }

        $this->object = null;
        $this->parameters = [];
        $this->row = null;
    }

    public function increaseRow()
    {
        $this->row = $this->row === null ? self::ROW_DEFAULT : $this->row + 1;
    }

    public function increaseColumn()
    {
        $this->column = $this->column === null ? self::COLUMN_DEFAULT : $this->column + 1;
    }

    /**
     * @return Worksheet
     */
    public function getObject(): Worksheet
    {
        return $this->object;
    }

    /**
     * @param Worksheet $object
     */
    public function setObject(Worksheet $object)
    {
        $this->object = $object;
    }

    /**
     * @return int|null
     */
    public function getRow()
    {
        return $this->row;
    }

    /**
     * @param int|null $row
     */
    public function setRow($row)
    {
        $this->row = $row;
    }

    /**
     * @return int|null
     */
    public function getColumn()
    {
        return $this->column;
    }

    /**
     * @param int|null $column
     */
    public function setColumn($column)
    {
        $this->column = $column;
    }

    /**
     * {@inheritdoc}
     */
    protected function configureMappings(): array
    {
        return [
            'autoFilter' => function ($value) { $this->object->setAutoFilter($value); },
            'columnDimension' => [
                '__multi' => function ($column = 'default'): ColumnDimension {
                    return $column === 'default' ?
                        $this->object->getDefaultColumnDimension() :
                        $this->object->getColumnDimension($column);
                },
                'autoSize' => function ($value, ColumnDimension $object) { $object->setAutoSize($value); },
                'collapsed' => function ($value, ColumnDimension $object) { $object->setCollapsed($value); },
                'columnIndex' => function ($value, ColumnDimension $object) { $object->setColumnIndex($value); },
                'outlineLevel' => function ($value, ColumnDimension $object) { $object->setOutlineLevel($value); },
                'visible' => function ($value, ColumnDimension $object) { $object->setVisible($value); },
                'width' => function ($value, ColumnDimension $object) { $object->setWidth($value); },
                'xfIndex' => function ($value, ColumnDimension $object) { $object->setXfIndex($value); },
            ],
            'pageMargins' => [
                'top' => function ($value) { $this->object->getPageMargins()->setTop($value); },
                'bottom' => function ($value) { $this->object->getPageMargins()->setBottom($value); },
                'left' => function ($value) { $this->object->getPageMargins()->setLeft($value); },
                'right' => function ($value) { $this->object->getPageMargins()->setRight($value); },
                'header' => function ($value) { $this->object->getPageMargins()->setHeader($value); },
                'footer' => function ($value) { $this->object->getPageMargins()->setFooter($value); },
            ],
            'pageSetup' => [
                'fitToHeight' => function ($value) { $this->object->getPageSetup()->setFitToHeight($value); },
                'fitToPage' => function ($value) { $this->object->getPageSetup()->setFitToPage($value); },
                'fitToWidth' => function ($value) { $this->object->getPageSetup()->setFitToWidth($value); },
                'horizontalCentered' => function ($value) { $this->object->getPageSetup()->setHorizontalCentered($value); },
                'orientation' => function ($value) { $this->object->getPageSetup()->setOrientation($value); },
                'paperSize' => function ($value) { $this->object->getPageSetup()->setPaperSize($value); },
                'printArea' => function ($value) { $this->object->getPageSetup()->setPrintArea($value); },
                'scale' => function ($value) { $this->object->getPageSetup()->setScale($value); },
                'verticalCentered' => function ($value) { $this->object->getPageSetup()->setVerticalCentered($value); },
            ],
            'printGridlines' => function ($value) { $this->object->setPrintGridlines($value); },
            'protection' => [
                'autoFilter' => function ($value) { $this->object->getProtection()->setAutoFilter($value); },
                'deleteColumns' => function ($value) { $this->object->getProtection()->setDeleteColumns($value); },
                'deleteRows' => function ($value) { $this->object->getProtection()->setDeleteRows($value); },
                'formatCells' => function ($value) { $this->object->getProtection()->setFormatCells($value); },
                'formatColumns' => function ($value) { $this->object->getProtection()->setFormatColumns($value); },
                'formatRows' => function ($value) { $this->object->getProtection()->setFormatRows($value); },
                'insertColumns' => function ($value) { $this->object->getProtection()->setInsertColumns($value); },
                'insertHyperlinks' => function ($value) { $this->object->getProtection()->setInsertHyperlinks($value); },
                'insertRows' => function ($value) { $this->object->getProtection()->setInsertRows($value); },
                'objects' => function ($value) { $this->object->getProtection()->setObjects($value); },
                'password' => function ($value) { $this->object->getProtection()->setPassword($value); },
                'pivotTables' => function ($value) { $this->object->getProtection()->setPivotTables($value); },
                'scenarios' => function ($value) { $this->object->getProtection()->setScenarios($value); },
                'selectLockedCells' => function ($value) { $this->object->getProtection()->setSelectLockedCells($value); },
                'selectUnlockedCells' => function ($value) { $this->object->getProtection()->setSelectUnlockedCells($value); },
                'sheet' => function ($value) { $this->object->getProtection()->setSheet($value); },
                'sort' => function ($value) { $this->object->getProtection()->setSort($value); },
            ],
            'rightToLeft' => function ($value) { $this->object->setRightToLeft($value); },
            'rowDimension' => [
                '__multi' => function ($column = 'default'): RowDimension {
                    return $column === 'default' ?
                        $this->object->getDefaultRowDimension() :
                        $this->object->getRowDimension($column);
                },
                'collapsed' => function ($value, RowDimension $object) { $object->setCollapsed($value); },
                'outlineLevel' => function ($value, RowDimension $object) { $object->setOutlineLevel($value); },
                'rowHeight' => function ($value, RowDimension $object) { $object->setRowHeight($value); },
                'rowIndex' => function ($value, RowDimension $object) { $object->setRowIndex($value); },
                'visible' => function ($value, RowDimension $object) { $object->setVisible($value); },
                'xfIndex' => function ($value, RowDimension $object) { $object->setXfIndex($value); },
                'zeroHeight' => function ($value, RowDimension $object) { $object->setZeroHeight($value); },
            ],
            'sheetState' => function ($value) { $this->object->setSheetState($value); },
            'showGridlines' => function ($value) { $this->object->setShowGridlines($value); },
            'tabColor' => function ($value) { $this->object->getTabColor()->setRGB($value); },
            'zoomScale' => function ($value) { $this->object->getSheetView()->setZoomScale($value); },
        ];
    }
}
