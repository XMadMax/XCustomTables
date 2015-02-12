<?php
function xct_get_all_modules()
{
    $allModelsClasses = array();
    $cdir = scandir(__DIR__ . '/models/');
    foreach ($cdir as $key => $value) {
	if (!in_array($value, array(".", ".."))) {
	    if (substr($value,0,1) != '.' && substr($value,0,1) != '_' && $value != basename($value, '.php'))
	    {
		$model = basename($value, '.php');
		require_once __DIR__ . "/models/$model.php";
		$modelClass = "$model" . "Model";
		$allModelsClasses[] = new $modelClass;
	    }
	}
    }
    return $allModelsClasses;
    
}
function xct_add_menu_items()
{

    $hook = add_menu_page(__('Mantenimientos',XCTAB_LANG), __('Mantenimientos',XCTAB_LANG), 'edit_pages', 'xctMainMenu' , 'xct_menu_inicio');
    foreach (xct_get_all_modules() as $modelClass) {
	if ($modelClass->tableHead['showinMenu'])
	{
	    $hook = add_submenu_page('xctMainMenu', $modelClass->tableHead['title'], $modelClass->tableHead['title'], 'edit_pages', 'xct' . basename(get_class($modelClass),'Model'), 'xct_render_list_page');
	    add_action("load-$hook", 'xct_add_options');
	}
    }
}

function xct_add_options()
{
    global $XCustomTables, $tableName;
    $XCustomTables = new XCustomTables($tableName);
}

add_action('admin_menu', 'xct_add_menu_items');

function xct_menu_inicio()
{
    $allModelsClasses = xct_get_all_modules();
    if (file_exists(__DIR__.'/views/render_main_menu.php'))
	include __DIR__.'/views/render_main_menu.php';
    else
	include __DIR__.'/xctcore/views/render_main_menu.php';
}

function xct_render_list_page()
{
    global $XCustomTables;
    $XCustomTables->prepare_items();
    $XCustomTables->showMsgAction();   
    $XCustomTables->addJSFile('xctcore/js/messagebox.js'); 
    $XCustomTables->addJSFile('xctcore/js/bootstrapwrapper.js'); 
    if (file_exists(__DIR__.'/views/render_list_page.php'))
	include __DIR__.'/views/render_list_page.php';
    else
	include __DIR__.'/xctcore/views/render_list_page.php';
}
function xct_render_edit_page()
{
    global $XCustomTables;
}

function xct_render_add_page()
{
    global $XCustomTables;
}

