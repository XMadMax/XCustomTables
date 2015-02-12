<?php
$innerJS = '
    $("#the-list").find(".column-[[COLUMN]]").each(function(){
	var pk = $(this).parent().find("input").first().val();
	$(this).wrapInner(\'<a class="column-[[COLUMN]]-editable" data-pk="\'+pk+\'" data-url="'.admin_url().'/admin.php?page='.$this->tableName.'&action=update" data-original-title="[[TITLE]]" data-placeholder="[[TITLE]]" data-pk="1" data-type="[[DATATYPE]]" id="column-[[COLUMN]]-'.$rand.'" href="#"></a>\');
	$(this).editable({
		url : "'.admin_url().'/admin.php?page=xct'.$this->tableName.'&action=update",
		title : "Modificar [[TITLE]]",
		pk: pk,
		name: "[[COLUMN]]",
		emptytext: "",
		mode: "inline",
		rows: 5,
		type: "[[DATATYPE]]",
		placement: "bottom",
		showbuttons: "right",
		inputclass: "input-large-custom",
		tpl: "<textarea maxlength=\"[[MAXLENGTH]]\" rows=\"5\"></textarea>",
		success: function(response, newValue) {
			if(!response.success) 
				alert(response.msg);
		}
	});
    });';
