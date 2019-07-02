<?php

namespace Erelke\TwigSpreadsheetBundle\Tests\Functional;

use Erelke\TwigSpreadsheetBundle\Twig\TwigSpreadsheetExtension;
use Exception;

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
     * @throws Exception
     */
    public function testPreCalculateFormulas()
    {
        /**
         * @var TwigSpreadsheetExtension $extension
         */
        $extension = static::$kernel->getContainer()->get('erelke_twig_spreadsheet.twig_spreadsheet_extension');

        static::assertFalse($extension->getAttributes()['pre_calculate_formulas'], 'Unexpected attribute');

        // TODO: check result
    }

    /**
     * @throws Exception
     */
    public function testXmlCacheDirectory()
    {
        // make request to fill the disk cache
        $response = $this->_getResponse('test_default', ['templateName' => 'simple']);
        static::assertNotNull($response, 'Response does not exist');

        /**
         * @var TwigSpreadsheetExtension $extension
         */
        $extension = static::$kernel->getContainer()->get('erelke_twig_spreadsheet.twig_spreadsheet_extension');

        static::assertDirectoryExists($extension->getAttributes()['cache']['xml'], 'Cache directory does not exist');
    }

    /**
     * @throws Exception
     */
    public function testCsvWriterAttributes()
    {
        /**
         * @var TwigSpreadsheetExtension $extension
         */
        $extension = static::$kernel->getContainer()->get('erelke_twig_spreadsheet.twig_spreadsheet_extension');

        static::assertEquals(';', $extension->getAttributes()['csv_writer']['delimiter'], 'Unexpected attribute');
        static::assertEquals('\'', $extension->getAttributes()['csv_writer']['enclosure'], 'Unexpected attribute');
        static::assertFalse($extension->getAttributes()['csv_writer']['excel_compatibility'], 'Unexpected attribute');
        static::assertFalse($extension->getAttributes()['csv_writer']['include_separator_line'], 'Unexpected attribute');
        static::assertEquals("\r\n", $extension->getAttributes()['csv_writer']['line_ending'], 'Unexpected attribute');
        static::assertEquals(0, $extension->getAttributes()['csv_writer']['sheet_index'], 'Unexpected attribute');
        static::assertTrue($extension->getAttributes()['csv_writer']['use_bom'], 'Unexpected attribute');

        // TODO: check result
    }
}
