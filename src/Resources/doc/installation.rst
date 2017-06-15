Installation
============

Step 1: Download the Bundle
---------------------------

Open a command console, enter your project directory and execute the
following command to download the latest stable version of this bundle:

.. code-block:: terminal

    $ composer require mewesk/twig-spreadsheet-bundle

This command requires you to have Composer installed globally, as explained
in the `installation chapter`_ of the Composer documentation.

Step 2: Enable the Bundle
-------------------------

.. code-block:: php

    <?php
    // app/AppKernel.php

    // ...
    class AppKernel extends Kernel
    {
        public function registerBundles()
        {
            $bundles = array(
                // ...

                new MewesK\TwigSpreadsheetBundle\MewesKTwigSpreadsheetBundle(),
            );

            // ...
        }

        // ...
    }

Step 3: Configure the Bundle (optional)
---------------------------------------

Add the following configuration to your config.yml if you don't want to pre-calculate formulas.
Disabling this option can improve the performance but the resulting documents won't show the result of any formulas when opened in a external spreadsheet software.

.. code-block:: yaml

    mewes_k_twig_spreadsheet:
        pre_calculate_formulas: false

Add the following configuration to your config.yml if you want to enable disk caching.
Using disk caching can improve memory consumption by writing data to disk temporarily. Works only for .XLSX and .ODS documents.

.. code-block:: yaml

    mewes_k_twig_spreadsheet:
        disk_caching_directory: "%kernel.cache.dir%/phpspreadsheet"

.. _`installation chapter`: https://getcomposer.org/doc/00-intro.md
