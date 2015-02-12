# XCustomTables

XCustomTables is a wordpress plugin to add CRUD functionallity to any table under same database as wordpress.
If you create a custom table, you can easilly add a 'maintenance view' to this table in the same wordpress admin menu.

  - Allows add / edit / detele records
  - Allows foreign keys, and foreign fields to be edited as typeahead
  - Allows text / textarea / date / detetime / picture / select fixed values / select foreign values (in progress) edit fields

This plugin uses [X-editable](http://vitalets.github.io/x-editable/), and integrates perfectlly in wordpress look and feel.
### Version
1.1

### Intallation

* Copy XCustomTables.zip to your wp-content/plugins directory.
* Unzip XCustomTables.zip
* Go to wordpress admin -> plugins
* Create example table in the same mysql database as wordpress, you can use the sql file:
  * .... /wp-contents/plugins/xcustomtables/sql/XCTDemoTables.sql
* Enable XCustomTables Plugin

Once enabled, you will see a 'XCustomTables' option in the admin menu.
Try the option 'Demo Table' and click over blue fields. 

The 'Type' column is Typeahead, will fire after third character typed, try ... 'Type'

### Customize
All tables that you want to be editable must to have it's own model file, where you will configure:
* Titles (table and fields)
* Primary key (primary key must to be an auto_increment column to allow add)
* Options allowed (add / edit / delete)
* Foreign keys
* Default sort
* Showed in wordpress (enabled / disabled)
* Field labels
* Field width
* Fields sortables
* Editable field options

#### Models
The model files are located under ... plugins/xcustomtables/models as ... MyModelName.php

The model field contains:

| Option              | Description                 | Values  |
|:------------------ |:-------------|:-----|
| **$tableHead** | **Array of table options** | **Described below** |
| * tableName | Fisacal table name in database |  |
| * title | Title to be used in wordpress |  |
| * singular | Singular name of table contents |  |
| * plural | Plural name of table contents |  |
| * showids | Show/hide the primary column | true / false |
| * primaryKey | Primary key field | |
| * allowAdd | Allow create new records |
| * allowDelete | Allow record deletion |
| * allowEdit | Allow inline field edition |
| * messageAdd | Message to be showed in top button if add allowed |
| * columnOptions | Where to show 'Delete this row', as wordpress style
| * foreignKey | Define the relation between local and foreign fields | 'localField ' => array ('TableModel', 'foreignFieldID' , 'getForeignFieldValue')
| * baseWhere | Force a recordset, default where clause | "a.fieldName = 'fieldValue' "
| * addRecordDefault | Set default values when a record is added | array('fieldName' => "'defaultValue'",'otherFieldName' => 'NOW()')
| * defaultSort | Sort by this field | "a.fieldName"
| * showinMenu | Show table maintenance in admin menu
| **$tableFields** | **Array of table options** | **Described below** |
| fieldName | Array of fieldName options | fieldname => array() , options below
| * label | Label to be sowed as header of the table maintenande |
| * validation | Validation of field | Still in development
| * type | Type of field for edit | int / text / textarea / combodate / combodatetime / img / select
| * width | Width of field cell (px) | 200px
| * length | Max length of field | Still in development, will be implemented with Validation
| * sortable | If this field is sortable | true / false
| * editable | If this field is editable | true / false

#### Base Model example:
```php
<?php
class XCTExampleModel extends xctBaseModel {
    public $tableHead = array(
	'tableName' => 'XCTDemoTable',
	'title' => 'Demo Table',
	'singular' => 'Record',
	'plural' => 'Records',
	'showids' => true,
	'primaryKey' => 'id',
	'allowAdd' => true,
	'allowDelete' => true,
	'allowEdit' => true,
	'messageAdd' => 'Add new record',
	'columnOptions' => 'id',
	'foreignKey' => array(
		    'typeid' => array('XCTExampleTypes' ,'id', 'description','Type'),
        ),
	'baseWhere' => '',
	'defaultSort' => 'a.id',
	'defaultSortOrder' => 'asc',
        'showinMenu' => true
    );

    public $tableFields = array(
	'id' => array(
		'label' => 'ID',
		'validation' => '',
		'type' => 'int',
		'length' => 20,
		'width' => '100px',
		'sortable' => true,
		'editable' => false,
		),
	'title' => array(
		'label' => 'Title',
		'validation' => '',
		'type' => 'text',
		'width' => '200px',
		'length' => 50,
		'sortable' => true,
		'editable' => true,
		),
	'description' => array(
		'label' => 'Description',
		'validation' => '',
		'type' => 'textarea',
		'width' => '200px',
		'length' => 1024,
		'sortable' => true,
		'editable' => true,
		),
	'date_add' => array(
		'label' => 'Date add',
		'validation' => '',
		'type' => 'combodatetime',
		'width' => '100px',
		'length' => 20,
		'sortable' => true,
		'editable' => true,
		),
	'typeid' => array(
		'label' => 'Parent',
		'validation' => '',
		'type' => 'int',
		'length' => 50,
		'width' => '100px',
		'sortable' => true,
		'editable' => true,
		'foreigntype' => 'typeahead',
		),
        'active' => array (
		'label' => 'Active',
		'validation' => '',
		'type' => 'select',
		'width' => '60px',
		'length' => '15',
		'sortable' => true,
		'editable' => true,
		),
    );
    
    public function defaultSelectValues_active()
    {
	    return array(
		0 => \__('No',XCTAB_LANG), 
		1 => \__('Yes',XCTAB_LANG), 
		);
    }
}
```

#### Example for foreign key demo:
```php
<?php
class XCTExampleTypesModel extends xctBaseModel {
    public $tableHead = array(
	    'tableName' => 'XCTDemoTableTypes',
	    'title' => 'Demo Table Types',
	    'singular' => 'Record',
	    'plural' => 'Records',
	    'showids' => true,
	    'primaryKey' => 'id',
	    'allowAdd' => true,
	    'allowDelete' => true,
	    'allowEdit' => true,
	    'messageAdd' => 'Add new record',
	    'columnOptions' => 'id',
	    'baseWhere' => '',
	    'defaultSort' => 'a.description',
        'showinMenu' => true
    );

    public $tableFields = array(
	'id' => array(
		'label' => 'ID',
		'validation' => '',
		'type' => 'int',
		'length' => 20,
		'width' => '100px',
		'sortable' => true,
		'editable' => false,
		),
	'description' => array(
		'label' => 'Description',
		'validation' => '',
		'type' => 'text',
		'width' => '200px',
		'length' => 255,
		'sortable' => true,
		'editable' => true,
		),
    );
    
}
```

#### Custom methods
In any model you can customize the values for each column, adding a method 'column_**fieldname**(&$item)', example:
```php
    public function column_description(&$item)
    {
	    $item['a_description'] = '<b>'.nl2br($item['a_description'])."</b>";
    }

```
This method will show all values of 'description field bold and &lt;br&gt; transformed to nl.
The prefix 'a_' is added for primary table. When a foreign key is defined, 'b_' is used for first join, 'c_' for second join....

For custom select values, insert a method that will return all values, as defaultSelectValues_**fieldname** :
```php
    public function defaultSelectValues_fieldname()
    {
	    return array(
		    0 => \__('No',XCTAB_LANG), 
		    1 => \__('Yes',XCTAB_LANG), 
		);
    }
```

#### Translation
English & spanish translated, you can add any other in plugins/xcunstomtables/languages
XCustomTables will use the language defined by wordpress, default to en_US.

#### 

### Todo's

 - Validation rules
 - Image upload
 - Checkbox field type
 - Wysiwyg html5 editor

### License
MIT

