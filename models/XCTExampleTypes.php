<?php

class XCTExampleTypesModel extends xctBaseModel
{

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
        'defaultSortOder' => 'asc',
        'showinMenu' => true
    );
    public $tableFields = array(
        'id' => array(
            'label' => 'ID',
            'validation' => '',
            'type' => 'int',
            'length' => 20,
            'width' => '50px',
            'sortable' => true,
            'editable' => false,
        ),
        'description' => array(
            'label' => 'Description',
            'validation' => '',
            'type' => 'text',
            'width' => '400px',
            'length' => 255,
            'sortable' => true,
            'editable' => true,
        ),
    );

}
