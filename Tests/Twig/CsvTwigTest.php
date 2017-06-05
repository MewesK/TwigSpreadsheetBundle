<?php

namespace MewesK\TwigSpreadsheetBundle\Tests\Twig;

/**
 * Class CsvTwigTest.
 *
 * @coversNothing
 */
class CsvTwigTest extends BaseTwigTest
{
    protected static $TEMP_PATH = '/../../tmp/csv/';

    //
    // PhpUnit
    //

    /**
     * @return array
     */
    public function formatProvider()
    {
        return [['csv']];
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
    public function testBasic($format)
    {
        $path = $this->getDocument('documentSimple', $format);

        static::assertFileExists($path, 'File does not exist');
        static::assertGreaterThan(0, filesize($path), 'File is empty');
        static::assertStringEqualsFile($path, '"Foo","Bar"'.PHP_EOL.'"Hello","World"'.PHP_EOL, 'Unexpected content');
    }

    /**
     * @param string $format
     *
     * @throws \Exception
     *
     * @dataProvider formatProvider
     */
    public function testDocumentTemplate($format)
    {
        $path = $this->getDocument('documentTemplate.csv', $format);

        static::assertFileExists($path, 'File does not exist');
        static::assertGreaterThan(0, filesize($path), 'File is empty');
        static::assertStringEqualsFile($path, '"Hello2","World"'.PHP_EOL.'"Foo","Bar2"'.PHP_EOL, 'Unexpected content');
    }
}
