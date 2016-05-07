<?php
/*
Plugin Name: About Me with Photo & Link
Plugin URI: http://www.jjdas.com/
Description: Add an image, a description about yourself and link to your homepage.
Author: Jay J. Das
Version: 1.1
Author URI: http://www.jjdas.com
Text Domain: about-me-widget
Domain Path: /languages
License: GPL2
*/

/*  Copyright 2016  Jay J. Das  (email : jay3000bc@gmail.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

// Plugin version
if ( ! defined( 'ABOUT_ME_VERSION' ) ) {
	define( 'ABOUT_ME_VERSION', '1.1' );
}

add_action('widgets_init', create_function('', 'return register_widget("about_me_widget");'));

class about_me_widget extends WP_Widget{

    function __construct(){
        $widget_ops = array('classname' => 'about_me_widget', 'description' => 'Introduce yourself with a cool image, a description about - what you do? and a link to your homepage');

        parent::__construct(
                //Base Id of Widget
                'about_me_widget',
                //Widget name will appear in UI
                'About Me',
                $widget_ops
            );
        add_action('admin_enqueue_scripts', array($this,'author_upload_enqueue'));
    }

    function widget($args, $instance){
        /*
        The extract function, takes an associative array and returns it's keys as variables
        This enables us to use $before_title instead of <?php echo $before_title; ?>
        */
        /** @see WP_Widget::widget */
        wp_enqueue_style( 'about_me', plugin_dir_url( __FILE__).'/css/about-me.css', '', ABOUT_ME_VERSION );

        extract( $args );

        // these are our widget options
        $title = apply_filters('widget_title', $instance['title']);
        $img_uri = $instance['img_uri'];
        $textarea = $instance['textarea'];
        $linktext = $instance['linktext'];

        echo $before_widget;

        // if the title is set
        if ( $title ) {
            echo $before_title . $title . $after_title;
        }

        if($img_uri)
            echo '<div class="author_photo"><img src="'.$img_uri.'" alt="Author Photo" /></div>';

        // if text is entered in the textarea
        if ( $textarea ) {
            echo '<div class="desc" class="widget-textarea">' . $textarea . '</div>';
        }

        //the link button
        if($linktext)
        {
            //echo '<input type="button" class="button button-primary" value="'. $linktext .'" />';
            echo '<div class="about-me-link"><a href="'.$linktext.'">About Me</a></div>';
        }

        echo $after_widget;
    }

    function update($new_instance, $old_instance){
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['img_uri'] = strip_tags($new_instance['img_uri']);
        $instance['textarea'] = strip_tags($new_instance['textarea']);
        $instance['linktext'] = strip_tags($new_instance['linktext']);

        return $instance;
    }

    function author_upload_enqueue(){
        wp_enqueue_media();
        wp_enqueue_script( 'media_upload_jquery', plugin_dir_url(__FILE__).'upload-media.js', array( 'jquery', 'media-upload', 'media-views' ), ABOUT_ME_VERSION, true );
    }

    function form($instance){
        wp_enqueue_style( 'about_me', plugin_dir_url( __FILE__).'/css/about-me.css', '', ABOUT_ME_VERSION );
        /*
            We should always sanitize all user input data
        */

        $title = isset($instance['title']) ? esc_attr($instance['title']) : '';
        $img_uri = isset($instance['img_uri']) ? esc_attr($instance['img_uri']) : '';
        $textarea = isset($instance['textarea']) ? esc_attr($instance['textarea']) : '';
        $linktext = isset($instance['linktext']) ? esc_attr($instance['linktext']) : '';
        ?>

         <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Widget Title (Optional)'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo esc_attr($title); ?>" />
        </p>

        <p>
            <input type="hidden" class="author_photo" name="<?php echo $this->get_field_name('img_uri'); ?>" id="<?php echo $this->get_field_id('img_uri'); ?>" value="<?php echo esc_attr($img_uri); ?>" />
            
            <div id="img_container"><img src="<?php echo esc_attr($img_uri); ?>" alt="" /></div>
            
            <div id="upload_btn"><input type="button" value="Upload Image" class="upload_image_button button button-success" /></div>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('textarea'); ?>"><?php _e('Write about yourself:'); ?></label>
            <textarea class="widefat" id="<?php echo $this->get_field_id('textarea'); ?>" name="<?php echo $this->get_field_name('textarea'); ?>"><?php echo esc_attr($textarea); ?></textarea>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('linktext'); ?>"><?php _e('URL of Homepage:'); ?></label>
            <input type="text" class="widefat" id="<?php echo $this->get_field_id('linktext'); ?>" name="<?php echo $this->get_field_name('linktext'); ?>" value="<?php echo esc_attr($linktext); ?>" />
        </p>
        
        <?php
    }
}

?>