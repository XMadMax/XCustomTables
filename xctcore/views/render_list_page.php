	<div class="wrap" style="width:80%;">
		<h2><?php echo $XCustomTables->model->tableHead['title'] ?>
	    <?php if ($XCustomTables->model->tableHead['allowAdd'] === true) : ?>
			<a class="add-new-h2" href="<?php echo admin_url()?>/admin.php?page=<?php echo $XCustomTables->pageName ?>&action=add"><?php echo $XCustomTables->model->tableHead['messageAdd'] ?></a>
	    <?php endif; ?>
		</h2>        
		<br />
		<form id="tables-filter" method="get">
		<input type="hidden" name="page" value="<?php echo $XCustomTables->pageName ?>" />
	    <?php
		$XCustomTables->search_box('Buscar', 'search_id');
		$XCustomTables->display();
	    ?>
		</form>

	</div>

    <div id="xctmodal" style="display:none; margin: 0 auto; position: absolute;background-color: #fff;" class="" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="" style='text-align: right;overflow:hidden; margin: 2px; background-color: #f1f1f1;'>
        <button type="button" class="" aria-hidden="true" onClick='jQuery("#xctmodal").slideUp();'>×</button>
	<div class="xctmodaltitlediv" style="text-align: center; overflow:hidden; margin: 0px; background-color: #e0e0e0;display:none;">
	</div>
	<div class="xctmodalimagediv" style="display:none;">
	    <img id="xctmodalimage" src="" style="margin: 4px;"/>
	</div>
	<div class="xctmodalmessagediv" style="text-align: center; overflow:hidden; margin: 10px; padding: 8px; min-height: 100px;background-color: #fff;display:none;">
      </div>
   </div>
	
<?php 	
$XCustomTables->dinamyc_edit_js();
$XCustomTables->print_js();
?>


