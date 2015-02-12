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
