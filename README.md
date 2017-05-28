# TwigSpreadsheetBundle

[![Teavis CI: Build Status](https://travis-ci.org/MewesK/TwigSpreadsheetBundle.png?branch=master)](https://travis-ci.org/MewesK/TwigSpreadsheetBundle)
[![Scrutinizer: Code Quality](https://scrutinizer-ci.com/g/MewesK/TwigSpreadsheetBundle/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/MewesK/TwigSpreadsheetBundle/?branch=master)
[![SensioLabsInsight: Code Quality](https://insight.sensiolabs.com/projects/283cfe57-6ee4-4102-8fff-da3f6e668e8f/mini.png)](https://insight.sensiolabs.com/projects/283cfe57-6ee4-4102-8fff-da3f6e668e8f)

This Symfony bundle integrates PhpSpreadsheet into Symfony using Twig.

## Supported output formats

The supported output formats are directly based on the capabilities of PhpSpreadsheet.

 * Open Document Format/OASIS (.ods)
 * Office Open XML (.xlsx) Excel 2007 and above
 * BIFF 8 (.xls) Excel 97 and above
 * CSV
 * PDF (using tcPDF, DomPDF or mPDF, which need to be installed separately)

## Software requirements

The following software is required to use PhpSpreadsheet/TwigSpreadsheetBundle.

**Required by this bundle:**

 * PHP 5.5.9 or newer
 * Symfony 3.0 or newer

**Required by PhpSpreadsheet:**

 * PHP extension php_zip enabled
 * PHP extension php_xml enabled
 * PHP extension php_gd2 enabled (optional, but required for exact column width autocalculation)

## Documentation

The source of the documentation is stored in the Resources/doc/ folder in this bundle:
    
[Resources/doc/index.rst](https://github.com/MewesK/TwigSpreadsheetBundle/blob/master/Resources/doc/index.rst)

You can find a prettier version on [readthedocs.org](httsp://readthedocs.org):

[https://twigspreadsheetbundle.readthedocs.org](https://twigspreadsheetbundle.readthedocs.org/)

## Installation

All the installation instructions are located in the documentation.

## License

This bundle is under the MIT license. See the complete license in the bundle:

[Resources/meta/LICENSE](https://github.com/MewesK/TwigSpreadsheetBundle/blob/master/LICENSE)
