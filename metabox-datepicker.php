<?php
/*
Plugin Name: Date Picker MetaBox
Plugin URI: http://www.komprihensiv.com/adding-the-jquery-ui-datepicker-to-a-wordpress-metabox/
Description: Adds a new meta box to post edit screen containing a datepicker (based on a plugin by Kyle G at plugin url)
Author: Tim Hodson (Talis)
Version: 1.1
 */

/* This is a modified version of the original plugin at the Plugin Uri location above.
 * For use with custom fields in our events post-type 
 * Tim Hodson 2012
 *
 * Create a new metabox_datepicker in functions.php everytime we need one.
 *
 */

class metabox_datepicker {

  var $post_type ;
  var $custom_field_name;
  var $metabox_title;
  var $custom_field_label;

  function  __construct($post_type='post', $custom_field_name="_datepicker", $custom_field_label="Datepicker", $metabox_title="The Datepicker") {
        $this->post_type = $post_type ;
        $this->custom_field_name = $custom_field_name ;
        $this->custom_field_label = $custom_field_label ;
        $this->metabox_title = $metabox_title;

        add_action('add_meta_boxes', array( &$this, 'register_datepicker_metabox') );
        add_action('save_post', array(&$this, 'save_date'));
    }

    function register_datepicker_metabox() {
        add_meta_box(
            $this->metabox_title.'_datepicker_section', //section ID
            $this->metabox_title, //The metabox title
            array(&$this, 'datepicker_metabox_content'), // the metabox callback function
            $this->post_type, //the post type where it will apprear
            'side', //where on the page it will appear
            'core' //how high on the page it will appear
        );

        //add the css and js only on the pages where we need it
        global $post_type, $hook_suffix;

        if($post_type == $this->post_type){
            add_action("admin_print_scripts-{$hook_suffix}", array(&$this, 'add_datepicker_scripts'));
            add_action("admin_print_styles-{$hook_suffix}", array(&$this, 'add_datepicker_css'));
            
        }
    }

    function datepicker_metabox_content( $post ){
       
        wp_nonce_field( plugin_basename( __FILE__ ), $this->custom_field_name.'_datepicker_nonce' );
        
        echo "<label for='".$this->custom_field_name."'>" . $this->custom_field_label . "</label> ";
        echo "<input class='datepicker' type='text' name='".$this->custom_field_name."' value='" . get_post_meta($post->ID, $this->custom_field_name, TRUE) . "' />";
    }

    function save_date( $post_id ){
        // verify if this is an auto save routine.
        // If it is our form has not been submitted, so we dont want to do anything
        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
            return;

        // verify this came from the our screen and with proper authorization,
        // because save_post can be triggered at other times

        if(!isset($_POST[$this->custom_field_name.'_datepicker_nonce']))
            return;

        if ( !wp_verify_nonce( $_POST[$this->custom_field_name.'_datepicker_nonce'], plugin_basename( __FILE__ ) ) )
            return;


        // Check permissions
        if ( 'page' == $_POST['post_type'] ){
            if ( !current_user_can( 'edit_page', $post_id ) )
                return;
            }
        else {
            if ( !current_user_can( 'edit_post', $post_id ) )
                return;
        }

        //finally save the date
        update_post_meta($post_id, $this->custom_field_name, $_POST[$this->custom_field_name], get_post_meta($post_id, $this->custom_field_name, TRUE));
    }

    function add_datepicker_scripts(){
        wp_enqueue_script('datepicker', plugins_url('js/jquery-ui-1.8.20.custom.min.js', __FILE__), array('jquery'), '', TRUE);
        wp_enqueue_script('datepicker-options', plugins_url('js/call_datepicker.js', __FILE__), array('datepicker'), '', TRUE);
    }
    function add_datepicker_css(){
      wp_enqueue_style('datepickers', plugins_url('css/ui-lightness/jquery-ui-1.8.20.custom.css', __FILE__));
    }
}

//$metabox_datepicker = new metabox_datepicker();
