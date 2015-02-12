<?php

/**
 * Class XCustomTables
 */
class XCustomTables extends WP_List_Table
{

    public $pageName;
    public $tableName;
    protected $model;
    protected $fmodel;
    protected $msgAction;
    protected $jsFiles = array();
    protected $jsCodes = array();
    protected $alias = 'abcdefghijklmnopqrstuvwxyz';

    /**
     * COnstructor
     * @global type $status
     * @global type $page
     * @param type $tableName
     */
    function __construct($tableName)
    {
        global $status, $page;

        // Populate pageName & tableName
        $this->tableName = $tableName;
        $this->pageName = 'xct' . $this->tableName;

        // Load model for current tableName
        $this->model = $this->loadModel($tableName);

        //Set parent defaults
        parent::__construct(array(
            'singular' => $this->model->tableHead['singular'], //singular name of the listed records
            'plural' => $this->model->tableHead['plural'], //plural name of the listed records
            'ajax' => false //does this table support ajax?
        ));
        // Add a custom admin_head (for this, only styles configured)
        if (!isset($_REQUEST['type']) || $_REQUEST['type'] != 'ajax')
            add_action('admin_head', array(&$this, 'admin_header'));

        if (isset($_REQUEST['action']) && method_exists($this, 'custom_' . $_REQUEST['action'])) {
            $this->{'custom_' . $_REQUEST['action']}();
        }
    }

    /**
     * Custom default
     * This method is called for each column found in the array returned by sql result
     * 
     * @param type $item
     * @param type $column_name
     * @return type
     */
    function column_default($item, $column_name)
    {
        return $this->model->column_default($item, $column_name, $this);
    }

    function process_add()
    {
        global $wpdb;
        $sql = "INSERT INTO " . $this->model->tableHead['tableName'];
        $sqlkeys = '';
        $sqlvalues = '';
        if (!isset($this->model->tableHead['addRecordDefault']) || $this->model->tableHead['addRecordDefault'] == '') {
            $sql .= ' (' . $this->model->tableHead['primaryKey'] . ') VALUES (null) ';
        } else {
            foreach ($this->model->tableHead['addRecordDefault'] as $key => $val) {
                $sqlkeys .= $key . ",";
                $sqlvalues .= $val . ",";
            }
            $sqlkeys = rtrim($sqlkeys, ',');
            $sqlvalues = rtrim($sqlvalues, ',');
            if ($sqlkeys != '' && $sqlvalues != '')
                $sql = $sql . " (" . $sqlkeys . ") VALUES (" . $sqlvalues . ")";
        }
        if (isset($_REQUEST['debug'])) {
            echo "<hr><strong>XCustomTables DEBUG</strong><hr>INSERT : $sql <br />";
        }
        $retcode = $wpdb->query($sql);
        return $wpdb->insert_id;
    }

    function custom_update()
    {
        global $wpdb;
        header('Content-Type: application/json');

        $fieldName = trim(@$_POST['name']);
        $fieldPK = trim(@$_POST['pk']);
        $fieldValue = trim(@$_POST['value']);

        $result = array('success' => false, 'msg' => 'Record not updated', 'data' => '');
        if (strpos($this->alias, substr($fieldName, 0, 1)) == 0) {
            $fieldName = substr($fieldName, 2);
            $sql = "UPDATE " . $this->model->tableHead['tableName'] . " SET $fieldName = '" . nl2br(strip_tags($fieldValue)) . "' WHERE id = " . $fieldPK;
            //echo $sql;
            $retcode = $wpdb->query($sql);
            if ($retcode)
                $result = array('success' => true, 'msg' => 'Record updated', 'data' => '');
        }
        else {
            $foreignPos = strpos($this->alias, substr($fieldName, 0, 1)) - 1;
            $fieldName = substr($fieldName, 2);
            $fieldNameLocalKeys = array_keys($this->model->tableHead['foreignKey']);
            $fieldNameLocal = $fieldNameLocalKeys[$foreignPos];
            $sql = "UPDATE " . $this->model->tableHead['tableName'] . " SET $fieldNameLocal = '" . nl2br(strip_tags($fieldValue)) . "' WHERE id = " . $fieldPK;
            //echo $sql;
            $retcode = $wpdb->query($sql);
            if ($retcode)
                $result = array('success' => true, 'msg' => 'Record updated', 'data' => '');
        }

        echo json_encode($result);
        die();
    }

    public function custom_getlist()
    {
        global $wpdb;
        header('Content-Type: application/json');

        $fieldList = trim(@$_GET['field']);
        $fieldSearch = trim(@$_GET['query']);

        $tableIndex = substr($fieldList, 0, 2);
        if (strpos($this->alias, substr($fieldList, 0, 1) == 0)) {
            $fieldName = substr($fieldList, 2);
            $sql = "SELECT DISTINCT $fieldName as value, $fieldName as text FROM " . $this->model->tableHead['tableName'] . " ORDER BY $fieldName ASC LIMIT 100";
            $data = $wpdb->get_results($sql, ARRAY_A);
        } else {
            $foreignPos = strpos($this->alias, substr($fieldList, 0, 1)) - 1;
            $fieldName = substr($fieldList, 2);
            $ftableNameValues = array_values($this->model->tableHead['foreignKey']);
            $ftableName = $ftableNameValues[$foreignPos][0];
            $fmodel = $this->loadModel($ftableName);
            $sql = "SELECT DISTINCT " . $fmodel->tableHead['primaryKey'] . " as value,$fieldName as text FROM " . $fmodel->tableHead['tableName'];
            if ($fieldSearch != '')
                $sql .= " WHERE $fieldName like '%$fieldName%'";
            //echo $sql;
            $data = $wpdb->get_results($sql, ARRAY_A);
            echo json_encode($data);
            die();
        }

        echo json_encode($data);
        die();
    }

    /**
     * Custom checkbox definition
     * @param type $item
     * @return type
     */
    function column_cb($item)
    {
        return sprintf(
                '<input type="checkbox" name="%1$s[]" value="%2$s" />', $this->_args['singular'], $item['a_' . $this->model->tableHead['primaryKey']]
        );
    }

    /**
     * get Columns obtaing all column headers 
     * @return type
     */
    function get_columns()
    {
        $columns = array(
            'cb' => '<input type="checkbox" />', //Render a checkbox instead of text
        );
        $lastAlias = 'a';
        foreach ($this->model->tableFields as $key => $val) {
            if ($key == $this->model->tableHead['primaryKey'] && $this->model->tableHead['showids'] === false)
                continue;
            if (isset($this->model->tableHead['foreignKey'][$key])) {
                $ftableName = $this->model->tableHead['foreignKey'][$key][0];
                $fmodel = $this->loadModel($ftableName);
                foreach ($fmodel->tableFields as $key2 => $val2) {
                    if ($key2 == $this->model->tableHead['foreignKey'][$key][2]) {
                        $lastAlias = $this->nextAlias($lastAlias);
                        if (isset($this->model->tableHead['foreignKey'][$key][3])) {
                            $columns[$lastAlias . '_' . $key2] = __($this->model->tableHead['foreignKey'][$key][3], XCTAB_LANG);
                        } else {
                            $columns[$lastAlias . '_' . $key2] = __($val['label'], XCTAB_LANG);
                        }
                    }
                }
            } else
                $columns['a_' . $key] = __($val['label'], XCTAB_LANG);
        }
        return $columns;
    }

    /**
     * Get sortable columns defines wich column can be sortable
     * @return boolean
     */
    function get_sortable_columns()
    {
        $sortable_columns = array();
        $lastAlias = 'a';
        foreach ($this->model->tableFields as $key => $val) {
            if ($val['sortable'] !== true)
                continue;
            if (isset($this->model->tableHead['foreignKey'][$key])) {
                $lastAlias = $this->nextAlias($lastAlias);
                $sortable_columns[$lastAlias . '_' . $this->model->tableHead['foreignKey'][$key][2]] = array($lastAlias . '_' . $this->model->tableHead['foreignKey'][$key][2], false);
            }
            $sortable_columns['a_' . $key] = array('a_' . $key, false);
        }
        return $sortable_columns;
    }

    /**
     * Custom admin header adds, styles the columns
     */
    function admin_header()
    {
        if (isset($_REQUEST['page']) && $this->pageName == $_REQUEST['page']) {
            echo '<style type="text/css">';
            echo ' #the-list td {overflow:auto;}';
            echo '.wp-list-table .column-cb { text-align:left; width: 2%; }';
            foreach ($this->model->tableFields as $key => $val) {
                echo '.wp-list-table .column-a_' . $key . ' { text-align:left; width: ' . $val['width'] . '; }';
            }
            $lastAlias = 'a';
            foreach ($this->model->tableHead['foreignKey'] as $key => $val) {
                $ftableName = $this->model->tableHead['foreignKey'][$key][0];
                $fmodel = $this->loadModel($ftableName);
                if (isset($fmodel->tableFields[$val[2]])) {
                    $lastAlias = $this->nextAlias($lastAlias);
                    echo '.wp-list-table .column-' . $lastAlias . '_' . $val[2] . ' { text-align:left; width: ' . $this->model->tableFields[$key]['width'] . '; }';
                }
            }
            echo '</style>';
        }
    }

    /**
     * Custom no_items found
     */
    function no_items()
    {
        _e('No records found.');
    }

    function get_bulk_actions()
    {
        $actions = array();
        if ($this->model->tableHead['allowDelete'] === true) {
            $actions = array(
                'delete' => __('Borrar', XCTAB_LANG),
            );
        } else {
            $actions = array(
                'no-actgions' => __('No hay acciones disponibles', XCTAB_LANG),
            );
        }
        return $actions;
    }

    function process_bulk_action()
    {

        //Detect when a bulk action is being triggered...
    }

    function process_current_action()
    {
        global $wpdb;

        $fieldNameIDS = strtolower($this->model->tableHead['singular']);
        $fieldIDS = array();
        $fieldName = '';
        $search = '';

        if (isset($_REQUEST[$fieldNameIDS]))
            $fieldIDS = $_REQUEST[$fieldNameIDS];
        if (isset($_REQUEST['field']))
            $fieldName = $_REQUEST['field'];
        if (isset($_REQUEST['s']))
            $search = $_REQUEST['s'];

        switch ($this->current_action()) {
            case 'delete':
                if ($this->model->tableHead['allowDelete']) {
                    $this->model->action_delete($fieldIDS);
                    $this->msgAction = sprintf(__("Borrado de %1\$s registro/s completado !!", XCTAB_LANG), count($fieldIDS));
                } else {
                    $this->msgAction = __("No está permitido el borrado de registro/s de esta tabla !!", XCTAB_LANG);
                }
                return true;
                break;
            case 'edit':
//                if ($this->model->tableHead['allowEdit']) {
//                    $this->model->action_edit($fieldIDS);
//                    xct_render_edit_page();
//                    return false;
//                } else {
//                    $this->msgAction = __("No está permitido la edición de registro/s de esta tabla !!", XCTAB_LANG);
//                }
                return true;
                break;
            case 'add':
                if ($this->model->tableHead['allowAdd']) {
                    $insertId = $this->process_add();
                    $this->msgAction = __("Usa el primer regstro en blanco para editar los valores a insertar.", XCTAB_LANG);
                    $this->addJSCode('jQuery(document).ready(function($){ $("#the-list").find("tr").first().css("background-color","#d2eed2");});');
                    $_REQUEST['orderby'] = 'a_'.$this->model->tableHead['primaryKey'];
                    $_REQUEST['order'] = 'desc';
                    $_REQUEST['action'] = '';
                    return true;
                } else {
                    $this->msgAction = __("No está permitida la inserción de registro/s en esta tabla !!", XCTAB_LANG);
                }
                return true;
                break;
            case 'select':
                $this->model->selectLike($this->model, $fieldName, $search);
                die();
                break;
        }
    }

    function prepare_items()
    {
        global $wpdb;

        if (isset($_REQUEST['wp_screen_options']['value']))
            $per_page = $_REQUEST['wp_screen_options']['value'];
        else
            $per_page = 10;


        $columns = $this->get_columns();
        $hidden = array();
        $sortable = $this->get_sortable_columns();


        $this->_column_headers = array($columns, $hidden, $sortable);

        if ($this->process_current_action() === false)
            die();

        $paged = isset($_REQUEST['paged']) ? max(0, intval($_REQUEST['paged']) - 1) : 0;

        $orderby = (isset($_REQUEST['orderby']) && in_array($_REQUEST['orderby'], array_keys($this->get_sortable_columns()))) ? $_REQUEST['orderby'] : $this->model->tableHead['defaultSort'];

        $order = (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('asc', 'desc'))) ? $_REQUEST['order'] : $this->model->tableHead['defaultSortOrder'];


        $select = "SELECT ";
        $whereIN = "(";

        $lastAlias = 'a';
        foreach ($this->model->tableFields as $key => $val) {
            $select .= "a." . $key . " as a_$key,";
            if (isset($this->model->tableHead['foreignKey'][$key])) {
                $lastAlias = $this->nextAlias($lastAlias);
                $select .= $lastAlias . "." . $this->model->tableHead['foreignKey'][$key][2] . " as " . $lastAlias . "_" . $this->model->tableHead['foreignKey'][$key][2] . ",";
                $whereIN .= isset($_REQUEST['s']) ? ' ' . $lastAlias . "." . $this->model->tableHead['foreignKey'][$key][2] . " like '%" . $_REQUEST['s'] . "%' OR " : "";
            }
            $whereIN .= isset($_REQUEST['s']) ? ' a.' . $key . " like '%" . $_REQUEST['s'] . "%' OR" : "";
        }
        $select = rtrim($select, ',');

        $select .= " FROM " . $this->model->tableHead['tableName'] . " a";
        $selectCount = "SELECT COUNT(1) as count FROM " . $this->model->tableHead['tableName'] . " a";

        $lastAlias = 'a';
        if (isset($this->model->tableHead['foreignKey'])) {
            foreach ($this->model->tableHead['foreignKey'] as $key => $val) {
                $lastAlias = $this->nextAlias($lastAlias);
                $ftableName = $val[0];
                $fmodel = $this->loadModel($ftableName);
                $select .= ' LEFT JOIN ' . $fmodel->tableHead['tableName'] . " $lastAlias ON a." . $key . " = $lastAlias." . $val[1];
                $selectCount .= ' LEFT JOIN ' . $fmodel->tableHead['tableName'] . " $lastAlias ON a." . $key . " = $lastAlias." . $val[1];
            }
        }
        $whereIN = basename($whereIN, ' OR') . ")";

        if ($whereIN == "()")
            $whereIN = ' WHERE 1=1';
        else {
            $whereIN = "WHERE " . $whereIN;
            $paged = 0;
        }
        if (isset($this->model->tableHead['baseWhere']) && $this->model->tableHead['baseWhere'] != '')
            $whereIN .= ' AND ' . $this->model->tableHead['baseWhere'];

        $select .= " $whereIN ORDER BY $orderby $order LIMIT $per_page OFFSET " . $paged * $per_page;
        $total_items = $wpdb->get_var("$selectCount $whereIN");

        $data = $wpdb->get_results($select, ARRAY_A);

        if (isset($_REQUEST['debug'])) {
            echo "<hr><strong>XCustomTables DEBUG</strong><hr>SELECT : $select COUNT:" . sizeof(data) . "<br />";
            echo "SELECTCOUNT: $selectCount $whereIN COUNT:" . $total_items . "<hr>";
        }

        $this->set_pagination_args(array(
            'total_items' => $total_items,
            'per_page' => $per_page,
            'total_pages' => ceil($total_items / $per_page)
        ));

        $current_page = $this->get_pagenum();
        $this->items = $data;

        $this->set_pagination_args(array(
            'total_items' => $total_items, //WE have to calculate the total number of items
            'per_page' => $per_page, //WE have to determine how many items to show on a page
            'total_pages' => ceil($total_items / $per_page)   //WE have to calculate the total number of pages
        ));
    }

    protected function nextAlias($lastAlias)
    {
        return substr($this->alias, strpos($this->alias, $lastAlias) + 1, 1);
    }

    protected function loadModel($model)
    {
        if (isset($this->fmodel[$model]))
            return $this->fmodel[$model];
        require_once __DIR__ . "/models/$model.php";
        $model = "$model" . "Model";
        $this->fmodel[$model] = new $model;
        return $this->fmodel[$model];
    }

    public function showMsgAction()
    {
        if ($this->msgAction != '')
            echo '<div style="display:block;" class="updated"><p>' . $this->msgAction . '</p></div>';
    }

    public function addJSFile($jsFile)
    {
        $this->jsFiles[] = "<script type='text/javascript' src='".plugins_url()."/xcustomtables/" . $jsFile . "?v=" . XCT_JS_VERSION . "'></script>\n";
    }

    public function addJSCode($jsCode)
    {
        $this->jsCodes[] = "<script type='text/javascript'>$jsCode</script>\n";
    }

    public function print_js()
    {
        foreach ($this->jsFiles as $val) {
            echo $val . "\n";
        }
        foreach ($this->jsCodes as $val) {
            echo $val . "\n";
        }
    }

    public function dinamyc_edit_js()
    {
        if (!isset($this->model->tableHead['allowEdit']) || $this->model->tableHead['allowEdit'] == false)
            return;
        if (count($this->items) == 0)
            return;
        $rand = rand(10000000, 99999999);
        include __DIR__ . '/xctcore/views/header.php';
        $columns = reset($this->items);

        $lastfield = '';
        foreach ($columns as $key => $val) {
            $field = substr($key, 2);
            //echo "FIELD = $field \n";
            if (isset($this->model->tableFields[$field]['foreigntype'])) {
                $lastfield = $field;
                continue;
            }
            if (!isset($this->model->tableFields[$field]) && $lastfield != '') {
                if (isset($this->model->tableHead['foreignKey'][$lastfield][2])) {
                    $field = $lastfield;
                }
            }
            if (!isset($this->model->tableFields[$field]['editable']) || $this->model->tableFields[$field]['editable'] === false)
                continue;

            $editView = isset($this->model->tableFields[$lastfield]['foreigntype']) ? $this->model->tableFields[$lastfield]['foreigntype'] : $this->model->tableFields[$field]['type'];

            if (method_exists($this->model, "defaultSelectValues_$field")) {
                $defaultSelectValues = $this->model->{"defaultSelectValues_$field"}();
            } else
                $defaultSelectValues = array();

            if (file_exists(__DIR__ . '/views/' . $editView . '.php')) {
                include __DIR__ . '/views/' . $editView . '.php';
                $innerJSColumn = $innerJS;
                $innerJSColumn = str_replace('[[COLUMN]]', $key, $innerJSColumn);
                $innerJSColumn = str_replace('[[TITLE]]', $this->model->tableFields[$field]['label'], $innerJSColumn);
                $innerJSColumn = str_replace('[[DATATYPE]]', $this->model->tableFields[$field]['type'], $innerJSColumn);
                $innerJSColumn = str_replace('[[MAXLENGTH]]', $this->model->tableFields[$field]['length'], $innerJSColumn);
                echo $innerJSColumn;
            } elseif (file_exists(__DIR__ . '/xctcore/views/' . $editView . '.php')) {
                include __DIR__ . '/xctcore/views/' . $editView . '.php';
                $innerJSColumn = $innerJS;
                $innerJSColumn = str_replace('[[COLUMN]]', $key, $innerJSColumn);
                $innerJSColumn = str_replace('[[TITLE]]', $this->model->tableFields[$field]['label'], $innerJSColumn);
                $innerJSColumn = str_replace('[[DATATYPE]]', $this->model->tableFields[$field]['type'], $innerJSColumn);
                $innerJSColumn = str_replace('[[MAXLENGTH]]', $this->model->tableFields[$field]['length'], $innerJSColumn);
                echo $innerJSColumn;
            }
            $lastfield = $field;
        }
        include __DIR__ . '/xctcore/views/footer.php';
    }

}

// END CLASS

