<input type='hidden' id='hasMySQL' value='<?php p($_['hasMySQL']) ?>'>
<input type='hidden' id='hasSQLite' value='<?php p($_['hasSQLite']) ?>'>
<input type='hidden' id='hasPostgreSQL' value='<?php p($_['hasPostgreSQL']) ?>'>
<input type='hidden' id='hasOracle' value='<?php p($_['hasOracle']) ?>'>
<input type='hidden' id='hasMSSQL' value='<?php p($_['hasMSSQL']) ?>'>
<form action="index.php" method="post">
<input type="hidden" name="install" value="true" />
	<?php if(count($_['errors']) > 0): ?>
	<fieldset class="warning">
		<legend><strong><?php p($l->t('Error'));?></strong></legend>
		<?php foreach($_['errors'] as $err): ?>
		<p>
			<?php if(is_array($err)):?>
				<?php print_unescaped($err['error']); ?>
				<span class='hint'><?php print_unescaped($err['hint']); ?></span>
			<?php else: ?>
				<?php print_unescaped($err); ?>
			<?php endif; ?>
		</p>
		<?php endforeach; ?>
	</fieldset>
	<?php endif; ?>
	<?php if($_['vulnerableToNullByte']): ?>
	<fieldset class="warning">
		<legend><strong><?php p($l->t('Security Warning'));?></strong></legend>
		<p><?php p($l->t('Your PHP version is vulnerable to the NULL Byte attack (CVE-2006-7243)'));?><br/>
		<?php p($l->t('Please update your PHP installation to use %s securely.', $theme->getName() )); ?></p>
	</fieldset>
	<?php endif; ?>
	<?php if(!$_['secureRNG']): ?>
	<fieldset class="warning">
		<legend><strong><?php p($l->t('Security Warning'));?></strong></legend>
		<p><?php p($l->t('No secure random number generator is available, please enable the PHP OpenSSL extension.'));?><br/>
		<?php p($l->t('Without a secure random number generator an attacker may be able to predict password reset tokens and take over your account.'));?></p>
	</fieldset>
	<?php endif; ?>
	<?php if(!$_['htaccessWorking']): ?>
	<fieldset class="warning">
		<legend><strong><?php p($l->t('Security Warning'));?></strong></legend>
		<p><?php p($l->t('Your data directory and files are probably accessible from the internet because the .htaccess file does not work.'));?><br>
		<?php print_unescaped($l->t(
			'For information how to properly configure your server, please see the <a href="%s" target="_blank">documentation</a>.',
			link_to_docs('admin-install')
		)); ?></p>
	</fieldset>
	<?php endif; ?>
	<fieldset id="adminaccount">
		<legend><?php print_unescaped($l->t( 'Create an <strong>admin account</strong>' )); ?></legend>
		<p class="infield grouptop">
			<input type="text" name="adminlogin" id="adminlogin" placeholder=""
				value="<?php p(OC_Helper::init_var('adminlogin')); ?>" autocomplete="off" autofocus required />
			<label for="adminlogin" class="infield"><?php p($l->t( 'Username' )); ?></label>
			<img class="svg" src="<?php p(image_path('', 'actions/user.svg')); ?>" alt="" />
		</p>
		<p class="infield groupbottom">
			<input type="password" name="adminpass" data-typetoggle="#show" id="adminpass" placeholder=""
				value="<?php p(OC_Helper::init_var('adminpass')); ?>" required />
			<label for="adminpass" class="infield"><?php p($l->t( 'Password' )); ?></label>
			<img class="svg" id="adminpass-icon" src="<?php print_unescaped(image_path('', 'actions/password.svg')); ?>" alt="" />
			<input type="checkbox" id="show" name="show" />
			<label for="show"></label>
		</p>
	</fieldset>

	<?php if(!$_['directoryIsSet'] OR !$_['dbIsSet'] OR count($_['errors']) > 0): ?>
	<fieldset id="advancedHeader">
		<legend><a id="showAdvanced"><?php p($l->t( 'Advanced' )); ?> <img class="svg" src="<?php print_unescaped(image_path('', 'actions/caret.svg')); ?>" /></a></legend>
	</fieldset>
	<?php endif; ?>

	<?php if(!$_['directoryIsSet'] OR count($_['errors']) > 0): ?>
	<fieldset id="datadirField">
		<div id="datadirContent">
			<label for="directory"><?php p($l->t( 'Data folder' )); ?></label>
			<input type="text" name="directory" id="directory"
				placeholder="<?php p(OC::$SERVERROOT."/data"); ?>"
				value="<?php p(OC_Helper::init_var('directory', $_['directory'])); ?>" />
		</div>
	</fieldset>
	<?php endif; ?>

	<?php if(!$_['dbIsSet'] OR count($_['errors']) > 0): ?>
	<fieldset id='databaseField'>
		<?php if($_['hasMySQL'] or $_['hasPostgreSQL'] or $_['hasOracle'] or $_['hasMSSQL'])
			$hasOtherDB = true; else $hasOtherDB =false; //other than SQLite ?>
		<legend><?php p($l->t( 'Configure the database' )); ?></legend>
		<div id="selectDbType">
		<?php if($_['hasSQLite']): ?>
		<input type='hidden' id='hasSQLite' value="true" />
		<?php if(!$hasOtherDB): ?>
		<p>SQLite <?php p($l->t( 'will be used' )); ?>.</p>
		<input type="hidden" id="dbtype" name="dbtype" value="sqlite" />
		<?php else: ?>
		<input type="radio" name="dbtype" value="sqlite" id="sqlite"
			<?php OC_Helper::init_radio('dbtype', 'sqlite', 'sqlite'); ?>/>
		<label class="sqlite" for="sqlite">SQLite</label>
		<?php endif; ?>
		<?php endif; ?>

		<?php if($_['hasMySQL']): ?>
		<input type='hidden' id='hasMySQL' value='true'/>
		<?php if(!$_['hasSQLite'] and !$_['hasPostgreSQL'] and !$_['hasOracle'] and !$_['hasMSSQL']): ?>
		<p>MySQL <?php p($l->t( 'will be used' )); ?>.</p>
		<input type="hidden" id="dbtype" name="dbtype" value="mysql" />
		<?php else: ?>
		<input type="radio" name="dbtype" value="mysql" id="mysql"
			<?php OC_Helper::init_radio('dbtype', 'mysql', 'sqlite'); ?>/>
		<label class="mysql" for="mysql">MySQL</label>
		<?php endif; ?>
		<?php endif; ?>

		<?php if($_['hasPostgreSQL']): ?>
		<?php if(!$_['hasSQLite'] and !$_['hasMySQL'] and !$_['hasOracle'] and !$_['hasMSSQL']): ?>
		<p>PostgreSQL <?php p($l->t( 'will be used' )); ?>.</p>
		<input type="hidden" id="dbtype" name="dbtype" value="pgsql" />
		<?php else: ?>
		<label class="pgsql" for="pgsql">PostgreSQL</label>
		<input type="radio" name="dbtype" value='pgsql' id="pgsql"
			<?php OC_Helper::init_radio('dbtype', 'pgsql', 'sqlite'); ?>/>
		<?php endif; ?>
		<?php endif; ?>

		<?php if($_['hasOracle']): ?>
		<?php if(!$_['hasSQLite'] and !$_['hasMySQL'] and !$_['hasPostgreSQL'] and !$_['hasMSSQL']): ?>
		<p>Oracle <?php p($l->t( 'will be used' )); ?>.</p>
		<input type="hidden" id="dbtype" name="dbtype" value="oci" />
		<?php else: ?>
		<label class="oci" for="oci">Oracle</label>
		<input type="radio" name="dbtype" value='oci' id="oci"
			<?php OC_Helper::init_radio('dbtype', 'oci', 'sqlite'); ?>/>
		<?php endif; ?>
		<?php endif; ?>

		<?php if($_['hasMSSQL']): ?>
		<input type='hidden' id='hasMSSQL' value='true'/>
		<?php if(!$_['hasSQLite'] and !$_['hasMySQL'] and !$_['hasPostgreSQL'] and !$_['hasOracle']): ?>
		<p>MS SQL <?php p($l->t( 'will be used' )); ?>.</p>
		<input type="hidden" id="dbtype" name="dbtype" value="mssql" />
		<?php else: ?>
		<label class="mssql" for="mssql">MS SQL</label>
		<input type="radio" name="dbtype" value='mssql' id="mssql" <?php OC_Helper::init_radio('dbtype', 'mssql', 'sqlite'); ?>/>
		<?php endif; ?>
		<?php endif; ?>
		</div>

		<?php if($hasOtherDB): ?>
		<div id="use_other_db">
			<p class="infield grouptop">
				<label for="dbuser" class="infield"><?php p($l->t( 'Database user' )); ?></label>
				<input type="text" name="dbuser" id="dbuser" placeholder=""
					value="<?php p(OC_Helper::init_var('dbuser')); ?>" autocomplete="off" />
			</p>
			<p class="infield groupmiddle">
				<input type="password" name="dbpass" id="dbpass" placeholder="" data-typetoggle="#dbpassword" 
					value="<?php p(OC_Helper::init_var('dbpass')); ?>" />
				<label for="dbpass" class="infield"><?php p($l->t( 'Database password' )); ?></label>
				<input type="checkbox" id="dbpassword" name="dbpassword" />
				<label for="dbpassword"></label>
			</p>
			<p class="infield groupmiddle">
				<label for="dbname" class="infield"><?php p($l->t( 'Database name' )); ?></label>
				<input type="text" name="dbname" id="dbname" placeholder=""
					value="<?php p(OC_Helper::init_var('dbname')); ?>"
					autocomplete="off" pattern="[0-9a-zA-Z$_-]+" />
			</p>
			<?php if($_['hasOracle']): ?>
			<div id="use_oracle_db">
				<p class="infield groupmiddle">
					<label for="dbtablespace" class="infield"><?php p($l->t( 'Database tablespace' )); ?></label>
					<input type="text" name="dbtablespace" id="dbtablespace" placeholder=""
						value="<?php p(OC_Helper::init_var('dbtablespace')); ?>" autocomplete="off" />
				</p>
			</div>
			<?php endif; ?>
			<p class="infield groupbottom">
				<label for="dbhost" class="infield"><?php p($l->t( 'Database host' )); ?></label>
				<input type="text" name="dbhost" id="dbhost" placeholder=""
					value="<?php p(OC_Helper::init_var('dbhost')); ?>" />
			</p>
		</div>
		<?php endif; ?>
	</fieldset>
	<?php endif; ?>

	<div class="buttons"><input type="submit" class="primary" value="<?php p($l->t( 'Finish setup' )); ?>" data-finishing="<?php p($l->t( 'Finishing …' )); ?>" /></div>
</form>
