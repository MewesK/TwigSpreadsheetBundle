<?php

namespace MewesK\TwigSpreadsheetBundle\Tests\Twig;

use MewesK\TwigSpreadsheetBundle\Helper\Filesystem;
use MewesK\TwigSpreadsheetBundle\Twig\TwigSpreadsheetExtension;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use Symfony\Bridge\Twig\AppVariable;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Class BaseTwigTest.
 */
abstract class BaseTwigTest extends \PHPUnit_Framework_TestCase
{
    const CACHE_PATH = './../../var/cache';
    const RESULT_PATH = './../../var/result';
    const RESOURCE_PATH = './Fixtures/views';
    const TEMPLATE_PATH = './Fixtures/templates';

    /**
     * @var \Twig_Environment
     */
    protected static $environment;

    /**
     * {@inheritdoc}
     *
     * @throws \Twig_Error_Loader
     */
    public static function setUpBeforeClass()
    {
        $cachePath = sprintf('%s/%s/%s/twig', __DIR__, static::CACHE_PATH, str_replace('\\', DIRECTORY_SEPARATOR, static::class));

        // remove temp files
        Filesystem::remove($cachePath);
        Filesystem::remove(sprintf('%s/%s/%s', __DIR__, static::RESULT_PATH, str_replace('\\', DIRECTORY_SEPARATOR, static::class)));

        // set up Twig environment
        $twigFileSystem = new \Twig_Loader_Filesystem([sprintf('%s/%s', __DIR__, static::RESOURCE_PATH)]);
        $twigFileSystem->addPath(sprintf('%s/%s', __DIR__, static::TEMPLATE_PATH), 'templates');

        static::$environment = new \Twig_Environment($twigFileSystem, ['debug' => true, 'strict_variables' => true]);
        static::$environment->addExtension(new TwigSpreadsheetExtension());
        static::$environment->setCache($cachePath);
    }

    /**
     * @param string $templateName
     * @param string $format
     *
     * @throws \Twig_Error_Syntax
     * @throws \Twig_Error_Loader
     * @throws \Symfony\Component\Filesystem\Exception\IOException
     *
     * @return Spreadsheet|string
     */
    protected function getDocument($templateName, $format)
    {
        $format = strtolower($format);

        // prepare global variables
        $request = new Request();
        $request->setRequestFormat($format);

        $requestStack = new RequestStack();
        $requestStack->push($request);

        $appVariable = new AppVariable();
        $appVariable->setRequestStack($requestStack);

        // generate source from template
        $source = static::$environment->load($templateName.'.twig')->render(['app' => $appVariable]);

        // create path
        $resultPath = sprintf('%s/%s/%s/%s.%s', __DIR__, static::RESULT_PATH, str_replace('\\', DIRECTORY_SEPARATOR, static::class), $templateName, $format);

        // save source
        Filesystem::dumpFile($resultPath, $source);

        // load source or return path for PDFs
        return $format === 'pdf' ? $resultPath : IOFactory::createReader(ucfirst($format))->load($resultPath);
    }
}
