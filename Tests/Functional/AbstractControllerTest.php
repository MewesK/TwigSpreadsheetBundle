<?php

namespace MewesK\TwigSpreadsheetBundle\Tests\Functional;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class AbstractControllerTest.
 */
abstract class AbstractControllerTest extends WebTestCase
{
    protected static $ENVIRONMENT;
    protected static $TEMP_PATH;

    /**
     * @var Filesystem
     */
    protected static $fileSystem;
    /**
     * @var Client
     */
    protected static $client;
    /**
     * @var Router
     */
    protected static $router;

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    public static function setUpBeforeClass()
    {
        static::$fileSystem = new Filesystem();
        static::$fileSystem->remove(__DIR__.static::$TEMP_PATH);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public static function tearDownAfterClass()
    {
        if (in_array(getenv('DELETE_TEMP_FILES'), ['true', '1', 1, true], true)) {
            static::$fileSystem->remove(__DIR__.static::$TEMP_PATH);
        }
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Exception
     */
    protected function setUp()
    {
        static::$client = static::createClient(['environment' => static::$ENVIRONMENT]);
        static::$router = static::$kernel->getContainer()->get('router');
    }

    //
    // PhpUnit
    //

    /**
     * @return array
     */
    abstract public function formatProvider();

    //
    // Helper
    //

    /**
     * @param string $uri
     * @param string $format
     *
     * @throws \InvalidArgumentException
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     *
     * @return Spreadsheet
     */
    protected function getDocument(string $uri, string $format = 'xlsx'): Spreadsheet
    {
        // generate source
        static::$client->request('GET', $uri);
        $source = static::$client->getResponse()->getContent();

        // create paths
        $tempDirPath = __DIR__.static::$TEMP_PATH;
        $tempFilePath = $tempDirPath.'simple'.'.'.$format;

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
            default:
                throw new \InvalidArgumentException();
        }

        return IOFactory::createReader($readerType)->load($tempFilePath);
    }
}
