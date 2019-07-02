<?php

namespace Erelke\TwigSpreadsheetBundle\Tests\Twig;

use Exception;
use Twig\Error\SyntaxError as Twig_Error_Syntax;
use TypeError;

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
     * @throws Exception
     *
     * @dataProvider formatProvider
     */
    public function testDocumentError($format)
    {
        $this->expectException(Twig_Error_Syntax::class);
        $this->expectExceptionMessage('Node "Erelke\TwigSpreadsheetBundle\Twig\Node\DocumentNode" is not allowed inside of Node "Erelke\TwigSpreadsheetBundle\Twig\Node\SheetNode"');

        $this->getDocument('documentError', $format);
    }

    /**
     * @param string $format
     *
     * @throws Exception
     *
     * @dataProvider formatProvider
     */
    public function testDocumentErrorTextAfter($format)
    {
        $this->expectException(Twig_Error_Syntax::class);
        $this->expectExceptionMessage('Node "Twig\Node\TextNode" is not allowed after Node "Erelke\TwigSpreadsheetBundle\Twig\Node\DocumentNode"');

        $this->getDocument('documentErrorTextAfter', $format);
    }

    /**
     * @param string $format
     *
     * @throws Exception
     *
     * @dataProvider formatProvider
     */
    public function testDocumentErrorTextBefore($format)
    {
        $this->expectException(Twig_Error_Syntax::class);
        $this->expectExceptionMessage('Node "Twig\Node\TextNode" is not allowed before Node "Erelke\TwigSpreadsheetBundle\Twig\Node\DocumentNode"');

        $this->getDocument('documentErrorTextBefore', $format);
    }

    /**
     * @param string $format
     *
     * @throws Exception
     *
     * @dataProvider formatProvider
     */
    public function testStartCellIndexError($format)
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Argument 1 passed to Erelke\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper::startCell() must be of the type integer');

        $this->getDocument('cellIndexError', $format);
    }

    /**
     * @param string $format
     *
     * @throws Exception
     *
     * @dataProvider formatProvider
     */
    public function testStartRowIndexError($format)
    {
        $this->expectException(TypeError::class);
        $this->expectExceptionMessage('Argument 1 passed to Erelke\TwigSpreadsheetBundle\Wrapper\PhpSpreadsheetWrapper::startRow() must be of the type integer');

        $this->getDocument('rowIndexError', $format);
    }

    /**
     * @param string $format
     *
     * @throws Exception
     *
     * @dataProvider formatProvider
     */
    public function testSheetError($format)
    {
        $this->expectException(Twig_Error_Syntax::class);
        $this->expectExceptionMessage('Node "Erelke\TwigSpreadsheetBundle\Twig\Node\RowNode" is not allowed inside of Node "Erelke\TwigSpreadsheetBundle\Twig\Node\DocumentNode"');

        $this->getDocument('sheetError', $format);
    }
}
