<?php
	/*
	Plugin Name: Like-Likest
	Author: Murat Baki Yücel
	Version: 1.0
	Description: Siteye özel beğeni eklentisi
	Licence: GNU
	*/
	add_action("admin_menu","addmenu");
	function addmenu(){
		add_menu_page("Eklenti","Like-Likest","manage_options","Like-Likest/menu.php","","",81);
		add_submenu_page("Like-Likest/menu.php","Ayarlar","Ayarlar","manage_options","Like-Likest/settings.php");
	}

	add_action("admin_init","likest_options");
	function likest_options(){
		register_setting("likerLikest","ll-size");
	}

	add_filter('the_content', 'add_like_button');
	function add_like_button($content){
		$tags=get_the_tags();
		global $current_user;
		if (is_user_logged_in()){
			if(is_single() && !is_null($tags)){
				$contentID =get_the_ID();
				$UserId = $current_user->ID;
				$liked = get_user_meta($current_user->ID,'is_liked-$contentID',true);
				if($liked){
					$content.="<div id='text-like-$contentID'>Beğenildi</div>";
				}else{
					$content.="<div id='text-like-$contentID'>Beğenilmedi</div>";
				}
				$content.="<input type='button' class='button button-primary' value='Naber' id='like-$contentID' onclick='deneme(this)'></input>";
			}
			//update_user_meta($current_user->ID,'is_liked-$contentID',!$oldNotes);
		}
		return $content;
	}
	function is_user_logged_in() {
    	$user = wp_get_current_user();
 
    	return $user->exists();
	}

	add_action( 'init', 'script_enqueuer' );
	function script_enqueuer() {
		wp_register_script( "like_script", plugins_url('js/functions.js',__FILE__), array('jquery') );
   		wp_localize_script( 'like_script', 'ajaxElement', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));        

   		wp_enqueue_script( 'jquery' );
   		wp_enqueue_script( 'like_script' );
	}

	add_action("wp_ajax_like_button_clicked", "like_button_clicked");
	add_action("wp_ajax_nopriv_like_button_clicked", "must_login");

	function like_button_clicked(){
		$state = $_POST['state']=='Beğenildi' ? 1:0;
		$result['state'] = !$state;
		echo json_encode($result);
		die();
	}
	function must_login(){
		echo "Oturum Açın";
		die();
	}

?>
