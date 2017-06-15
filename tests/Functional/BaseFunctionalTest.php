<?php

namespace MewesK\TwigSpreadsheetBundle\Tests\Functional;

use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\Client;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class BaseFunctionalTest.
 */
abstract class BaseFunctionalTest extends WebTestCase
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
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public static function setUpBeforeClass()
    {
        static::$fileSystem = new Filesystem();
        static::$fileSystem->remove(static::$TEMP_PATH);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     */
    public static function tearDownAfterClass()
    {
        if (!getenv('TSB_KEEP_CACHE')) {
            static::$fileSystem->remove(static::$TEMP_PATH);
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
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     *
     * @return Spreadsheet
     */
    protected function getDocument(string $uri, string $format = 'xlsx'): Spreadsheet
    {
        $format = strtolower($format);

        // generate source
        static::$client->request(Request::METHOD_GET, $uri);
        $source = static::$client->getResponse()->getContent();

        // create path for temp file
        $path = sprintf('%s/simple.%s', static::$TEMP_PATH, $format);

        // save source
        static::$fileSystem->dumpFile($path, $source);

        // load source
        return IOFactory::createReader(ucfirst($format))->load($path);
    }
}
