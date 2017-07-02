Configuration
=============

.. code-block:: yaml

    # app/config/config.yml
    mewes_k_twig_spreadsheet:

Add the following configuration to your config.yml if you don't want to pre-calculate formulas.
Disabling this option can improve the performance but the resulting documents won't show the result of any formulas when opened in a external spreadsheet software.

.. code-block:: yaml

    mewes_k_twig_spreadsheet:
        pre_calculate_formulas: false

Add the following configuration to your config.yml if you want to enable disk caching.
Using disk caching can improve memory consumption by writing data to disk temporarily. Works only for .XLSX and .ODS documents.

.. code-block:: yaml

    mewes_k_twig_spreadsheet:
        cache:
            bitmap: "%kernel.cache.dir%/phpspreadsheet"

.. _`installation chapter`: https://getcomposer.org/doc/00-intro.md

