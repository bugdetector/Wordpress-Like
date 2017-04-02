<?php
	error_reporting(E_ALL);
	/*
	Plugin Name: Like-Likest
	Author: Murat Baki Yücel
	Version: 1.0
	Description: Etiketli yazıların altında beliren beğeni düğmesi ve sıralı olarak gösteren bileşen
	Licence: GNU
	*/
	class Like_Likest_TagOrderedList extends WP_Widget
	{
		
		function __construct()
		{
			$params = array(
				'description' => "Like-Likest eklentisinin sağladığı beğeni listesini sıralı olarak gösterin.",
				"name" => "Sıralı Liste" );
			parent::__construct("Like_Likest_TagOrderedList","",$params);
		}
		public function form($instance){
			extract($instance);
			?>
			<p>
				<label for="<?php echo $this->get_field_id('LL_Header'); ?>">Başlık: </label>
				<input type="text" class="widefat" id="<?php echo $this->get_field_id('LL_Header'); ?>" 
				name="<?php echo $this->get_field_name('LL_Header'); ?>"
				value= "<?php if(isset($LL_Header)) echo esc_attr($LL_Header); else echo "En Çok Beğenilen Etiketler" ?>">
			</p>
			<p>
				<label for="<?php echo $this->get_field_id('LL_List_Size'); ?>">Gösterilen Etiket Sayısı: </label>
				<input type="number" class="widefat" id="<?php echo $this->get_field_id('LL_List_Size'); ?>" 
				name="<?php echo $this->get_field_name('LL_List_Size'); ?>"
				value= "<?php if(isset($LL_List_Size)) echo esc_attr($LL_List_Size); else echo 10; ?>">
			</p>
			<?php
		}
		public function widget($args,$instance){
			extract($args);
			extract($instance);
			?>
				<section class="widget">
					<h2 class="widget-title"> <?php
					 $text = $LL_Header!="" ? $LL_Header : $widget_name;
					 echo $text; ?> </h2>
					<?php 
						global $wpdb;
						$size = isset($LL_List_Size) ? $LL_List_Size : 10;

						$results = $wpdb->get_results(
							"SELECT Term_ID
							FROM {$wpdb->prefix}LL_Liked_Tags 
							ORDER BY Like_Count DESC 
							LIMIT $size");
						foreach ($results as $result) {
							$term= get_term($result->Term_ID);
							echo "<li><a href='".get_term_link($term)."''>".$term->name."</a></li>";
						}
					 ?>
				</section>
			<?php
		}
	}
	add_action("widgets_init","Like_Likest_register_widget");
	function Like_Likest_register_widget(){
		register_widget("Like_Likest_TagOrderedList");
	}

	register_activation_hook( __FILE__, 'activate_Like_Likest' );
	function activate_Like_Likest(){
		global $wpdb;

		$charset_collate = $wpdb->get_charset_collate();
		$LL_Likers_sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}LL_Likers (
		    		User_Post_ID varchar(20) NOT NULL,
					Is_Liked int,
					PRIMARY KEY (User_Post_ID)
				) $charset_collate";

		$LL_Liked_Tags_sql = "CREATE TABLE IF NOT EXISTS {$wpdb->prefix}LL_Liked_Tags (
							  Term_ID int NOT NULL,
							  Like_Count int,
							  PRIMARY KEY (Term_ID)
					) $charset_collate";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
		dbDelta( $LL_Likers_sql );
		dbDelta( $LL_Liked_Tags_sql );

	}
	register_deactivation_hook( __FILE__, 'deactivate_Like_Likest' );
	function deactivate_Like_Likest(){
		global $wpdb;
		delete_option("like-likest-text-normal");
		delete_option("like-likest-text-like");
	}

	register_uninstall_hook( __FILE__, 'uninstall_Like_Likest' );
	function uninstall_Like_Likest(){
		if (!defined('WP_UNINSTALL_PLUGIN')) {
 		   die;
		}
		global $wpdb;
		$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}LL_Likers");
		$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}LL_Liked_Tags");
	}

	add_action("admin_menu","LL_addmenu");
	function LL_addmenu(){
		add_menu_page("Like-Likest","Like-Likest","manage_options","Like-Likest/menu.php","","",81);
	}

	add_action("admin_init","LL_options");
	function LL_options(){
		register_setting("like-likest","like-likest-text-normal");
		register_setting("like-likest","like-likest-text-like");
	}

	add_filter('the_content', 'LL_add_like_button');
	function LL_add_like_button($content){
		$tags=get_the_tags();
		global $current_user;
		if (is_user_logged_in()){
			if(is_single() && !empty($tags)){
				$contentID =get_the_ID();
				$UserId = $current_user->ID;
				$User_Post_ID = $UserId."-".$contentID;
				global $wpdb;
				$liked = $wpdb->get_row(
					"SELECT *
					FROM {$wpdb->prefix}LL_Likers 
					WHERE User_Post_ID = '$User_Post_ID'");
				if(isset($liked) && $liked->Is_Liked==1){
					$option = get_option("like-likest-text-like");
					$text = $option=="" ? "Beğenildi":$option;
				}else{
					$option = get_option("like-likest-text-normal");
					$text = $option=="" ? "Beğen":$option;
				}
				global $wpdb;
				$content.="<input type='button' class='button-primary' value='$text' id='like-$contentID' onclick='like(this)'></input>";
			}
			//update_user_meta($current_user->ID,'is_liked-$contentID',!$oldNotes);
		}
		return $content;
	}

	add_action( 'init', 'script_enqueuer' );
	function script_enqueuer() {
		wp_register_script( "like_script", plugins_url('js/LL_ajax.js',__FILE__), array('jquery') );
   		wp_localize_script( 'like_script', 'ajaxElement', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        

   		wp_enqueue_script( 'jquery' );
   		wp_enqueue_script( 'like_script' );
	}

	add_action("wp_ajax_like_button_clicked", "like_button_clicked");
	add_action("wp_ajax_nopriv_like_button_clicked", "must_login");

	function like_button_clicked(){
		global $current_user;
		global $wpdb;
		$UserId = $current_user->ID;
		$like_text = get_option("like-likest-text-like");
		$like_text = $like_text=="" ? "Beğenildi":$like_text;
		$normal_text = get_option("like-likest-text-normal");
		$normal_text = $normal_text==""? "Beğen" : $normal_text;

		$oldstate = $_POST['state'] == $like_text ? 1:0;
		$PostId = $_POST['id'];

		$terms = wp_get_post_terms($contentID);

		$User_Post_ID = $UserId."-".$PostId;
		$terms = wp_get_post_terms($PostId);
		if(!empty($terms)){	
			$state = $oldstate==0 ? 1:0;
			$action = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}LL_Likers WHERE User_Post_ID = '$User_Post_ID'");
			if(is_null($action->Is_Liked)){
				$wpdb->query(
					"INSERT INTO {$wpdb->prefix}LL_Likers (User_Post_ID,Is_Liked) 
					VALUES ('$User_Post_ID',$state) "
				);
			}else{
				$wpdb->query(
					"UPDATE {$wpdb->prefix}LL_Likers 
					SET Is_Liked = $state 
					WHERE User_Post_ID = '$User_Post_ID'");
			}
			foreach ($terms as $term) {
				$action = $wpdb->get_row(
					"SELECT * 
					FROM {$wpdb->prefix}LL_Liked_Tags 
					WHERE Term_ID = $term->term_id");
				if(is_null($action)){
					$wpdb->query(
						"INSERT INTO {$wpdb->prefix}LL_Liked_Tags (Term_ID,Like_Count) 
						VALUES ($term->term_id,1) ");
				}else{
					$newLikeCount = $action->Like_Count + $state - $oldstate; 
					$wpdb->query(
					"UPDATE {$wpdb->prefix}LL_Liked_Tags 
					SET Like_Count = $newLikeCount 
					WHERE Term_ID = {$term->term_id}");
				}
			}

		}
		$result["text"] = $state == 1 ? $like_text:$normal_text;
		echo json_encode($result);
		die();
	}
	function must_login(){
		echo "Oturum Açın";
		die();
	}

?>
