<?php
$innerJS = '
    $("#the-list").find(".column-[[COLUMN]]").each(function(){
	var pk = $(this).parent().find("input").first().val();
	var val = $(this).html();
	moment.locale("'.XCTAB_LANG_LANG.'");
	$(this).wrapInner(\'<a class="column-[[COLUMN]]-editable" data-value="\'+val+\'" data-type="combodate" data-pk="\'+pk+\'" id="column-[[COLUMN]]-'.$rand.'" href="#"></a>\');
	$(this).editable({
		url : "'.admin_url().'/admin.php?page=xct'.$this->tableName.'&action=update",
		title : "'.__("Seleccionar fecha",XCTAB_LANG).' : [[TITLE]]",
		pk: pk,
		format: "YYYY-MM-DD",
		viewFormat: "YYY-MM-DD",
		template: "DD  /  MMMM  /  YYYY",
		name: "[[COLUMN]]",
		mode: "inline",
		placement: "right",
		emptytext: "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;",
		type: "combodate",
		placement: "bottom",
		inputclass: "select-combodate-custom",
		combodate: {
			firstItem: "name"
		},
		success: function(response, newValue) {
			if(!response.success) 
			{
				//alert(response.msg);
			}
		}
	});
    });';
