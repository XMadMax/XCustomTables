<?php
$innerJS = '
var values = '.json_encode($defaultSelectValues,JSON_FORCE_OBJECT).';
$("#the-list").find(".column-[[COLUMN]]").each(function(){
	var pk = $(this).parent().find("input").first().val();
	var val = $(this).html();
	$(this).html(values[val]);
	$(this).wrapInner(\'<a class="column-[[COLUMN]]-editable" data-type="select" data-pk="\'+pk+\'" id="column-[[COLUMN]]-'.$rand.'" href="#"></a>\');
	$(this).editable({
		title : "'.__("Seleccionar fecha",XCTAB_LANG).' : [[TITLE]]",
		prepend: "'.__("Selecciona",XCTAB_LANG).'",
		url : "'.admin_url().'/admin.php?page=xct'.$this->tableName.'&action=update",
		pk: pk,
		type: "select",
		name: "[[COLUMN]]",
		value: val,
		mode: "inline",
		emptytext: "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
		source: [
		';
foreach ($defaultSelectValues as $keySelect => $valSelect) 
{
			$innerJS .= '{value: "'.$keySelect.'", text: "'.$valSelect.'"},'."\n";
}
$innerJS .= ' ]
	});
});';

