<!DOCTYPE html>
<html>
<head>
	<title>Ayarlar</title>
</head>
<body>
	<h1>Ayarlar</h1>
	<form method="post" action="options.php">
	<?php settings_fields("likerLikest"); do_settings_sections("likerLikest");?>
	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="blogname">Başlık</label>
			</th>
			<th>
				<input type="number" name="size" value="<?php echo get_option("size"); ?>">
			</th>
		</tr>
	</table>
		<?php submit_button(); ?>
	</form>
</body>
</html>