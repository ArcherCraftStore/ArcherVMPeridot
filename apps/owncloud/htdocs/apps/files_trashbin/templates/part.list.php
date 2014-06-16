<?php foreach($_['files'] as $file):
	$relative_deleted_date = OCP\relative_modified_date($file['timestamp']);
	// the older the file, the brighter the shade of grey; days*14
	$relative_date_color = round((time()-$file['date'])/60/60/24*14);
	if($relative_date_color>200) $relative_date_color = 200;
	$name = \OCP\Util::encodePath($file['name']);
	$directory = \OCP\Util::encodePath($file['directory']); ?>
	<tr data-filename="<?php p($file['name']);?>"
		data-type="<?php ($file['type'] === 'dir')?p('dir'):p('file')?>"
		data-mime="<?php p($file['mimetype'])?>"
		data-permissions='<?php p($file['permissions']); ?>'
		<?php if ( $_['dirlisting'] ): ?>
		id="<?php p($file['directory'].'/'.$file['name']);?>"
		data-file="<?php p($name);?>"
		data-timestamp=''
		data-dirlisting=1
		<?php  else: ?>
		id="<?php p($file['name'].'.d'.$file['timestamp']);?>"
		data-file="<?php p($file['name'].'.d'.$file['timestamp']);?>"
		data-timestamp='<?php p($file['timestamp']);?>'
		data-dirlisting=0
		<?php endif; ?>>
		<?php if($file['isPreviewAvailable']): ?>
		<td class="filename svg preview-icon"
		<?php else: ?>
		<td class="filename svg"
		<?php endif; ?>
		<?php if($file['type'] === 'dir'): ?>
			style="background-image:url(<?php print_unescaped(OCP\mimetype_icon('dir')); ?>)"
		<?php else: ?>
				<?php if($file['isPreviewAvailable']): ?>
				style="background-image:url(<?php print_unescaped(OCA\Files_Trashbin\Trashbin::preview_icon(!$_['dirlisting'] ? ($file['name'].'.d'.$file['timestamp']) : ($file['directory'].'/'.$file['name']))); ?>)"
				<?php else: ?>
				style="background-image:url(<?php print_unescaped(OCP\mimetype_icon($file['mimetype'])); ?>)"
				<?php endif; ?>
		<?php endif; ?>
			>
		<?php if(!isset($_['readonly']) || !$_['readonly']): ?>
			<input id="select-<?php p($file['id']); ?>" type="checkbox" />
			<label for="select-<?php p($file['id']); ?>"></label>
		<?php endif; ?>
		<?php if($file['type'] === 'dir'): ?>
			<?php if( $_['dirlisting'] ): ?>
				<a class="name dir" href="<?php p($_['baseURL'].'/'.$name); ?>" title="">
			<?php else: ?>
				<a class="name dir" href="<?php p($_['baseURL'].'/'.$name.'.d'.$file['timestamp']); ?>" title="">
			<?php endif; ?>
		<?php else: ?>
			<?php if( $_['dirlisting'] ): ?>
				<a class="name file" href="<?php p($_['downloadURL'].'/'.$name); ?>" title="">
			<?php else: ?>
				<a class="name file" href="<?php p($_['downloadURL'].'/'.$name.'.d'.$file['timestamp']);?>" title="">
			<?php endif; ?>
		<?php endif; ?>
			<span class="nametext">
				<?php if($file['type'] === 'dir'):?>
					<?php print_unescaped(htmlspecialchars($file['name']));?>
				<?php else:?>
					<?php print_unescaped(htmlspecialchars($file['basename']));?><span
						class='extension'><?php p($file['extension']);?></span>
				<?php endif;?>
			</span>
			<?php if($file['type'] === 'dir'):?>
				<span class="uploadtext" currentUploads="0">
				</span>
			<?php endif;?>
			</a>
		</td>
		<td class="date">
			<span class="modified"
				  title="<?php p($file['date']); ?>"
				  style="color:rgb(<?php p($relative_date_color.','
												.$relative_date_color.','
												.$relative_date_color) ?>)">
				<?php p($relative_deleted_date); ?>
			</span>
		</td>
	</tr>
<?php endforeach;
