<?php
/*****************************************************************************/
/** @class HtmlTable
 *  @brief  Create html tables
 * 
 *  This class creates html tables.
 *  Create a table object, define the elements with optional attributes and pass your content.
 *  Several methods allows you to set table rows, columns, header and footer element. Also you can define an array with column widths.
 *  The class provides changing class in table rows of body elements using modulo.
 *  You can define the class names and the row number for line change.
 *  CSS classes are needed using this option for class change !
 *  This class supports strings, arrays, bi dimensional arrays and associative arrays for creating the table content.
 *  @par Notice
 *  Tables should be styled by CSS !
 *  Attributes, like 'align', 'bgcolor',... are worse style,
 *  and deprecated in HTML5. Please check the reference.
 *  @par Data array for example
 *  @code
 *  $dataArray = array('Data 1', 'Data 2', 'Data 3');
 *  @endcode
 *  @par Example_1
 *  @code
 *  // Example without defining table head and table foot elements.
 *  // Starting a row directly, all missing table elements are set automatically for semantic table.
 *  // Create an table instance with optional table ID, table class.
 *  $table = new HtmlTable('Id_Example_1', 'tableClass');
 *  // For each key => value a column is to be defined in a table row.
 *  $table->addRow($dataArray);
 *  // get validated table
 *  echo $table->getHtmlTable();
 *  @endcode
 *  @par Example_2
 *  @code
 *  // Create an table instance with optional table ID, table class and border
 *  $table = new HtmlTable('Id_Example_2', 'tableClass', 1);
 *  // we can also set further attributes for the table
 *  $table->addAttribute('style', 'width: 100%;');
 *  $table->addAttribute('summary', 'Example');
 *  // add table header with class attribute and a column as string
 *  $table->addTableHeader('class', 'name', 'columntext', 'th'); // $col paremeter 'th' is set by dafault to 'td'
 *  // add next row to the header
 *  $table->addRow('... some more text ...'); // optional parameters ( $content, $attribute, $value, $col = 'td')
 *  // Third row we can also pass single arrays, bidimensional arrays, and assoc. arrays
 *  // For each key => value a column is to be defined in a table row
 *  $table->addRow($dataArray);
 *  // add the table footer
 *  $table->addTableFooter('class', 'foot', 'Licensed by Admidio');
 *  // add a body element
 *  $table->addTableBody('class', 'body', $dataArray);
 *  // also we can set further body elements
 *  $table->addTableBody('class', 'nextBody', $dataArray);
 *  // in this body elemtent for example, we want to define the cols in a table row programmatically
 *  // define a new row
 *  $table->addRow(); // no data and no attributes for this row
 *  $table->addColumn('col1');
 *  $table->addColumn('col2', 'class', 'secondColumn'); // this col has a class attribute
 *  $table->addColumn('col3');
 *  // also we can pass our Array at the end
 *  $table->addColumn($dataArray);
 *  // get validated table
 *  echo $table->getHtmlTable();
 *  @endcode
 *  @par Example 3
 *  @code
 *  // Example with fixed columns width and changing classes for rows in body element and table border
 *  $table = new HtmlTable('Id_Example_3', 'tableClass', 1);
 *  // Set table width to 600px. Ok, we should do this in the class or id in CSS ! However,...
 *  $table->addAttribute('style', 'width: 600px;');
 *  // Define columms width as array
 *  $table->setColumnsWidth(array('20%', '20%', '60%'));
 *  // We also want to have changing class in every 3rd table row in the table body
 *  $table->setClassChange('class_1', 'class_2', 3); // Parameters: class names and integer for the line ( Default: 2 )
 *  // Define a table header with class="head" and define a column string (arrays are also possible)
 *  // and Set a header element for the column (Default: 'td')
 *  $table->addTableHeader('class', 'head', 'Headline_1', 'th');
 *  // 2 more columns ...
 *  $table->addColumn('Headline_2', '', '', 'th'); // no attribute/value in this example
 *  $table->addColumn('Headline_3', '', '', 'th'); // no attribute/value in this example
 *  // Define the footer with a string in center position
 *  $table->addTableFooter();
 *  // First mention that we do not want to have fixed columns in the footer. So we clear the array and set the text to center positon!
 *  $table->setColumnsWidth(array());
 *  // Define a new table row
 *  $table->addRow();
 *  // Add the column with colspan attribute
 *  $table->addColumn('', 'colspan', '3'); // no data here, because first do the settings and after finishend pass the content !
 *  // Define center position for the text
 *  $table->addAttribute('align', 'center'); // ok, it is worse style! 
 *  // Now we can set the data if all settings are done!
 *  $table->addData('Tablefooter');
 *  // Now set the body element of the table
 *  // Remember we deleted the columns width array, so we need to set it again
 *  $table->setColumnsWidth(array('20%', '20%', '60%'));
 *  // Define a table row with array or string for first column
 *  $table->addTableBody('class', 'body', $dataArray);
 *  // Some more rows with changeclass mode in body element
 *  $table->addRow($dataArray);
 *  $table->addRow($dataArray);
 *  $table->addRow($dataArray);
 *  $table->addRow($dataArray);
 *  echo $table->getHtmlTable();
 *  @endcode
 */
/*****************************************************************************
 *
 *  Copyright    : (c) 2004 - 2013 The Admidio Team
 *  Author       : Thomas-RCV
 *  Homepage     : http://www.admidio.org
 *  License      : GNU Public License 2 http://www.gnu.org/licenses/gpl-2.0.html
 *
 *****************************************************************************/

class HtmlTable extends HtmlElement {

    private $border;                     ///< String with border attribute and value of the table
    private $lineChange;                ///< Integer value for class change mode for table rows.
    private $class_1;                    ///< Class name for standard design of table rows 
    private $class_2;                    ///< Class name for changed design of table rows 
    private $changeClass;                ///< Class name for the next table row using class change mode
    private $columnsWidth;               ///< Array with values for the columns width
    protected $thead;                    ///< Internal Flag for setted thead element
    protected $tfoot;                    ///< Internal Flag for setted tfoot element
    protected $tbody;                    ///< Internal Flag for setted tbody element
    private $columnCount;                ///< Counter for setted columns
    private $rowCount;                   ///< Counter for setted rows in body element
    
    /**
     * Constructor initializing all class variables
     * 
     * @param $id Id of the table
     * @param $class Class name of the table
     * @param $border Set table border
     */
    public function __construct($id = '', $class = '', $border = 0)
    {        
        $this->border       = (is_numeric($border))? $border : 0;
        $this->lineChange  = '';
        $this->columsWidth  = array();
        $this->changeclass  = '';
        $this->thead        = -1;
        $this->tfoot        = -1;
        $this->tbody        = -1;
        $this->columnCount  = 0;
        $this->rowCount     = 1;
        
        parent::__construct('table', '', '', true);
        
        if(strlen($id) > 0)
        {
            $this->addAttribute('id', $id);
        }
        
        if(strlen($class) > 0)
        {
            $this->addAttribute('class', $class);
        }
        
        if($border == 1)
        {
            $this->addAttribute('border', '1');
        }
    } 

    /**
     *  @par Add Columns to current table row.
     *  This method defines the columns for the current table row.
     *  The data can be passed as string or array. Using Arrays, for each key/value a new column is set.
     *  You can define an attribute for each column. If you need further attributes for the column first do the settings with addAttribute();
     *  If all settings are done for the column use the addData(); to define your column content.
     *
     *  @param $data Content for the column as string, or array
     *  @param $attribute Attribute
     *  @param $value Value of the attribute
     *  @param $col Column element 'td' or 'th' (Default: 'td')
     */
    public function addColumn($data = '', $attribute = '', $value = '', $col = 'td')
    {
        if($col == 'td' || $col == 'th')
        {
            $this->addElement($col);
        }

        if(!empty($this->columnsWidth))
        {
            $this->addAttribute('style', 'width:' . $this->columnsWidth[$this->columnCount]);
        }

        if($attribute != '' && $value != '')
        {
            $this->addAttribute($attribute, $value);
        }


        if($data != '')
        {
            $this->addData($data);
            $this->columnCount ++;
        }
    }

    /**
     *  @par Add new table row.
     *  Starting the table table directly with a row, the class automatically defines 'thead' and 'tfoot' element with an empty row.
     *  The method checks if a row is already defined and must be closed first.
     *  You can define 1 attribute/value pair for the row, calling the method. If you need further attributes for the new row, use method addAttribute(), before passing the content.
     *  The element and attributes are stored in buffer first and will be parsed and written in the output string if the content is defined.
     *  After all settings are done use addColumn(); to define your columns with content.
     *
     *
     *  @param $data Content for the table row as string, or array
     *  @param $attribute Attribute
     *  @param $value Value of the attribute
     *  @param $col Column element 'td' or 'th' (Default: 'td')
     */
    public function addRow($data = '', $attribute = '', $value = '', $col = 'td')
    {
        // Clear column counter
        $this->columnCount = 0;

        if($this->thead == -1)
        {
            // if no table elements are defined then create it for semantic markup
            $this->addTableHeader();
            $this->addElement('tr', '' ,'', '<td></td>');
            $this->closeParentElement('thead');
            $this->thead = 1;

            $this->addTableFooter();
            $this->addElement('tr', '' ,'', '<td></td>');
            $this->closeParentElement('tfoot');
            $this->tfoot = 1;

            $this->addTableBody();
            $this->thead = 1;
            $this->tfoot = 1;
            $this->tbody = 1;
        }
        // If row is active we must close it first before starting new one
        if(in_array('tr', $this->arrParentElements))
        {
            $this->closeParentElement('tr');
        }

        if($this->lineChange == '' && empty($this->columnsWidth))
        {
            $this->addParentElement('tr');
            // if class change is not set and no cols width are available
            if($attribute != '' && $value != '')
            {
                $this->addAttribute($attribute, $value);
            }

            if($data != '')
            {
                $this->addColumn($data, '', '', $col);
                $this->closeParentElement('tr');
            }

        }
        elseif($this->lineChange == '' && !empty($this->columnsWidth))
        {
            $this->addParentElement('tr');
            // if class change is not set and cols width are available

            if($attribute != '' && $value != '')
            {
                $this->addAttribute($attribute, $value);
            }
            if($data != '')
            {
                if(is_array($data))
                {
                    foreach($data as $column)
                    {
                        $style = $this->columnsWidth[$this->columnCount];
                        $this->addColumn($column, '', '', $col);
                    }
                }
                else
                {
                    // String
                    $this->addColumn($data, '', '', $col);
                }
            }
        }
        elseif($this->lineChange != '' && empty($this->columnsWidth))
        {
            $this->addParentElement('tr');
            // if class change is set and no cols width are available
            if($attribute != '' && $value != '' && $attribute != 'class')
            {
                $this->addAttribute($attribute, $value);
            }

            if($this->tbody == 1)
            {
                // Only allowed in body element of the table
                if($this->rowCount % $this->lineChange == 0)
                {
                    $this->changeclass = $this->class_1;
                }
                else
                {
                    $this->changeclass = $this->class_2;
                }    
                $modulo = $this->changeclass;
                $this->addAttribute('class', $modulo, 'tr');
                $this->rowCount ++;
            }

            if($data != '')
            {
                if(is_array($data))
                {
                    foreach($data as $column)
                    {
                        $style = $this->columnsWidth[$this->columnCount];
                        $this->addColumn($column, '', '', $col);
                    }
                }
                else
                {
                        $this->addColumn($data, '', '', $col);
                }
            }
        }
        else
        {
            $this->addParentElement('tr');
            // class change and cols width are set
            if($attribute != '' && $value != '' && $attribute != 'class')
            {
                $this->addAttribute($attribute, $value);
            }

            if($this->tbody == 1)
            {
                // Only allowed in body element of the table
                if($this->rowCount % $this->lineChange == 0)
                {
                    $this->changeclass = $this->class_1;
                }
                else
                {
                    $this->changeclass = $this->class_2;
                }
                $modulo = $this->changeclass;
                $this->addAttribute('class', $modulo, 'tr');
                $this->rowCount ++;
            }

            if($data != '')
            {
                if(is_array($data))
                {
                    foreach($data as $column)
                    {
                        $style = $this->columnsWidth[$this->columnCount];
                        $this->addColumn($column, '', '', $col);
                    }
                }
                else
                {
                    // String
                    $this->addColumn($data, '', '', $col);
                }
            }
        }
    }

    /**
     *  @par Define table body.
     *  Please have a look at the description addRow(); and addColumn(); how you can define further attribute settings
     *
     *  @param $attribute Attribute
     *  @param $value Value of the attribute
     *  @param $data Content for the element as string, or array
     */
    public function addTableBody($attribute = '', $value = '', $data = '', $col = 'td')
    {
        if($this->tfoot != -1 && in_array('tfoot', $this->arrParentElements));
        {
            $this->closeParentElement('tr');
        }

        $this->closeParentElement('tfoot');
        $this->addParentElement('tbody');
        $this->tbody = 1 ;
        if($attribute != '' && $value != '')
        {
            $this->addAttribute($attribute, $value);
        }

        if($data != '')
        {
            $this->addRow($data, '', '', $col);
        }
    }
    
    /**
     *  @par Define table footer
     *  Please have a look at the description addRow(); and addColumn(); how you can define further attribute settings
     *
     *  @param $attribute Attribute
     *  @param $value Value of the attribute
     *  @param $data Content for the element as string, or array
     *  @return Returns @b false if tfoot element is already set
     */
    public function addTableFooter($attribute = '', $value = '', $data = '', $col = 'td')
    {
        if($this->thead != -1 && in_array('thead', $this->arrParentElements));
        {
            $this->closeParentElement('thead');
        }
        // Check if table footer already exists
        if($this->tfoot != 1)
        {
            $this->closeParentElement('thead');
            $this->addParentElement('tfoot');
            $this->tfoot = 1 ;

            if($attribute != '' && $value != '')
            {
                $this->addAttribute($attribute, $value);
            }

            if($data != '')
            {
                $this->addRow($data, '', '', $col);
            }
        }
        return false;
    }
    
    /**
     *  @par Define table header
     *  Please have a look at the description addRow(); and addColumn(); how you can define further attribute settings
     *
     *  @param $attribute Attribute
     *  @param $value Value of the attribute
     *  @param $data Content for the element as string, or array
     *  @return Returns @b false if thead element is already set
     */
    public function addTableHeader($attribute = '', $value = '', $data = '', $col = 'td')
    {
        // Check if table head already exists
        if($this->thead != 1)
        {
            $this->addParentElement('thead');
            $this->thead = 1 ;

            if($attribute != '' && $value != '')
            {
                $this->addAttribute($attribute, $value);
            }

            if($data != '')
            {
                $this->addRow($data, '', '', $col);
            }
        }
        return false;
    }
    
    /**
     * Get the parsed html table
     *
     * @return Returns the validated html table as string
     */
    public function getHtmlTable()
    {
        $this->closeParentElement('tr');
        $this->closeParentElement('tbody');
        $table = $this->getHtmlElement();
        return $table;
    }

    /**
     * @par Set line Change mode
     * In body elements you can use this option. You have to define two class names and a counter as integer value.
     * The first class name is the standard class and the second name is the class used if the class is changed regarding the counter.
     * As default value, every second row is to be changed.
     * 
     * @param $class_1 Name of the standard class used for lineChange mode
     * @param $class_2 Name of the change class used for lineChange mode
     * @param $line Number (integer) of the line that is changed to Class_2 (Default: 2)
     */
    public function setClassChange($class_1 = '', $class_2 = '', $line = 2)
    {
        if(is_numeric($line))
        {
            $this->lineChange = $line;
        }
        else
        {
            return false;
        }
        
        $this->class_1 = $class_1;
        $this->class_2 = $class_2;
    }

    /**
     * Set columns width as array
     * 
     * @param $array Array with values for each column width
     */
    public function setColumnsWidth($array)
    {
        if(is_array($array))
        {
            foreach ($array as $column) 
            {
                if($column != '')
                {
                    $this->columnsWidth[] =  $column;
                }
                else
                {
                    $this->columnsWidth[] =  '';
                }
            }
        }
        return false; 
    } 
} 

?>