<?php

namespace MewesK\TwigSpreadsheetBundle\Wrapper;

use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooterDrawing;

/**
 * Class DrawingWrapper.
 */
class DrawingWrapper extends BaseWrapper
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
     * @var HeaderFooterWrapper
     */
    protected $headerFooterWrapper;

    /**
     * @var Drawing | HeaderFooterDrawing
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
     * DrawingWrapper constructor.
     *
     * @param array               $context
     * @param \Twig_Environment   $environment
     * @param SheetWrapper        $sheetWrapper
     * @param HeaderFooterWrapper $headerFooterWrapper
     */
    public function __construct(array $context, \Twig_Environment $environment, SheetWrapper $sheetWrapper, HeaderFooterWrapper $headerFooterWrapper)
    {
        $this->context = $context;
        $this->environment = $environment;
        $this->sheetWrapper = $sheetWrapper;
        $this->headerFooterWrapper = $headerFooterWrapper;

        $this->object = null;
        $this->attributes = [];
        $this->mappings = [];

        $this->initializeMappings();
    }

    /**
     * @param string $path
     * @param array  $properties
     *
     * @throws \LogicException
     * @throws \InvalidArgumentException
     * @throws \RuntimeException
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     */
    public function start(string $path, array $properties = [])
    {
        if ($this->sheetWrapper->getObject() === null) {
            throw new \LogicException();
        }

        // create local copy of the asset
        $tempPath = $this->createTempCopy($path);

        // add to header/footer
        if ($this->headerFooterWrapper->getObject()) {
            $headerFooterAttributes = $this->headerFooterWrapper->getAttributes();
            $location = '';

            switch (strtolower($this->headerFooterWrapper->getAlignmentAttributes()['type'])) {
                case 'left':
                    $location .= 'L';
                    $headerFooterAttributes['value']['left'] .= '&G';
                    break;
                case 'center':
                    $location .= 'C';
                    $headerFooterAttributes['value']['center'] .= '&G';
                    break;
                case 'right':
                    $location .= 'R';
                    $headerFooterAttributes['value']['right'] .= '&G';
                    break;
                default:
                    throw new \InvalidArgumentException(sprintf('Unknown alignment type "%s"', $this->headerFooterWrapper->getAlignmentAttributes()['type']));
            }

            switch (strtolower($headerFooterAttributes['type'])) {
                case 'header':
                case 'oddheader':
                case 'evenheader':
                case 'firstheader':
                    $location .= 'H';
                    break;
                case 'footer':
                case 'oddfooter':
                case 'evenfooter':
                case 'firstfooter':
                    $location .= 'F';
                    break;
                default:
                    throw new \InvalidArgumentException(sprintf('Unknown type "%s"', $headerFooterAttributes['type']));
            }

            $this->object = new HeaderFooterDrawing();
            $this->object->setPath($tempPath);
            $this->headerFooterWrapper->getObject()->addImage($this->object, $location);
            $this->headerFooterWrapper->setAttributes($headerFooterAttributes);
        }

        // add to worksheet
        else {
            $this->object = new Drawing();
            $this->object->setWorksheet($this->sheetWrapper->getObject());
            $this->object->setPath($tempPath);
        }

        $this->setProperties($properties, $this->mappings);
    }

    public function end()
    {
        $this->object = null;
        $this->attributes = [];
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

    /**
     * @return Drawing
     */
    public function getObject(): Drawing
    {
        return $this->object;
    }

    /**
     * @param Drawing $object
     */
    public function setObject(Drawing $object)
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

    protected function initializeMappings()
    {
        $this->mappings['coordinates'] = function ($value) {
            $this->object->setCoordinates($value);
        };
        $this->mappings['description'] = function ($value) {
            $this->object->setDescription($value);
        };
        $this->mappings['height'] = function ($value) {
            $this->object->setHeight($value);
        };
        $this->mappings['name'] = function ($value) {
            $this->object->setName($value);
        };
        $this->mappings['offsetX'] = function ($value) {
            $this->object->setOffsetX($value);
        };
        $this->mappings['offsetY'] = function ($value) {
            $this->object->setOffsetY($value);
        };
        $this->mappings['resizeProportional'] = function ($value) {
            $this->object->setResizeProportional($value);
        };
        $this->mappings['rotation'] = function ($value) {
            $this->object->setRotation($value);
        };
        $this->mappings['shadow']['alignment'] = function ($value) {
            $this->object->getShadow()->setAlignment($value);
        };
        $this->mappings['shadow']['alpha'] = function ($value) {
            $this->object->getShadow()->setAlpha($value);
        };
        $this->mappings['shadow']['blurRadius'] = function ($value) {
            $this->object->getShadow()->setBlurRadius($value);
        };
        $this->mappings['shadow']['color'] = function ($value) {
            $this->object->getShadow()->getColor()->setRGB($value);
        };
        $this->mappings['shadow']['direction'] = function ($value) {
            $this->object->getShadow()->setDirection($value);
        };
        $this->mappings['shadow']['distance'] = function ($value) {
            $this->object->getShadow()->setDistance($value);
        };
        $this->mappings['shadow']['visible'] = function ($value) {
            $this->object->getShadow()->setVisible($value);
        };
        $this->mappings['width'] = function ($value) {
            $this->object->setWidth($value);
        };
    }

    /**
     * @param string $path
     *
     * @throws \RuntimeException
     * @throws \InvalidArgumentException
     *
     * @return string
     */
    private function createTempCopy(string $path): string
    {
        // create temp path
        $pathExtension = pathinfo($path, PATHINFO_EXTENSION);
        $tempPath = sys_get_temp_dir().DIRECTORY_SEPARATOR.'xlsdrawing'.'_'.md5($path).($pathExtension ? '.'.$pathExtension : '');

        // create local copy
        if (!file_exists($tempPath)) {
            $data = file_get_contents($path);
            if ($data === false) {
                throw new \InvalidArgumentException($path.' does not exist.');
            }
            $temp = fopen($tempPath, 'wb+');
            if ($temp === false) {
                throw new \RuntimeException('Cannot open '.$tempPath);
            }
            fwrite($temp, $data);
            if (fclose($temp) === false) {
                throw new \RuntimeException('Cannot close '.$tempPath);
            }
            unset($data, $temp);
        }

        return $tempPath;
    }
}
