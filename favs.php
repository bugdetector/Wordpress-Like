<!DOCTYPE html>
<html>
<head>
	<title>Favoriler</title>
</head>
<body>
	<h1>Favoriler</h1>
	<table class="form-table">
		<tr>
			<th scope="row">
				<label for="blogname"> <h1>Etiket Adı</h1></label>
			</th>
			<th>
				<label for="blogname"><h1>Beğenme Sayısı</h1></label>
			</th>
		</tr>
		<?php
			global $wpdb;
			$results = $wpdb->get_results(
							"SELECT *
							FROM {$wpdb->prefix}LL_Liked_Tags 
							ORDER BY Like_Count DESC ");
			foreach ($results as $result) {
			 	# code...
			  
		 ?>
		<tr>
			<th scope="row">
				<label for="blogname"><?php echo get_term($result->Term_ID)->name; ?> </label>
			</th>
			<th>
				<label for="blogname"><?php echo $result->Like_Count;?></label>
			</th>
		</tr>

		<?php
		 }
		 ?>
	</table>
</body>
</html>
