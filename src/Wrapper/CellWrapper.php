<?php

namespace MyWheels\TwigSpreadsheetBundle\Wrapper;

use PhpOffice\PhpSpreadsheet\Cell\Cell;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/**
 * Class CellWrapper.
 */
class CellWrapper extends BaseWrapper
{
    /**
     * @var SheetWrapper
     */
    protected $sheetWrapper;

    /**
     * @var Cell|null
     */
    protected $object;

    /**
     * CellWrapper constructor.
     *
     * @param array             $context
     * @param \Twig_Environment $environment
     * @param SheetWrapper      $sheetWrapper
     */
    public function __construct(array $context, \Twig_Environment $environment, SheetWrapper $sheetWrapper)
    {
        parent::__construct($context, $environment);

        $this->sheetWrapper = $sheetWrapper;

        $this->object = null;
    }

    /**
     * @param int|null $index
     * @param array $properties
     *
     * @throws \InvalidArgumentException
     * @throws \LogicException
     * @throws \RuntimeException
     */
    public function start(int $index = null, array $properties = [])
    {
        if ($this->sheetWrapper->getObject() === null) {
            throw new \LogicException();
        }

        if ($index === null) {
            $this->sheetWrapper->increaseColumn();
        } else {
            $this->sheetWrapper->setColumn($index);
        }

        $this->object = $this->sheetWrapper->getObject()->getCellByColumnAndRow(
            $this->sheetWrapper->getColumn(),
            $this->sheetWrapper->getRow());

        $this->parameters['value'] = null;
        $this->parameters['properties'] = $properties;
        $this->setProperties($properties);
    }

    /**
     * @param mixed|null $value
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function value($value = null)
    {
        if ($value !== null) {
            if (isset($this->parameters['properties']['dataType'])) {
                $this->object->setValueExplicit($value, $this->parameters['properties']['dataType']);
            } else {
                $this->object->setValue($value);
            }
        }

        $this->parameters['value'] = $value;
    }

    public function end()
    {
        $this->object = null;
        $this->parameters = [];
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
     * {@inheritdoc}
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    protected function configureMappings(): array
    {
        return [
            'break' => function ($value) { $this->sheetWrapper->getObject()->setBreak($this->object->getCoordinate(), $value); },
            'dataType' => function ($value) { $this->object->setDataType($value); },
            'dataValidation' => [
                'allowBlank' => function ($value) { $this->object->getDataValidation()->setAllowBlank($value); },
                'error' => function ($value) { $this->object->getDataValidation()->setError($value); },
                'errorStyle' => function ($value) { $this->object->getDataValidation()->setErrorStyle($value); },
                'errorTitle' => function ($value) { $this->object->getDataValidation()->setErrorTitle($value); },
                'formula1' => function ($value) { $this->object->getDataValidation()->setFormula1($value); },
                'formula2' => function ($value) { $this->object->getDataValidation()->setFormula2($value); },
                'operator' => function ($value) { $this->object->getDataValidation()->setOperator($value); },
                'prompt' => function ($value) { $this->object->getDataValidation()->setPrompt($value); },
                'promptTitle' => function ($value) { $this->object->getDataValidation()->setPromptTitle($value); },
                'showDropDown' => function ($value) { $this->object->getDataValidation()->setShowDropDown($value); },
                'showErrorMessage' => function ($value) { $this->object->getDataValidation()->setShowErrorMessage($value); },
                'showInputMessage' => function ($value) { $this->object->getDataValidation()->setShowInputMessage($value); },
                'type' => function ($value) { $this->object->getDataValidation()->setType($value); },
            ],
            'merge' => function ($value) {
                if (\is_int($value)) {
                    $value = Coordinate::stringFromColumnIndex($value).$this->sheetWrapper->getRow();
                }
                $this->sheetWrapper->getObject()->mergeCells(sprintf('%s:%s', $this->object->getCoordinate(), $value));
            },
            'style' => function ($value) { $this->sheetWrapper->getObject()->getStyle($this->object->getCoordinate())->applyFromArray($value); },
            'url' => function ($value) { $this->object->getHyperlink()->setUrl($value); },
        ];
    }
}
