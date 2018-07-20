Twig Tags
=========

xlsdocument
-----------

.. code-block:: twig

    {% xlsdocument [properties:array] %}
        ...
    {% endxlsdocument %}

- Must contain one or more 'xlssheet' tags

Parameters
``````````

==========  ======  ========  ===========
Name        Type    Optional  Description
==========  ======  ========  ===========
properties  array   X
==========  ======  ========  ===========

Properties
``````````

=======================  ========  ===  ===  ===========
Name                     Type      XLS  ODS  Description
=======================  ========  ===  ===  ===========
category                 string    X
company                  string
created                  datetime  X    X    Can be null, timestamp or a strtotime compatible string
creator                  string    X    X
defaultStyle             array               Standard PhpSpreadsheet style array
description              string    X    X
format                   string    X         Possible formats are 'csv', 'html', 'pdf', 'xls, 'xlsx'
keywords                 string    X
lastModifiedBy           string    X
manager                  string
modified                 datetime  X    X    Can be null, timestamp or a strtotime compatible string
security                 array               Cannot be tested - not supported by the reader
\+ lockRevision          boolean             Cannot be tested - not supported by the reader
\+ lockStructure         boolean             Cannot be tested - not supported by the reader
\+ lockWindows           boolean             Cannot be tested - not supported by the reader
\+ revisionsPassword     string              Cannot be tested - not supported by the reader
\+ workbookPassword      string              Cannot be tested - not supported by the reader
subject                  string    X    X
template                 string    X    X    The path can be an absolute system path or a Twig namespace prefixed path like '@AppBundle/...' or a custom one like '@Templates/...'. Works for CSV too.
title                    string    X    X
=======================  ========  ===  ===  ===========

Example
```````

.. code-block:: twig

    {% xlsdocument {
        category: 'Test category',
        company: 'Test company',
        created: '2000/01/01',
        creator: 'Tester',
        defaultStyle: {
            font: {
                name: 'Verdana',
                size: 18.0
            }
        },
        description: 'Test document',
        format: 'xls',
        keywords: 'Test',
        lastModifiedBy: 'Tester',
        manager: 'Tester',
        modified: '2000/01/01',
        security: {
            lockRevision: true,
            lockStructure: true,
            lockWindows: true,
            revisionsPassword: 'test',
            workbookPassword: 'test'
        },
        subject: 'Test',
        title: 'Test'
    } %}
        {# ... #}
    {% endxlsdocument %}

xlssheet
--------

.. code-block:: twig

    {% xlssheet [title:string] [properties:array] %}
    ...
    {% endxlssheet %}

- May contain one or more 'xlsheader', 'xlsfooter', 'xlsrow' and 'xlsdrawing' tags

Parameters
``````````

==========  ======  ========  ===========
Name        Type    Optional  Description
==========  ======  ========  ===========
title       string  X         If no title is given the first existing sheet will be used. If no sheet exists a new one will be created.
properties  array   X
==========  ======  ========  ===========

Properties
``````````

=======================  ========  ===  ===  ===========
Name                     Type      XLS  ODS  Description
=======================  ========  ===  ===  ===========
autoFilter               string              The range like 'A1:E20'
columnDimension          array               Contains one or more arrays. Possible keys are 'default' or a valid column name like 'A'
 \+ autoSize             boolean
 \+ collapsed            boolean             Does not work in PhpSpreadsheet?
 \+ columnIndex          string              Does not work in PhpSpreadsheet?
 \+ outlineLevel         int
 \+ visible              boolean             Does not work in PhpSpreadsheet?
 \+ width                double
 \+ xfIndex              int
pageMargins              array
 \+ top                  double
 \+ bottom               double
 \+ left                 double
 \+ right                double
 \+ header               double
 \+ footer               double
pageSetup                array
 \+ fitToHeight          int
 \+ fitToPage            boolean
 \+ fitToWidth           int
 \+ horizontalCentered   boolean
 \+ orientation          string              Possible orientations are 'default', 'landscape', 'portrait'
 \+ paperSize            int                 Possible values are defined in PhpOffice\PhpSpreadsheet\Worksheet\PageSetup
 \+ printArea            string              A range like 'A1:E20'
 \+ scale                int
 \+ verticalCentered     boolean
protection               array
 \+ autoFilter           boolean
 \+ deleteColumns        boolean
 \+ deleteRows           boolean
 \+ formatCells          boolean
 \+ formatColumns        boolean
 \+ formatRows           boolean
 \+ insertColumns        boolean
 \+ insertHyperlinks     boolean
 \+ insertRows           boolean
 \+ objects              boolean
 \+ password             string
 \+ pivotTables          boolean
 \+ scenarios            boolean
 \+ selectLockedCells    boolean
 \+ selectUnlockedCells  boolean
 \+ sheet                boolean
 \+ sort                 boolean
printGridlines           boolean
rightToLeft              boolean
rowDimension             array               Contains one or more arrays. Possible keys are 'default' or a row index >=1
 \+ collapsed            boolean             Does not work in PhpSpreadsheet?
 \+ outlineLevel         int
 \+ rowHeight            double
 \+ rowIndex             int                 Does not work in PhpSpreadsheet?
 \+ visible              boolean             Does not work in PhpSpreadsheet?
 \+ xfIndex              int
 \+ zeroHeight           boolean             Does not work in PhpSpreadsheet?
sheetState               string
showGridlines            boolean             Cannot be tested - not supported by the reader
tabColor                 string
zoomScale                int
=======================  ========  ===  ===  ===========

Example
```````

.. code-block:: twig

    {% xlssheet 'Worksheet' {
        columnDimension: {
            'default': {
                autoSize: false,
                collapsed: false,
                outlineLevel: 0,
                visible: true,
                width: -1,
                xfIndex: 0
            },
            'D': {
                columnIndex: 2,
                visible: false
            }
        },
        pageMargins: {
            top: 1,
            bottom: 1,
            left: 0.75,
            right: 0.75,
            header: 0.5,
            footer: 0.5
        },
        pageSetup: {
            fitToHeight: 1,
            fitToPage: false,
            fitToWidth: 1,
            horizontalCentered: false,
            orientation: 'landscape',
            paperSize: 9,
            printArea: 'A1:B1',
            scale: 100,
            verticalCentered: false
        },
        protection: {
            autoFilter: true,
            deleteColumns: true,
            deleteRows: true,
            formatCells: true,
            formatColumns: true,
            formatRows: true,
            insertColumns: true,
            insertHyperlinks: true,
            insertRows: true,
            objects: true,
            pivotTables: true,
            scenarios: true,
            selectLockedCells: true,
            selectUnlockedCells: true,
            sheet: true,
            sort: true
        },
        printGridlines: true,
        rightToLeft: false,
        rowDimension: {
            'default': {
                collapsed: false,
                outlineLevel: 0,
                rowHeight: -1,
                rowIndex: '1',
                visible: true,
                xfIndex: 0,
                zeroHeight:false
            },
            '2': {
                visible: false
            }
        },
        sheetState: 'visible',
        showGridlines: true,
        tabColor: 'c0c0c0',
        zoomScale: 75
    }%}
        {# ... #}
    {% endxlssheet %}

xlsheader, xlsfooter
--------------------

.. code-block:: twig

    {% xlsheader [type:string] [properties:array] %}
        ...
    {% endxlsheader %}

    {% xlsfooter [type:string] [properties:array] %}
        ...
    {% endxlsfooter %}

- May contain one 'xlsleft', 'xlscenter' and 'xlsright' tag each
- Not supported by the OpenDocument writer

Parameters
``````````

==========  ======  ========  ===========
Name        Type    Optional  Description
==========  ======  ========  ===========
type        string  X         Possible types are null (default), 'odd' (xlsx), 'even' (xlsx), 'first' (xlsx)
properties  array   X
==========  ======  ========  ===========

Properties
``````````

=======================  ========  ===  ===  ===========
Name                     Type      XLS  ODS  Description
=======================  ========  ===  ===  ===========
scaleWithDocument        boolean
alignWithMargins         boolean
=======================  ========  ===  ===  ===========

Example
```````

.. code-block:: twig

    {% xlsheader 'even' %}
        {# ... #}
    {% endxlsheader %}

    {% xlsheader 'odd' %}
        {# ... #}
    {% endxlsheader %}

    {% xlsfooter %}
        {# ... #}
    {% endxlsfooter %}

xlsleft, xlscenter, xlsright
----------------------------

.. code-block:: twig

    {% xlsleft %}
        ...
    {% endxlsleft %}

    {% xlscenter %}
        ...
    {% endxlscenter %}

    {% xlsright %}
        ...
    {% endxlsright %}

- May contain one 'xlsdrawing' tag (not supported by the XLS and ODS writer)
- Not supported by the ODS writer

- These tags replace the &L, &C and &R format codes. All other codes can be found in PhpOffice\PhpSpreadsheet\Worksheet\HeaderFooter

Example
```````

.. code-block:: twig

    {% xlsheader %}
        {% xlsleft %}
            Left part of the header
        {% endxlsleft %}
        {% xlscenter %}
            Center part of the header
        {% endxlscenter %}
        {% xlsright %}
            Right part of the header
        {% endxlsright %}
    {% endxlsheader %}

xlsrow
------

.. code-block:: twig

    {% xlsrow [index:int] %}
        ...
    {% endxlsrow %}

- May contain one or more 'xlscell' tags

- If 'index' is not defined it will default to 1 for the first usage per sheet
- For each further usage it will increase the index by 1 automatically (1, 2, 3, ...)

Parameters
``````````

==========  ======  ========  ===========
Name        Type    Optional  Description
==========  ======  ========  ===========
index       int     X         A row index >=1
==========  ======  ========  ===========

Example
```````

.. code-block:: twig

    {% xlsrow 1 %}
        {# ... #}
    {% endxlsrow %}

xlscell
-------

.. code-block:: twig

    {% xlscell [index:string] [properties:array] %}
        ...
    {% endxlscell %}

- If 'index' is not defined it will default to 1 for the first usage per row
- For each further usage it will increase the index by 1 automatically (0, 1, 2, ...)
- Formulas are supported (e.g. ``=SUM(A1:F1)`` or ``=A1+B1``)

Parameters
``````````

==========  ======  ========  ===========
Name        Type    Optional  Description
==========  ======  ========  ===========
index       int     X         A column index >=1
properties  array   X
==========  ======  ========  ===========

Properties
``````````

=======================  ==========  ===  ===  ===========
Name                     Type        XLS  ODS  Description
=======================  ==========  ===  ===  ===========
break                    int         X         Possible values are defined in PhpOffice\PhpSpreadsheet\Spreadsheet
dataType                 string      X    X    If set cell is rendered as an explicit value (prevents PHP type casting). Possible values are defined in PhpOffice\PhpSpreadsheet\Cell\DataType
dataValidation           array
 \+ allowBlank           boolean
 \+ error                string
 \+ errorStyle           string                Does not work in PhpSpreadsheet? Possible values are defined in PhpOffice\PhpSpreadsheet\Cell\DataValidation
 \+ errorTitle           string
 \+ formula1             string
 \+ formula2             string
 \+ operator             string                Possible values are defined in PhpOffice\PhpSpreadsheet\Cell\DataValidation
 \+ prompt               string
 \+ promptTitle          string
 \+ showDropDown         boolean
 \+ showErrorMessage     boolean
 \+ showInputMessage     boolean
 \+ type                 string                Does not work in PhpSpreadsheet? Possible values are defined in PhpOffice\PhpSpreadsheet\Cell\DataValidation
merge                    int|string  X         Merge a cell range. Allows cell index >=1 or cell coordinates like 'A3'
style                    array       X         Standard PhpSpreadsheet style array
url                      string      X
=======================  ==========  ===  ===  ===========

Example
```````

.. code-block:: twig

    {% xlscell 1 {
        break: 1,
        dataValidation: {
            allowBlank: false,
            error: '',
            errorStyle: 'stop',
            errorTitle: '',
            formula1: '',
            formula2: '',
            operator: '',
            prompt: ''
            promptTitle: '',
            showDropDown: false,
            showErrorMessage: false,
            showInputMessage: false,
            type: 'none',
        },
        merge: 3,
        style: {
            borders: {
                bottom: {
                    style: 'thin',
                    color: {
                        rgb: '000000'
                    }
                }
            }
        },
        url: 'http://www.example.com'
    } %}
        {# ... #}
    {% endxlscell %}

xlsdrawing
----------

.. code-block:: twig

    {% xlsdrawing [path:string] [properties:array] %}

- If the xlsdrawing is used in a header/footer it automatically adds the &G code to be displayed
- Not supported by the OpenDocument writer

Parameters
``````````

==========  ======  ========  ===========
Name        Type    Optional  Description
==========  ======  ========  ===========
path        string
properties  array   X
==========  ======  ========  ===========

Properties
``````````

=======================  ========  ===  ===  ===========
Name                     Type      XLS  ODS  Description
=======================  ========  ===  ===  ===========
coordinates              string    X         Cell coordinates like 'A1'
description              string
height                   int       X
name                     string
offsetX                  int
offsetY                  int
resizeProportional       boolean   X
rotation                 int
shadow                   array
 \+ alignment            string              Possible values are defined in PhpOffice\PhpSpreadsheet\Worksheet\Drawing\Shadow
 \+ alpha                int
 \+ blurRadius           int
 \+ color                string              A hexadecimal color string like '000000' (without #)
 \+ direction            int
 \+ distance             int
 \+ visible              boolean
width                    int       X
=======================  ========  ===  ===  ===========

Example
```````

.. code-block:: twig

    {% xlsdrawing '/test.png' {
        coordinates: 'A1',
        description: 'Test',
        height: 0,
        name: '',
        offsetX: 0,
        offsetY: 0,
        resizeProportional: true,
        rotation: 0,
        shadow: {
            alignment: 'br',
            alpha: 50,
            blurRadius: 6,
            color: '000000',
            direction: 0,
            distance: 2,
            visible: false
        },
        width: 0
    } %}
