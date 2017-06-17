<?php

namespace MewesK\TwigSpreadsheetBundle\Tests\Functional;

/**
 * Class ConfigFunctionalTest.
 */
class ConfigFunctionalTest extends OdsXlsXlsxFunctionalTest
{
    /**
     * @var string
     */
    protected static $ENVIRONMENT = 'config';

    //
    // Tests
    //

    /**
     * @throws \Exception
     */
    public function testDiskCachingDirectory()
    {
        // make request to fill the disk cache
        $response = $this->getResponse('test_default', ['templateName' => 'simple']);
        static::assertNotNull($response, 'Response does not exist');

        static::assertNotEmpty(static::$kernel->getContainer()->getParameter('mewes_k_twig_spreadsheet.disk_caching_directory'), 'Unexpected parameter');
        static::assertDirectoryExists(static::$kernel->getContainer()->getParameter('mewes_k_twig_spreadsheet.disk_caching_directory'), 'Cache directory does not exist');
    }

    /**
     * @throws \Exception
     */
    public function testPreCalculateFormulas()
    {
        static::assertFalse(static::$kernel->getContainer()->getParameter('mewes_k_twig_spreadsheet.pre_calculate_formulas'), 'Unexpected parameter');
    }
}
