## 1.1

 * Fixed issue #13 'Incorrect cache directory for images' (technetium)
 * Fixed issue #15 'bug pre_calculate_formulas' (mikysan)
 * Fixed 'xlsmergestyles' which merged non-array values instead of overriding them
 * Added 'xlscellindex' and 'xlsrowindex' functions to get the current position
 * Added advanced CSV writer configurations like custom value separators etc
 * Improved internal index handling
 * Improved code performance

## 1.0

 * Removed xlsblock, xlsinclude and xlsmacro. The native Twig functions do work now like they are supposed to do!
 * Removed explicitValue cell property. Use dataType instead. However PhpSpread is handling leading zeros properly now so you probably don't have to use this property anyway.
 * Changed header/footer types. Just use 'even', 'odd', 'first' now.
 * ["Column indexes are now based on 1. So column A is the index 1. This is consistent with rows starting at 1 and Excel function COLUMN() that returns 1 for column A."](https://phpspreadsheet.readthedocs.io/en/develop/topics/migration-from-PHPExcel/#column-index-based-on-1)
