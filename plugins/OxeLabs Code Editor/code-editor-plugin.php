<?php
/*
Plugin Name: OxeLabs Code Editor
Plugin URI: http://www.jjdas.com/
Description: This plugin helps to easily add codes in your wordpress blog. You will see a special code button in the editor. Clicking the button will open a box inside the editor, where yuo can type in your code.
Version: 1.1
Author: Jay J. Das
Author URI: http://www.jjdas.com/
*/

add_filter('tiny_mce_before_init', 'oxe_code_button_javascript');
function oxe_code_button_javascript($in){
	
	$in['plugins'] .= ",codesample";
	$in['toolbar1'] .= ",codesample";
	return $in;
}

add_filter( 'mce_external_plugins', 'my_custom_plugins' );
function my_custom_plugins( $plugins ) {
     $plugins['codesample'] = plugins_url( 'tinymce/', __FILE__ ) . 'codesample/plugin.min.js';
     return $plugins;
}

//Add any Javascript and Stylesheet
add_action('admin_enqueue_scripts', 'oxe_code_editor_assets');
add_action('wp_enqueue_scripts', 'oxe_code_editor_assets');
function oxe_code_editor_assets(){
	wp_enqueue_style('prism',plugins_url( 'tinymce/', __FILE__ ) . 'codesample/css/prism.css');
	wp_enqueue_script('prism_js',plugins_url( 'tinymce/', __FILE__ ).'codesample/prism.js', array('jquery'), '1.0', true);
}

?>