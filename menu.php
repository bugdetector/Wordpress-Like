<!DOCTYPE html>
<html>
<head>
	<title>Ayarlar</title>
</head>
<body>
	<h1>Ayarlar</h1>
	<form method="post" action="options.php">
	<?php settings_fields("like-likest"); do_settings_sections("like-likest");?>
	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="blogname">Normal Durum Yazısı:</label>
			</th>
			<th>
				<input type="text" name="like-likest-text-normal" value="<?php echo get_option("like-likest-text-normal"); ?>">
			</th>
		</tr>
		<tr>
			<th scope="row">
				<label for="blogname">Beğeni Yazısı:</label>
			</th>
			<th>
				<input type="text" name="like-likest-text-like" value="<?php echo get_option("like-likest-text-like"); ?>">
			</th>
		</tr>
	</table>
		<?php submit_button(); ?>
	</form>
</body>
</html>
