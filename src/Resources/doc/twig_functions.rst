Twig Functions
==============

xlsmergestyles
--------------

.. code-block:: twig

    xlsmergestyles([style1:array], [style2:array])

- Merges two style arrays recursively
- Returns a new array

Parameters
``````````

==========  ======  ========  ===========
Name        Type    Optional  Description
==========  ======  ========  ===========
style1      array             Standard PhpSpreadsheet style array
style2      array             Standard PhpSpreadsheet style array
==========  ======  ========  ===========

Example
```````

.. code-block:: twig

    {% set mergedStyle = xlsmergestyles({ font: { name: 'Verdana' } }, { font: { size: '18' } }) %}



xlsrowindex
-----------

.. code-block:: twig

    xlsrowindex()

- Returns the current row index

When you don't want to recalculate the location of merged cells and images
after inserting or deleting rows.

Example
```````

.. code-block:: twig

    {% xlsrow %}{% set row = xlsrowindex() %}{% xlscell %}Image there ==>{% endxlscell %}{% endxlsrow %}
    {% xlsdrawing 'directory_name/image.png' {
        coordinates: 'B' ~ row,
    } %}

	{% xlsrow %}
		{% set row = xlsrowindex() %}
		{% xlscell { merge: 'A'~(row+1) } %}A cell over two lines{% endxlscell %}
	{% endxlsrow %}

