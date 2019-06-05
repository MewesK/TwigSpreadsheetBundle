<?php

namespace MewesK\TwigSpreadsheetBundle\Tests\Functional;

use MewesK\TwigSpreadsheetBundle\Helper\Filesystem;
use MewesK\TwigSpreadsheetBundle\Tests\Functional\Fixtures\TestAppKernel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Exception;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bundle\FrameworkBundle\Client;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\Filesystem\Exception\IOException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class BaseFunctionalTest.
 */
abstract class BaseFunctionalTest extends WebTestCase
{
    const CACHE_PATH = './../../var/cache/twig';
    const RESULT_PATH = './../../var/result';

    /**
     * @var string
     */
    protected static $ENVIRONMENT;

    /**
     * @var Client
     */
    protected static $client;

    /**
     * {@inheritdoc}
     *
     * @throws IOException
     */
    public static function setUpBeforeClass()
    {
        // remove temp files
        Filesystem::remove(sprintf('%s/%s', static::CACHE_PATH, str_replace('\\', DIRECTORY_SEPARATOR, static::class)));
        Filesystem::remove(sprintf('%s/%s', static::RESULT_PATH, str_replace('\\', DIRECTORY_SEPARATOR, static::class)));
    }

    /**
     * {@inheritdoc}
     */
    protected static function getKernelClass()
    {
        return TestAppKernel::class;
    }

    /**
     * {@inheritdoc}
     */
    protected static function createKernel(array $options = [])
    {
        /**
         * @var TestAppKernel $kernel
         */
        $kernel = parent::createKernel($options);
        $kernel->setCacheDir(sprintf('%s/../../../var/cache/%s', $kernel->getRootDir(), str_replace('\\', DIRECTORY_SEPARATOR, static::class)));
        $kernel->setLogDir(sprintf('%s/../../../var/logs/%s', $kernel->getRootDir(), str_replace('\\', DIRECTORY_SEPARATOR, static::class)));

        return $kernel;
    }

    /**
     * @throws IOException
     */
    public function setUp()
    {
        // create client
        static::$client = static::createClient(['environment' => static::$ENVIRONMENT, 'debug' => false]);
    }

    /**
     * @param string $routeName
     * @param array $routeParameters
     * @param string $format
     *
     * @throws IOException
     * @throws Exception
     *
     * @return Spreadsheet
     */
    protected function getDocument(string $routeName, array $routeParameters = [], string $format = 'xlsx'): Spreadsheet
    {
        // create document content
        $content = $this->getResponse($routeName, $routeParameters)->getContent();

        // create path for temp file
        $format = strtolower($format);
        $resultPath = sprintf('%s/%s/%s/%s.%s', __DIR__, static::RESULT_PATH, str_replace('\\', DIRECTORY_SEPARATOR, static::class), static::$ENVIRONMENT, $format);

        // save content
        Filesystem::dumpFile($resultPath, $content);

        // load document
        return IOFactory::createReader(ucfirst($format))->load($resultPath);
    }

    /**
     * @param string $routeName
     * @param array  $routeParameters
     *
     * @return Response
     */
    protected function getResponse(string $routeName, array $routeParameters = []): Response
    {
        /**
         * @var Router $router
         */
        $router = static::$kernel->getContainer()->get('router');
        static::$client->request(Request::METHOD_GET, $router->generate($routeName, $routeParameters));

        return static::$client->getResponse();
    }
}
