<?php

namespace MewesK\TwigSpreadsheetBundle\Tests\Twig;

use MewesK\TwigSpreadsheetBundle\Twig\TwigSpreadsheetExtension;
use MewesK\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class AbstractTwigTest
 * @package MewesK\TwigSpreadsheetBundle\Tests\Twig
 */
abstract class AbstractTwigTest extends \PHPUnit_Framework_TestCase
{
    protected static $TEMP_PATH = '/../../tmp/';
    protected static $RESOURCE_PATH = '/../Resources/views/';
    protected static $TEMPLATE_PATH = '/../Resources/templates/';

    /**
     * @var Filesystem
     */
    protected static $fileSystem;
    /**
     * @var \Twig_Environment
     */
    protected static $environment;

    //
    // Helper
    //

    /**
     * @param string $templateName
     * @param string $format
     * @return Spreadsheet|string
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Loader
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     * @throws \InvalidArgumentException
     */
    protected function getDocument($templateName, $format)
    {
        // prepare global variables
        $request = new Request();
        $request->setRequestFormat($format);

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $appVariable = new AppVariable();
        $appVariable->setRequestStack($requestStack);

        // generate source from template
        $source = static::$environment->load($templateName . '.twig')->render(['app' => $appVariable]);

        // create paths
        $tempDirPath = __DIR__ . static::$TEMP_PATH;
        $tempFilePath = $tempDirPath . $templateName . '.' . $format;

        // save source
        static::$fileSystem->dumpFile($tempFilePath, $source);

        // load source
        switch ($format) {
            case 'ods':
                $readerType = 'Ods';
                break;
            case 'xls':
                $readerType = 'Xls';
                break;
            case 'xlsx':
                $readerType = 'Xlsx';
                break;
            case 'pdf':
            case 'csv':
                return $tempFilePath;
            default:
                throw new \InvalidArgumentException();
        }

        $reader = IOFactory::createReader($readerType);

        return $reader->load($tempFilePath);
    }

    //
    // PhpUnit
    //

    /**
     * @return array
     */
    abstract public function formatProvider();

    /**
     * {@inheritdoc}
     * @throws \Twig_Error_Loader
     */
    public static function setUpBeforeClass()
    {
        static::$fileSystem = new Filesystem();
        
        $twigFileSystem = new \Twig_Loader_Filesystem([__DIR__ . static::$RESOURCE_PATH]);
        $twigFileSystem->addPath( __DIR__ . static::$TEMPLATE_PATH, 'templates');
        
        static::$environment = new \Twig_Environment($twigFileSystem, ['strict_variables' => true]);
        static::$environment->addExtension(new TwigSpreadsheetExtension());
        static::$environment->setCache(__DIR__ . static::$TEMP_PATH);
    }

    /**
     * {@inheritdoc}
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public static function tearDownAfterClass()
    {
        if (in_array(getenv('DELETE_TEMP_FILES'), ['true', '1', 1, true], true)) {
            static::$fileSystem->remove(__DIR__ . static::$TEMP_PATH);
        }
    }
}
