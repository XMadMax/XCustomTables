<?php
class xctBaseModel
{
    public function action_delete($fieldIDS)	
    {
	global $wpdb;
	array_walk($fieldIDS,function(&$val,$key){
	    $val = "'".$val."'";
	});
	$fieldIDSText = implode(',',$fieldIDS);
	$sql = "DELETE FROM ".$this->tableHead['tableName']." WHERE ".$this->tableHead['primaryKey']." IN ($fieldIDSText)";
	//echo "<hr>$sql<hr>";exit;
	$result = $wpdb->get_var($sql);

	return $result;
    }

    public function column_default($item, $column_name, $parent)
	{
		$column_name = trim($column_name);
		$base_column_name = substr($column_name, 0, 1);
		$column_name = substr($column_name, 2);
		$return = '';

		$actions = array();

		if (method_exists($this, 'column_' . $column_name))
			$this->{'column_' . $column_name}($item);

		if (isset($this->tableFields["$column_name"]['label'])) {
			parse_str($_SERVER["QUERY_STRING"], $currentNavOptions);
			if ($column_name == $this->tableHead['columnOptions'] && $this->tableHead['allowDelete'] === true) {
				$parseNavOptions = array();
				isset($currentNavOptions['orderby'])?$parseNavOptions['orderby']=$currentNavOptions['orderby']:'';;
				isset($currentNavOptions['order'])?$parseNavOptions['order']=$currentNavOptions['order']:'';;
				$actions['delete'] = sprintf('<a href="?page=%s&action=%s&%s&' . strtolower($this->tableHead['singular']) . '[]=%s">' . __('Borrar', XCTAB_LANG) . '</a>', $_REQUEST['page'], 'delete', http_build_query($parseNavOptions), $item['a_' . $this->tableHead['primaryKey']]);
			}
//			if ($column_name == $this->tableHead['columnOptions'] && $this->tableHead['allowEdit'] === true) {
//				$actions['edit'] = sprintf('<a href="?page=%s&action=%s&' . strtolower($this->tableHead['singular']) . '=%s">' . __('Editar', XCTAB_LANG) . '</a>', $_REQUEST['page'], 'edit', $item['a_' . $this->tableHead['primaryKey']]);
//			}
			if ($base_column_name == 'a' && $this->tableHead['showids'] === false && $column_name != $this->tableHead['primaryKey'] && $column_name == $this->tableHead['columnOptions']) {
				$return = sprintf('%1$s <br><span style="color:silver">(ID: %2$s)</span>%3$s', $item['a_' . $column_name], $item['a_' . $this->tableHead['primaryKey']], $parent->row_actions($actions));
			} else if ($base_column_name == 'a' && $this->tableHead['showids'] === true && $column_name != $this->tableHead['primaryKey'] && $column_name == $this->tableHead['columnOptions']) {
				$return = sprintf('%1$s <br>%3$s', $item['a_' . $column_name], $item['a_' . $this->tableHead['primaryKey']], $parent->row_actions($actions));
			} else if ($base_column_name == 'a' && $column_name == $this->tableHead['primaryKey'] && $column_name == $this->tableHead['columnOptions']) {
				$return = sprintf('%1$s <br>%3$s', $item['a_' . $column_name], $item['a_' . $this->tableHead['primaryKey']], $parent->row_actions($actions));
			} else if ($this->tableFields["$column_name"]['type'] == 'img' && $item[$base_column_name . '_' . $column_name] != '') {
				$return = sprintf('<img class="xctimage" src="%1$s%2$s" width="80" />', XCT_MEDIA_UPLOAD_BASEPATH, $item['a_' . $column_name]);
			} else {
				$return = $item[$base_column_name . '_' . $column_name];
			}
		} else {
			$return = $item[$base_column_name . '_' . $column_name];
		}

		return $return;
	}

	public function xaction_edit($ID)
    {
    }
    
    public function selectLike($model, $field, $text)
    {
	global $wpdb;
	$sql = "SELECT ".$model->tableHead['primaryKey'].",".$field." FROM ".$model->tableHead['tableName']." WHERE ".$field." LIKE '".$text."%'";
//echo $sql;
	$result = $wpdb->get_results($sql,ARRAY_A);
	$response = array();
	foreach ($result as $key => $val)
	{
	    $response[] = array('value' => $val[$model->tableHead['primaryKey']],'text' => $val[$field]);
	}
	
	//header('Content-type: application/json');
	echo json_encode($response);

    }
    
    
}
