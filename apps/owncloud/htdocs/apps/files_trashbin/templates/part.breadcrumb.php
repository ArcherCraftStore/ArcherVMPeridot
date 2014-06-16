<div class="crumb home">
		<a href="<?php print_unescaped($_['home']); ?>">
			<img src="<?php print_unescaped(OCP\image_path('core', 'places/home.svg'));?>" class="svg" />
		</a>
</div>
<div class="crumb svg"
	 data-dir='/'>
	<a href="<?php p($_['baseURL']); ?>"><?php p($l->t("Deleted Files")); ?></a>
</div>
<?php if(count($_["breadcrumb"])):?>
<?php endif;?>
<?php for($i=0; $i<count($_["breadcrumb"]); $i++):
	$crumb = $_["breadcrumb"][$i];
	$dir = \OCP\Util::encodePath($crumb["dir"]); ?>
	<div class="crumb <?php if($i === count($_["breadcrumb"])-1) p('last');?> svg"
		 data-dir='<?php p($dir);?>'>
	<a href="<?php p($_['baseURL'].$dir); ?>"><?php p($crumb["name"]); ?></a>
	</div>
<?php endfor;
