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
     * DrawingWrapper constructor.
     *
     * @param array               $context
     * @param \Twig_Environment   $environment
     * @param SheetWrapper        $sheetWrapper
     * @param HeaderFooterWrapper $headerFooterWrapper
     */
    public function __construct(array $context, \Twig_Environment $environment, SheetWrapper $sheetWrapper, HeaderFooterWrapper $headerFooterWrapper)
    {
        parent::__construct($context, $environment);

        $this->sheetWrapper = $sheetWrapper;
        $this->headerFooterWrapper = $headerFooterWrapper;

        $this->object = null;
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
            $headerFooterParameters = $this->headerFooterWrapper->getParameters();
            $location = '';

            switch (strtolower($this->headerFooterWrapper->getAlignmentParameters()['type'])) {
                case 'left':
                    $location .= 'L';
                    $headerFooterParameters['value']['left'] .= '&G';
                    break;
                case 'center':
                    $location .= 'C';
                    $headerFooterParameters['value']['center'] .= '&G';
                    break;
                case 'right':
                    $location .= 'R';
                    $headerFooterParameters['value']['right'] .= '&G';
                    break;
                default:
                    throw new \InvalidArgumentException(sprintf('Unknown alignment type "%s"', $this->headerFooterWrapper->getAlignmentParameters()['type']));
            }

            switch (strtolower($headerFooterParameters['type'])) {
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
                    throw new \InvalidArgumentException(sprintf('Unknown type "%s"', $headerFooterParameters['type']));
            }

            $this->object = new HeaderFooterDrawing();
            $this->object->setPath($tempPath);
            $this->headerFooterWrapper->getObject()->addImage($this->object, $location);
            $this->headerFooterWrapper->setParameters($headerFooterParameters);
        }

        // add to worksheet
        else {
            $this->object = new Drawing();
            $this->object->setWorksheet($this->sheetWrapper->getObject());
            $this->object->setPath($tempPath);
        }

        $this->setProperties($properties);
    }

    public function end()
    {
        $this->object = null;
        $this->parameters = [];
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
     * {@inheritdoc}
     */
    protected function configureMappings(): array
    {
        return [
            'coordinates' => function ($value) { $this->object->setCoordinates($value); },
            'description' => function ($value) { $this->object->setDescription($value); },
            'height' => function ($value) { $this->object->setHeight($value); },
            'name' => function ($value) { $this->object->setName($value); },
            'offsetX' => function ($value) { $this->object->setOffsetX($value); },
            'offsetY' => function ($value) { $this->object->setOffsetY($value); },
            'resizeProportional' => function ($value) { $this->object->setResizeProportional($value); },
            'rotation' => function ($value) { $this->object->setRotation($value); },
            'shadow' => [
                'alignment' => function ($value) { $this->object->getShadow()->setAlignment($value); },
                'alpha' => function ($value) { $this->object->getShadow()->setAlpha($value); },
                'blurRadius' => function ($value) { $this->object->getShadow()->setBlurRadius($value); },
                'color' => function ($value) { $this->object->getShadow()->getColor()->setRGB($value); },
                'direction' => function ($value) { $this->object->getShadow()->setDirection($value); },
                'distance' => function ($value) { $this->object->getShadow()->setDistance($value); },
                'visible' => function ($value) { $this->object->getShadow()->setVisible($value); },
            ],
            'width' => function ($value) { $this->object->setWidth($value); },
        ];
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
