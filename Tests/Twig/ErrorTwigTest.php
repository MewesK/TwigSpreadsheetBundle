<?php

namespace MewesK\TwigSpreadsheetBundle\Tests\Twig;

/**
 * Class ErrorTwigTest
 * @package MewesK\TwigSpreadsheetBundle\Tests\Twig
 */
class ErrorTwigTest extends AbstractTwigTest
{
    protected static $TEMP_PATH = '/../../tmp/error/';

    //
    // PhpUnit
    //

    /**
     * @return array
     */
    public function formatProvider()
    {
        return [['ods'], ['xls'], ['xlsx']];
    }

    //
    // Tests
    //

    /**
     * @param string $format
     * @throws \Exception
     *
     * @dataProvider formatProvider
     */
    public function testDocumentError($format)
    {
        $this->expectException(\Twig_Error_Syntax::class);
        $this->expectExceptionMessage('Node "MewesK\TwigSpreadsheetBundle\Twig\Node\XlsDocumentNode" is not allowed inside of Node "MewesK\TwigSpreadsheetBundle\Twig\Node\XlsSheetNode"');

        $this->getDocument('documentError', $format);
    }
}
