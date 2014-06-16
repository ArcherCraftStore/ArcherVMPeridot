<!DOCTYPE html>
<!--[if lt IE 7]><html class="ng-csp ie ie6 lte9 lte8 lte7"><![endif]-->
<!--[if IE 7]><html class="ng-csp ie ie7 lte9 lte8 lte7"><![endif]-->
<!--[if IE 8]><html class="ng-csp ie ie8 lte9 lte8"><![endif]-->
<!--[if IE 9]><html class="ng-csp ie ie9 lte9"><![endif]-->
<!--[if gt IE 9]><html class="ng-csp ie"><![endif]-->
<!--[if !IE]><!--><html class="ng-csp"><!--<![endif]-->

	<head data-user="<?php p($_['user_uid']); ?>" data-requesttoken="<?php p($_['requesttoken']); ?>">
		<title>
			<?php
				p(!empty($_['application'])?$_['application'].' - ':'');
				p($theme->getTitle());
			?>
		</title>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="apple-itunes-app" content="app-id=543672169">
		<link rel="shortcut icon" href="<?php print_unescaped(image_path('', 'favicon.png')); ?>" />
		<link rel="apple-touch-icon-precomposed" href="<?php print_unescaped(image_path('', 'favicon-touch.png')); ?>" />
		<?php foreach($_['cssfiles'] as $cssfile): ?>
			<link rel="stylesheet" href="<?php print_unescaped($cssfile); ?>" type="text/css" media="screen" />
		<?php endforeach; ?>
		<?php foreach($_['jsfiles'] as $jsfile): ?>
			<script type="text/javascript" src="<?php print_unescaped($jsfile); ?>"></script>
		<?php endforeach; ?>
		<?php foreach($_['headers'] as $header): ?>
			<?php
				print_unescaped('<'.$header['tag'].' ');
				foreach($header['attributes'] as $name=>$value) {
					print_unescaped("$name='$value' ");
				};
				print_unescaped('/>');
			?>
		<?php endforeach; ?>
	</head>

	<body id="<?php p($_['bodyid']);?>">
	<div id="notification-container">
		<div id="notification"></div>
		<?php if ($_['updateAvailable']): ?>
			<div id="update-notification" style="display: inline;"><a href="<?php print_unescaped($_['updateLink']); ?>"><?php p($l->t('%s is available. Get more information on how to update.', array($_['updateVersion']))); ?></a></div>
		<?php endif; ?>
	</div>
	<header><div id="header">
			<a href="<?php print_unescaped(link_to('', 'index.php')); ?>" title="" id="owncloud"><img class="svg"
				src="<?php print_unescaped(image_path('', 'logo-wide.svg')); ?>" alt="<?php p($theme->getName()); ?>" /></a>
			<div id="logo-claim" style="display:none;"><?php p($theme->getLogoClaim()); ?></div>
			<ul id="settings" class="svg">
				<span id="expand" tabindex="0" role="link">
					<span id="expandDisplayName"><?php  p(trim($_['user_displayname']) != '' ? $_['user_displayname'] : $_['user_uid']) ?></span>
					<img class="svg" src="<?php print_unescaped(image_path('', 'actions/caret.svg')); ?>" />
					<?php if ($_['enableAvatars']): ?>
					<div class="avatardiv"></div>
					<?php endif; ?>
				</span>
				<div id="expanddiv">
				<?php foreach($_['settingsnavigation'] as $entry):?>
					<li>
						<a href="<?php print_unescaped($entry['href']); ?>" title=""
							<?php if( $entry["active"] ): ?> class="active"<?php endif; ?>>
							<img class="svg" alt="" src="<?php print_unescaped($entry['icon']); ?>">
							<?php p($entry['name']) ?>
						</a>
					</li>
				<?php endforeach; ?>
					<li>
						<a id="logout" <?php print_unescaped(OC_User::getLogoutAttribute()); ?>>
							<img class="svg" alt="" src="<?php print_unescaped(image_path('', 'actions/logout.svg')); ?>" />
							<?php p($l->t('Log out'));?>
						</a>
					</li>
				</div>
			</ul>

			<form class="searchbox" action="#" method="post">
				<input id="searchbox" class="svg" type="search" name="query"
					value="<?php if(isset($_POST['query'])) {p($_POST['query']);};?>"
					autocomplete="off" x-webkit-speech />
			</form>
		</div></header>

		<nav><div id="navigation">
			<ul id="apps" class="svg">
				<div class="wrapper"><!-- for sticky footer of apps management -->
				<?php foreach($_['navigation'] as $entry): ?>
					<li data-id="<?php p($entry['id']); ?>">
						<a href="<?php print_unescaped($entry['href']); ?>" title=""
							<?php if( $entry['active'] ): ?> class="active"<?php endif; ?>>
							<img class="icon svg" src="<?php print_unescaped($entry['icon']); ?>"/>
							<span>
								<?php p($entry['name']); ?>
							</span>
						</a>
					</li>
				<?php endforeach; ?>
				<?php if(OC_User::isAdminUser(OC_User::getUser())): ?>
					<div class="push"></div><!-- for for sticky footer of apps management -->
				<?php endif; ?>
				</div>

				<!-- show "More apps" link to app administration directly in app navigation, as sticky footer -->
				<?php if(OC_User::isAdminUser(OC_User::getUser())): ?>
					<li id="apps-management">
						<a href="<?php print_unescaped(OC_Helper::linkToRoute('settings_apps').'?installed'); ?>" title=""
							<?php if( $entry['active'] ): ?> class="active"<?php endif; ?>>
							<img class="icon svg" src="<?php print_unescaped(OC_Helper::imagePath('settings', 'apps.svg')); ?>"/>
							<span>
								<?php p($l->t('Apps')); ?>
							</span>
						</a>
					</li>
				<?php endif; ?>
			</ul>
		</div></nav>

		<div id="content-wrapper">
			<div id="content">
				<?php print_unescaped($_['content']); ?>
			</div>
		</div>
	</body>
</html>
