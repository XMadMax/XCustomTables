    <div class="wrap">
	<h2><?php echo __('Mantenimientos',XCTAB_LANG);?></h2>

	<div id="poststuff">
		<div class="postbox">
			<h3 class="hndle"><?php echo __('Ayuda',XCTAB_LANG);?></h3>
			<div class="inside">
			    <?php echo __('HelpMainMenu',XCTAB_LANG);?>
			</div>
		</div>
	</div>
	<div id="poststuff">
		<div class="postbox">
			<h3 class="hndle"><?php echo __('Opciones',XCTAB_LANG);?></h3>
			<div class="inside">
			    <ul>
				<?php foreach ($allModelsClasses as $modelClass) : ?>
				    <?php if ($modelClass->tableHead['showinMenu']) : ?>
				    <li><h3><a href="<?php echo admin_url()?>/admin.php?page=xct<?php echo basename(get_class($modelClass),'Model');?>"><?php echo $modelClass->tableHead['title']?></a></h3></li>
				    <?php endif; ?>
				<?php endforeach; ?>
			    </ul>
			</div>
		</div>
	</div>
    </div>
