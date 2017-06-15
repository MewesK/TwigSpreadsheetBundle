<?php

namespace MewesK\TwigSpreadsheetBundle\Tests\Functional;

/**
 * Class ConfigFunctionalTest.
 */
class ConfigFunctionalTest extends BasicFunctionalTest
{
    protected static $ENVIRONMENT = 'config';
    protected static $TEMP_PATH = __DIR__.'/../../var/cache/functional/config';

    //
    // Tests
    //

    /**
     * @throws \Exception
     */
    public function testPreCalculateFormulas()
    {
        static::assertFalse(static::$kernel->getContainer()->getParameter('mewes_k_twig_spreadsheet.pre_calculate_formulas'), 'Unexpected parameter');

    }

    /**
     * @throws \Exception
     */
    public function testDiskCachingDirectory()
    {
        $document = $this->getDocument(static::$router->generate('test_default', ['templateName' => 'simple']));
        static::assertNotNull($document, 'Document does not exist');

        static::assertNotEmpty(static::$kernel->getContainer()->getParameter('mewes_k_twig_spreadsheet.disk_caching_directory'), 'Unexpected parameter');
        static::assertDirectoryExists(static::$kernel->getContainer()->getParameter('mewes_k_twig_spreadsheet.disk_caching_directory'), 'Cache directory does not exist');
    }
}
