<?php

namespace MewesK\TwigSpreadsheetBundle\Tests\Functional;

use MewesK\TwigSpreadsheetBundle\Twig\TwigSpreadsheetExtension;

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
    public function testPreCalculateFormulas()
    {
        /**
         * @var TwigSpreadsheetExtension $extension
         */
        $extension = static::$kernel->getContainer()->get('mewes_k_twig_spreadsheet.twig_spreadsheet_extension');

        static::assertFalse($extension->getAttributes()['pre_calculate_formulas'], 'Unexpected attribute');
    }

    /**
     * @throws \Exception
     */
    public function testXmlCacheDirectory()
    {
        // make request to fill the disk cache
        $response = $this->getResponse('test_default', ['templateName' => 'simple']);
        static::assertNotNull($response, 'Response does not exist');

        /**
         * @var TwigSpreadsheetExtension $extension
         */
        $extension = static::$kernel->getContainer()->get('mewes_k_twig_spreadsheet.twig_spreadsheet_extension');

        static::assertDirectoryExists($extension->getAttributes()['cache']['xml'], 'Cache directory does not exist');
    }
}
