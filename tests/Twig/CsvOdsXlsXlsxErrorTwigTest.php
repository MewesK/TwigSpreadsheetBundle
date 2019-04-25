<?php

namespace MyWheels\TwigSpreadsheetBundle\Tests\Twig;

/**
 * Class CsvOdsXlsXlsxErrorTwigTest.
 */
class CsvOdsXlsXlsxErrorTwigTest extends BaseTwigTest
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
    public function testDocumentError($format)
    {
        $this->expectException(\Twig_Error_Syntax::class);
        $this->expectExceptionMessage('Node "MyWheels\TwigSpreadsheetBundle\Twig\Node\DocumentNode" is not allowed inside of Node "MyWheels\TwigSpreadsheetBundle\Twig\Node\SheetNode"');

        $this->getDocument('documentError', $format);
    }

    /**
     * @param string $format
     *
     * @throws \Exception
     *
     * @dataProvider formatProvider
     */
    public function testDocumentErrorTextAfter($format)
    {
        $this->expectException(\Twig_Error_Syntax::class);
        $this->expectExceptionMessage('Node "Twig_Node_Text" is not allowed after Node "MyWheels\TwigSpreadsheetBundle\Twig\Node\DocumentNode"');

        $this->getDocument('documentErrorTextAfter', $format);
    }

    /**
     * @param string $format
     *
     * @throws \Exception
     *
     * @dataProvider formatProvider
     */
    public function testDocumentErrorTextBefore($format)
    {
        $this->expectException(\Twig_Error_Syntax::class);
        $this->expectExceptionMessage('Node "Twig_Node_Text" is not allowed before Node "MyWheels\TwigSpreadsheetBundle\Twig\Node\DocumentNode"');

        $this->getDocument('documentErrorTextBefore', $format);
    }

    /**
     * @param string $format
     *
     * @throws \Exception
     *
     * @dataProvider formatProvider
     */
    public function testStartCellIndexError($format)
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 1 passed to MyWheels\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper::startCell() must be of the type integer');

        $this->getDocument('cellIndexError', $format);
    }

    /**
     * @param string $format
     *
     * @throws \Exception
     *
     * @dataProvider formatProvider
     */
    public function testStartRowIndexError($format)
    {
        $this->expectException(\TypeError::class);
        $this->expectExceptionMessage('Argument 1 passed to MyWheels\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper::startRow() must be of the type integer');

        $this->getDocument('rowIndexError', $format);
    }

    /**
     * @param string $format
     *
     * @throws \Exception
     *
     * @dataProvider formatProvider
     */
    public function testSheetError($format)
    {
        $this->expectException(\Twig_Error_Syntax::class);
        $this->expectExceptionMessage('Node "MyWheels\TwigSpreadsheetBundle\Twig\Node\RowNode" is not allowed inside of Node "MyWheels\TwigSpreadsheetBundle\Twig\Node\DocumentNode"');

        $this->getDocument('sheetError', $format);
    }
}
