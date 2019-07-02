Examples
========

Formulas
--------

.. code-block:: twig

    {% xlsdocument %}
        {% xlssheet %}
            {% xlsrow %}
                {% xlscell %}667.5{% endxlscell %}{# A1 #}
                {% xlscell %}2{% endxlscell %}{# B1 #}
            {% endxlsrow %}
            {% xlsrow %}
                {% xlscell %}=A1*B1+2{% endxlscell %}
            {% endxlsrow %}
            {% xlsrow %}
                {% xlscell %}=SUM(A1:B1){% endxlscell %}
            {% endxlsrow %}
        {% endxlssheet %}
    {% endxlsdocument %}

.. note:: It can be faster to calculate formulas in the template or the controller.

Templates
---------

.. code-block:: twig

    {% xlsdocument { 'template': '@Hello/templates/template.xlsx' } %}
        {% xlssheet %}
            {# ... #}
        {% endxlssheet %}
    {% endxlsdocument %}

.. note::

    If you want to save your templates outside of your `views` folder consider using your own Twig namespace_.

.. _namespace: http://symfony.com/doc/current/templating/namespaced_paths.html

More examples
-------------

For more advanced examples check the unit test scenarios here:

https://github.com/Erelke/TwigSpreadsheetBundle/tree/master/tests/Twig/Fixtures/views
