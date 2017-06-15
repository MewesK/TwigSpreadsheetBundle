<?php

namespace MewesK\TwigSpreadsheetBundle\Tests\Functional;

/**
 * Class CustomConfigTest.
 */
class CustomConfigTest extends AbstractControllerTest
{
    protected static $ENVIRONMENT = 'custom';
    protected static $TEMP_PATH = '/../../tmp/functional/config/';

    //
    // PhpUnit
    //

    /**
     * @return array
     */
    public function formatProvider()
    {
        return [['xlsx']];
    }

    //
    // Tests
    //

    /**
     * @param string $format
     *
     * @throws \Exception
     *
     * @dataProvider formatProvider
     */
    public function testCustomConfig($format)
    {
        $document = $this->getDocument(static::$router->generate('test_default', ['templateName' => 'simple', '_format' => $format]), $format);
        static::assertNotNull($document, 'Document does not exist');

        static::assertFalse(static::$kernel->getContainer()->getParameter('mewes_k_twig_spreadsheet.pre_calculate_formulas'), 'Unexpected parameter');
        static::assertStringEndsWith('tmp/cache/spreadsheet', static::$kernel->getContainer()->getParameter('mewes_k_twig_spreadsheet.disk_caching_directory'), 'Unexpected parameter');
        static::assertDirectoryExists(static::$kernel->getContainer()->getParameter('mewes_k_twig_spreadsheet.disk_caching_directory'), 'Cache directory does not exist');
    }
}
