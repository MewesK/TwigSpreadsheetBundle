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

    {% set mergedStyle = xlsmergestyles({ font: { name: 'Verdana' } }, { font: { size: 18.0 } }) %}

xlscellindex
-----------

.. code-block:: twig

    xlscellindex()

- Returns the current cell index or null if no cell is initialized

Example
```````

.. code-block:: twig

    {% xlsrow %}
        {% set cellIndex = xlscellindex() %}
        {% xlscell %}
            {# cell index is null, because it was read before the first cell was initialized #}
            {{ cellIndex }}
            {# cell index is 1, because it was read after the first cell was initialized #}
            {{ xlscellindex() }}
        {% endxlscell %}
    {% endxlsrow %}

xlsrowindex
-----------

.. code-block:: twig

    xlsrowindex()

- Returns the current row index or null if no row is initialized

Example
```````

.. code-block:: twig

    {% xlssheet 'Test' %}
        {% set rowIndex = xlsrowindex() %}
        {% xlsrow %}
            {% xlscell %}
                {# row index is null, because it was read before the first row was initialized #}
                {{ rowIndex }}
                {# row index is 1, because it was read after the first row was initialized #}
                {{ xlsrowindex() }}
            {% endxlscell %}
        {% endxlsrow %}
    {% endxlssheet %}

