<?php

namespace MewesK\TwigSpreadsheetBundle\Wrapper;

use PhpOffice\PhpSpreadsheet\Cell;

/**
 * Class CellWrapper.
 */
class CellWrapper extends BaseWrapper
{
    /**
     * @var array
     */
    protected $context;
    /**
     * @var \Twig_Environment
     */
    protected $environment;
    /**
     * @var SheetWrapper
     */
    protected $sheetWrapper;

    /**
     * @var Cell|null
     */
    protected $object;
    /**
     * @var array
     */
    protected $attributes;
    /**
     * @var array
     */
    protected $mappings;

    /**
     * CellWrapper constructor.
     *
     * @param array             $context
     * @param \Twig_Environment $environment
     * @param SheetWrapper      $sheetWrapper
     */
    public function __construct(array $context, \Twig_Environment $environment, SheetWrapper $sheetWrapper)
    {
        $this->context = $context;
        $this->environment = $environment;
        $this->sheetWrapper = $sheetWrapper;

        $this->object = null;
        $this->attributes = [];
        $this->mappings = [];

        $this->initializeMappings();
    }

    /**
     * @param int|null   $index
     * @param mixed|null $value
     * @param array      $properties
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \RuntimeException
     */
    public function start(int $index = null, $value = null, array $properties = [])
    {
        if ($this->sheetWrapper->getObject() === null) {
            throw new \LogicException();
        }

        if ($index === null) {
            $this->sheetWrapper->increaseColumn();
        } else {
            $this->sheetWrapper->setColumn($index);
        }

        $this->object = $this->sheetWrapper->getObject()->getCellByColumnAndRow($this->sheetWrapper->getColumn(),
            $this->sheetWrapper->getRow());

        if ($value !== null) {
            if (isset($properties['dataType'])) {
                $this->object->setValueExplicit($value, $properties['dataType']);
            } else {
                $this->object->setValue($value);
            }
        }

        $this->attributes['value'] = $value;
        $this->attributes['properties'] = $properties;

        $this->setProperties($properties, $this->mappings);
    }

    public function end()
    {
        $this->object = null;
        $this->attributes = [];
    }

    /**
     * @return Cell|null
     */
    public function getObject()
    {
        return $this->object;
    }

    /**
     * @param Cell|null $object
     */
    public function setObject(Cell $object = null)
    {
        $this->object = $object;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * @return array
     */
    public function getMappings(): array
    {
        return $this->mappings;
    }

    /**
     * @param array $mappings
     */
    public function setMappings(array $mappings)
    {
        $this->mappings = $mappings;
    }

    protected function initializeMappings()
    {
        $this->mappings['break'] = function ($value) {
            $this->sheetWrapper->getObject()->setBreak($this->object->getCoordinate(), $value);
        };
        $this->mappings['dataType'] = function ($value) {
            $this->object->setDataType($value);
        };
        $this->mappings['dataValidation']['allowBlank'] = function ($value) {
            $this->object->getDataValidation()->setAllowBlank($value);
        };
        $this->mappings['dataValidation']['error'] = function ($value) {
            $this->object->getDataValidation()->setError($value);
        };
        $this->mappings['dataValidation']['errorStyle'] = function ($value) {
            $this->object->getDataValidation()->setErrorStyle($value);
        };
        $this->mappings['dataValidation']['errorTitle'] = function ($value) {
            $this->object->getDataValidation()->setErrorTitle($value);
        };
        $this->mappings['dataValidation']['formula1'] = function ($value) {
            $this->object->getDataValidation()->setFormula1($value);
        };
        $this->mappings['dataValidation']['formula2'] = function ($value) {
            $this->object->getDataValidation()->setFormula2($value);
        };
        $this->mappings['dataValidation']['operator'] = function ($value) {
            $this->object->getDataValidation()->setOperator($value);
        };
        $this->mappings['dataValidation']['prompt'] = function ($value) {
            $this->object->getDataValidation()->setPrompt($value);
        };
        $this->mappings['dataValidation']['promptTitle'] = function ($value) {
            $this->object->getDataValidation()->setPromptTitle($value);
        };
        $this->mappings['dataValidation']['showDropDown'] = function ($value) {
            $this->object->getDataValidation()->setShowDropDown($value);
        };
        $this->mappings['dataValidation']['showErrorMessage'] = function ($value) {
            $this->object->getDataValidation()->setShowErrorMessage($value);
        };
        $this->mappings['dataValidation']['showInputMessage'] = function ($value) {
            $this->object->getDataValidation()->setShowInputMessage($value);
        };
        $this->mappings['dataValidation']['type'] = function ($value) {
            $this->object->getDataValidation()->setType($value);
        };
        $this->mappings['merge'] = function ($value) {
            if (is_int($value)) {
                $value = Cell::stringFromColumnIndex($value).$this->sheetWrapper->getRow();
            }
            $this->sheetWrapper->getObject()->mergeCells(sprintf('%s:%s', $this->object->getCoordinate(), $value));
        };
        $this->mappings['style'] = function ($value) {
            $this->sheetWrapper->getObject()->getStyle($this->object->getCoordinate())->applyFromArray($value);
        };
        $this->mappings['url'] = function ($value) {
            $this->object->getHyperlink()->setUrl($value);
        };
    }
}
