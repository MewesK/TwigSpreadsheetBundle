# TwigSpreadsheetBundle

This Symfony bundle integrates PhpSpreadsheet into Symfony using Twig.

## Features

 * Easy to use Twig integration including ``macro`` and ``include`` support
 * Use existing spreadsheet files as templates. The easiest way to customize fonts, colors, etc.
 * ...

## Supported output formats

The supported output formats are directly based on the capabilities of PhpSpreadsheet.

 * Open Document Format/OASIS (.ods)
 * Office Open XML (.xlsx) Excel 2007 and above
 * BIFF 8 (.xls) Excel 97 and above
 * CSV
 * PDF (using mPDF, which need to be installed separately)

## Software requirements

The following software is required to use PhpSpreadsheet/TwigSpreadsheetBundle.

**Required by this bundle:**

 * PHP 7.1 or newer
 * Symfony 3.4 or newer

**Required by PhpSpreadsheet:**

 * PHP extension php_zip enabled
 * PHP extension php_xml enabled
 * PHP extension php_fileinfo enabled
 * PHP extension php_gd2 enabled (optional, but required for exact calculation of the column width)

## Documentation

The source of the documentation is stored in the Resources/doc/ folder in this bundle:
    
[Resources/doc/index.rst](https://github.com/Gerben321/TwigSpreadsheetBundle/blob/master/src/Resources/doc/index.rst)

You can find a prettier version on [readthedocs.org](httsp://readthedocs.org):

[https://twigspreadsheetbundle.readthedocs.org](https://twigspreadsheetbundle.readthedocs.org/)

## Installation

All the installation instructions are located in the documentation.

## License

This bundle is under the MIT license. See the complete license in the bundle:

[Resources/meta/LICENSE](https://github.com/Gerben321/TwigSpreadsheetBundle/blob/master/LICENSE)
