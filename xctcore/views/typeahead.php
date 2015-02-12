<?php
$innerJS = '
    $("#the-list").find(".column-[[COLUMN]]").each(function(){
	var pk = $(this).parent().find("input").first().val();
	$(this).parent().addClass("editable-click");
	$(this).wrapInner(\'<a id="column-[[COLUMN]]-'.$rand.'" class="column-[[COLUMN]]-editable editable editable-click" data-type="typeahead" data-value="" data-pk="\'+pk+\'" data-title="[[TITLE]]" href="#" style="display: inline;"></a>\');
	$(".column-[[COLUMN]]-editable").editable({
		showbuttons: true, 		
	    	mode: "inline",
		name: "[[COLUMN]]",
		emptytext: "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
		inputclass: "input-select-custom",
		url : "'.admin_url().'/admin.php?page=xct'.$this->tableName.'&action=update",
		source: "'.admin_url().'/admin.php?page=xct'.$this->tableName.'&action=getlist&field=[[COLUMN]]",
		typeahead : {
			hint: true,
			highlight: true,
			minLength: 3,
			items: 8,
			remote: "'.admin_url().'/admin.php?page=xct'.$this->tableName.'&action=getlist&field=[[COLUMN]]"
		}
	});	
    });


	
'; 
