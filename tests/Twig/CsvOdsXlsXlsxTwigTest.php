<?php

namespace MewesK\TwigSpreadsheetBundle\Tests\Twig;

/**
 * Class CsvOdsXlsXlsxTwigTest.
 */
class CsvOdsXlsXlsxTwigTest extends BaseTwigTest
{
    /**
     * @return array
     */
    public function formatProvider(): array
    {
        return [['csv'], ['ods'], ['xls'], ['xlsx']];
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
    public function testDocumentSimple($format)
    {
        $document = $this->getDocument('documentSimple', $format);
        static::assertNotNull($document, 'Document does not exist');

        $sheet = $document->getActiveSheet();
        static::assertNotNull($sheet, 'Sheet does not exist');

        static::assertEquals('Foo', $sheet->getCell('A1')->getValue(), 'Unexpected value in A1');
        static::assertEquals('Bar', $sheet->getCell('B1')->getValue(), 'Unexpected value in B1');
        static::assertEquals('Hello', $sheet->getCell('A2')->getValue(), 'Unexpected value in A2');
        static::assertEquals('World', $sheet->getCell('B2')->getValue(), 'Unexpected value in B2');
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
        $document = $this->getDocument('documentTemplate.'.$format, $format);
        static::assertNotNull($document, 'Document does not exist');

        $sheet = $document->getSheet(0);
        static::assertNotNull($sheet, 'Sheet does not exist');

        static::assertEquals('Hello2', $sheet->getCell('A1')->getValue(), 'Unexpected value in A1');
        static::assertEquals('World', $sheet->getCell('B1')->getValue(), 'Unexpected value in B1');
        static::assertEquals('Foo', $sheet->getCell('A2')->getValue(), 'Unexpected value in A2');
        static::assertEquals('Bar2', $sheet->getCell('B2')->getValue(), 'Unexpected value in B2');
    }

    /**
     * @param string $format
     *
     * @throws \Exception
     *
     * @dataProvider formatProvider
     */
    public function testFunctionIndex($format)
    {
        $document = $this->getDocument('functionIndex', $format);
        static::assertNotNull($document, 'Document does not exist');

        $sheet = $document->getSheet(0);
        static::assertNotNull($sheet, 'Sheet does not exist');

        static::assertEquals('NULL', $sheet->getCell('A1')->getValue(), 'Unexpected value in A1');
        static::assertEquals(1, $sheet->getCell('B1')->getValue(), 'Unexpected value in B1');
        static::assertEquals('NULL', $sheet->getCell('A2')->getValue(), 'Unexpected value in A2');
        static::assertEquals(2, $sheet->getCell('B2')->getValue(), 'Unexpected value in B2');
    }
}
