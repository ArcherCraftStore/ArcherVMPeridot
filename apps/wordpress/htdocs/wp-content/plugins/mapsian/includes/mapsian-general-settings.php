<?php
/*
* description : Mapsian General Settings page.
*/

function m_setting(){

	if (sanitize_text_field( $_POST['api_key'] ) == ""){
		if( sanitize_text_field($_GET['action']) == "update"){
		delete_option("mapsian_maps_api_key");
		}
	}
	else {
		if( sanitize_text_field( $_POST['api_key'] ) ){
		update_option("mapsian_maps_api_key", sanitize_text_field($_POST['api_key']) );
		}
	}

	if ( sanitize_text_field($_POST['language']) == "default"){
		if( sanitize_text_field($_GET['action']) == "update"){
			delete_option("mapsian_maps_language");
		}
	}
	else {
		if( sanitize_text_field($_POST['language']) ){
		update_option("mapsian_maps_language", sanitize_text_field($_POST['language']) );
		}
	}

?>

<h2><?php echo _e('Mapsian General settings', 'mapsian');?></h2>
<form method="post" action="admin.php?page=m_setting&action=update">

<div style="margin-top:30px">
	<ul>
		<li><h3><?php echo _e('Google maps API Key', 'mapsian');?></h3></li>
		<li><input type="text" name="api_key" value="<?php echo get_option('mapsian_maps_api_key');?>" style="width:60%"></li>
		<li style="font-style:italic; color:gray"><?php echo _e('If you have Google maps API key, insert this field.', 'mapsian');?> <a href="https://developers.google.com/maps/documentation/javascript/tutorial?hl=en" target="_blank"><?php echo _e('What is Google maps API key?', 'mapsian');?></a><br>
			<?php echo _e("All maps will not appear If it's wrong or not correct, so please insert key correctly.", "mapsian");?></li>
		<li><input type="submit" class="button button-primary" value="Add key"></li>
	</ul>
</div>

<div style="margin-top:30px">
	<ul>
		<li><h3><?php echo _e('Google maps Language setting', 'mapsian');?></h3></li>
		<li>
			<select name="language">
				<option value="default">Default</option>
				<option value="ar">ARABIC</option>
				<option value="eu">BASQUE</option>
				<option value="bg">BULGARIAN</option>
				<option value="bn">BENGALI</option>
				<option value="ca">CATALAN</option>
				<option value="cs">CZECH</option>
				<option value="da">DANISH</option>
				<option value="de">GERMAN</option>
				<option value="el">GREEK</option>
				<option value="en">ENGLISH</option>
				<option value="en-AU">ENGLISH (AUSTRALIAN)</option>
				<option value="en-GB">ENGLISH (GREAT BRITAIN)</option>
				<option value="es">SPANISH</option>
				<option value="eu">BASQUE</option>
				<option value="fa">FARSI</option>
				<option value="fi">FINNISH</option>
				<option value="fil">FILIPINO</option>
				<option value="fr">FRENCH</option>
				<option value="gl">GALICIAN</option>
				<option value="gu">GUJARATI</option>
				<option value="hi">HINDI</option>
				<option value="hr">CROATIAN</option>
				<option value="hu">HUNGARIAN</option>
				<option value="id">INDONESIAN</option>
				<option value="it">ITALIAN</option>
				<option value="iw">HEBREW</option>
				<option value="ja">JAPANESE</option>
				<option value="kn">KANNADA</option>
				<option value="ko">KOREAN</option>
				<option value="lt">LITHUANIAN</option>
				<option value="lv">LATVIAN</option>
				<option value="ml">MALAYALAM</option>
				<option value="mr">MARATHI</option>
				<option value="nl">DUTCH</option>
<!--			<option value="nn">NORWEGIAN NYNORSK</option>-->
				<option value="no">NORWEGIAN</option>
<!--			<option value="or">ORIYA</option>-->
				<option value="pl">POLISH</option>
				<option value="pt">PORTUGUESE</option>
				<option value="pt-BR">PORTUGUESE (BRAZIL)</option>
				<option value="pt-PT">PORTUGUESE (PORTUGAL)</option>
				<option value="ro">ROMANIAN</option>
				<option value="ru">RUSSIAN</option>
				<option value="sk">SLOVAK</option>
				<option value="sl">SLOVENIAN</option>
				<option value="sr">SERBIAN</option>
				<option value="sv">SWEDISH</option>
				<option value="tl">TAGALOG</option>
				<option value="ta">TAMIL</option>
				<option value="te">TELUGU</option>
				<option value="th">THAI</option>
				<option value="tr">TURKISH</option>
				<option value="uk">UKRAINIAN</option>
				<option value="vi">VIETNAMESE</option>
				<opiton value="zh-CN">CHINESE (SIMPLIFIED)</option>
				<option value="zh-TW">CHINESE (TRADITIONAL)</option>
			</select>
		</li>
<script>
	var language = "<?php echo get_option('mapsian_maps_language');?>";

	if(language){
	jQuery("[name=language]").val(language);
	}

</script>
		<li style="font-style:italic; color:gray"><?php echo _e('It makes map language by you selected.', 'mapsian');?><br><?php echo _e("If you select 'Default', The map language will setting automatically.", "mapsian");?></li>
		<li><input type="submit" class="button button-primary" value="<?php echo _e('Add Language', 'mapsian');?>"></li>
	</ul>
</div>
</form>
<?php

}

?>