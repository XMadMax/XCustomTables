<?php
$innerJS = '
    $("#the-list").find(".column-[[COLUMN]]").each(function(){
	var pk = $(this).parent().find("input").first().val();
	$(this).wrapInner(\'<a class="column-[[COLUMN]]-editable" data-pk="\'+pk+\'" data-url="'.admin_url().'/admin.php?page=xct'.$this->tableName.'&action=update" data-original-title="[[TITLE]]" data-placeholder="[[TITLE]]" data-pk="1" data-type="[[DATATYPE]]" id="column-[[COLUMN]]-'.$rand.'" href="#"></a>\');
	$(this).editable({
		url : "'.admin_url().'/admin.php?page=xct'.$this->tableName.'&action=update",
		title : "Modificar [[TITLE]]",
		pk: pk,
		name: "[[COLUMN]]",
		mode: "inline",
		emptytext: "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
		rows: 1,
		type: "[[DATATYPE]]",
		placement: "bottom",
		inputclass: "input-medium-custom",
		success: function(response, newValue) {
			if(!response.success) 
				alert(response.msg);
		}
	});
    });';
