<?php
/*
Plugin Name: Social Links for every post
Plugin URI: http://www.jjdas.com/
Description: Adds social links at the bottom of every post
Version: 1.1
Author: Jay J. Das
Author URI: http://www.jjdas.com/
*/
register_activation_hook(__FILE__, 'jjd_social_plugin_install');
function jjd_social_plugin_install(){
	global $wp_version;
	if(version_compare($wp_version, "2.7", "<")){
		deactivate_plugins(basename(__FILE__));
		wp_die(__('This plugin requires Wordpress 2.7 or higher', 'jjd-plugin'));
	}
}

add_action('init', 'jjd_init');
function jjd_init(){
	load_plugin_textdomain('jjd-social-plugin', false, plugin_basename(dirname(__FILE__).'/localization'));
}

add_action('wp_head', 'jjd_social_head_tags');
function jjd_social_head_tags(){

	if(is_single() || is_home() || is_front_page()){
		?>
		<meta property="og:title" content="<?php the_title(); ?>" />
		<meta property="og:site_name" content="<?php bloginfo('name'); ?>" />
		<meta property="og:url" content="<?php the_permalink(); ?>" />
		<meta property="og:description" content="<?php the_excerpt(); ?>" />
		<meta property="og:type" content="article" />
	<?php
		if(has_post_thumbnail()){
			$image = wp_get_attachment_image_src(get_post_thumbnail_id(), 'thumbnail');
		}
		else
		{
			$image[0] = get_option('jjd_img_uri');
		}
	?>
		<meta property="og:image" content="<?php echo $image[0]; ?>" />
	<?php
	}
}

//Add any Javascript and Stylesheet
add_action('admin_enqueue_scripts', 'jjd_social_assets');
function jjd_social_assets(){
	wp_enqueue_media();
	wp_enqueue_script('jjd_scripts',plugin_dir_url(__FILE__).'/js/jjd_social_script.js', array('jquery', 'media-upload', 'media-views'), '1.0', true);
	wp_enqueue_style('jjd_styles',plugin_dir_url(__FILE__).'/css/jjd_social_style.css');
}

//We need to put the button after the content, so we will simply add a filter
add_filter('the_content','jjd_social_buttons_function');
function jjd_social_buttons_function($content){
	if(is_single() || is_home() || is_front_page()):
		$fb_appid = get_option('jjd_fb_appid');
		
		$fb_sdk = '<div id="fb-root"></div><script>(function(d, s, id) {var js, fjs = d.getElementsByTagName(s)[0];if (d.getElementById(id)) return;js = d.createElement(s); js.id = id;js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.6&appId='.$fb_appid.'";fjs.parentNode.insertBefore(js, fjs);}(document, \'script\', \'facebook-jssdk\'));</script>';
		$fb_content = '<div class="fb-share-button" data-href="http://www.jjdas.com/" data-layout="button_count" data-mobile-iframe="true"></div>';
		
		$twitter_content = '<div class="twitter-button-wrap"><a href="https://twitter.com/share" class="twitter-share-button">Tweet</a></div>';
		$twitter_js = "<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+'://platform.twitter.com/widgets.js';fjs.parentNode.insertBefore(js,fjs);}}(document, 'script', 'twitter-wjs');</script>";
		
		$googleplus_js = '<script src="https://apis.google.com/js/platform.js" async defer></script>';
		$google_plus_content = '<div class="g-plus" data-action="share" data-height="20" data-annotation="bubble" data-align="left" data-href="<?php the_permalink(); ?>"></div>';
		
		$content = $content."<div class='jjd_social_buttons'>".$fb_sdk.$fb_content.$twitter_content.$twitter_js.$googleplus_js.$google_plus_content."</div>";
		return($content);
	endif;
}

//We will add a menu inside Admin Settings menu in Admin Dashboard
//So, we call the hook admin_menu
add_action('admin_menu','jjd_create_social_menu');
function jjd_create_social_menu(){
	add_menu_page('JJD Social Plugin Settings', 'Social Buttons', 'administrator', __FILE__,'jjd_social_settings_page');

	//We will have to regsiter the settings functions, for the options
	add_action('admin_init', 'jjd_social_register_settings');
}

function jjd_social_register_settings(){
	register_setting('jjd-settings-group', 'jjd_fb_appid');
	register_setting('jjd-settings-group', 'jjd_img_uri');
}

function jjd_social_settings_page(){
	$jjd_social_plugin_options_arr = array(
			'jjd_fb_appid' =>'',
			'jjd_img_uri' => ''
		);
	update_option('jjd_social_plugin_options', $jjd_social_plugin_options_arr);

	$jjd_social_plugin_options_arr = get_option('jjd_social_plugin_options');

	$jjd_fb_appid = $jjd_social_plugin_options_arr['jjd_fb_appid'];
	$jjd_img_uri = $jjd_social_plugin_options_arr['jjd_img_uri'];
	?>
	<div class="wrap">
		<h2><?php _e('Social Button Settings', 'jjd-social-plugin'); ?></h2>
		<p>1. You will need to obtain the Id of your existing facebook app.<br /><span class="small"><em>Only facebook requires an appID, for others you don't need to provide any ID.</em></span></p>
		<p>2. Upload a default image for your individual posts.<br /><span class="small"><em>If your post has a featured image, it will automatically get attached when your post is shared in Facebook. If your post does not have any featured image, the default image</em></span></p>
		<form method="post" action="options.php">
			<?php settings_fields('jjd-settings-group'); ?>
			<p>
				<label for="appid">Your facebook App Id.</label>
				<input type="text" size="20" maxlength ="45" name="jjd_fb_appid" value="<?php echo $jjd_fb_appid; ?>" />
			</p>
			<p id="img_container"><img src="<?php echo $jjd_img_uri; ?>" alt="" /></p>
			<p>
				<input type="text" size="40" maxlength ="45" class="jjd_default_photo" name="jjd_img_uri" value="<?php echo $jjd_img_uri; ?>" />
				<input type="button" value="Upload default image" class="jjd_upload_image_button button button-success" />
			</p>
			<p><input type="submit" class="button-primary" value="<?php _e('Save Changes', 'jjd-plugin'); ?>" /></p>
		</form>
	</div>
<?php
}
?>