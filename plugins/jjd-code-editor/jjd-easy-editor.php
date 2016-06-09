<?php
/*
Plugin Name: JJD Code Editor
Plugin URI: http://www.jjdas.com/
Description: This plugin helps to easily add codes in your wordpress blog. You will see a special code button in the editor. Clicking the button will open a box inside the editor, where yuo can type in your code.
Version: 1.1
Author: Jay J. Das
Author URI: http://www.jjdas.com/
*/
add_filter('mce_buttons', 'jjd_code_buttons');
function jjd_code_buttons($buttons){
	$buttons[] = 'jjd_code_button';
	return $buttons;
}

add_filter( 'mce_external_plugins', 'jjd_code_action' );
function jjd_code_action( $jjd_code_action_plugins ) {
     $jjd_code_action_plugins['jjd_code_button'] = plugin_dir_url(__FILE__).'/js/jjd_tmce_plugin.js';
     return $jjd_code_action_plugins;
}

//Add any Javascript and Stylesheet
add_action('admin_enqueue_scripts', 'jjd_code_editor_assets');
function jjd_code_editor_assets(){
	wp_enqueue_style('button_style',plugin_dir_url(__FILE__).'/css/jjd_code_editor_style.css');
}

?>