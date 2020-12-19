<?php
/**
 * Plugin Name: Check for Taiwan
 * Description: Warn user if they have types taiwan into the content
 * Author: Adam T Taylor
 * Author URI: http://adamttaylor.com
 * License: GPL21
 * Version: 1
 */


/*
1. check for Taiwain
2. If Taiwan is in content without the phrase "Taiwan, province of China"

*/
function taiwan_scripts(){
  wp_enqueue_script( 'check_tjs', plugin_dir_url(__FILE__) . 'check_taiwan.js' ,array('jquery'));
  wp_localize_script( 'check_tjs', 'check_tjs', array(
    'checked'   => get_field('__tai_checked', $post->ID)!==null
  ) );
}
add_action( 'admin_enqueue_scripts', 'taiwan_scripts' );

//Add Nonce for Later Saving
function taiwan_nonce() {
  wp_nonce_field( 'taiwan_catch_action', 'taiwan_catch_nonce' );
}
add_action( 'add_meta_boxes', 'taiwan_nonce' );

function taiwan_save_meta_box_data( $post_id ) {
  // Check if our nonce is set.
  if ( ! isset( $_POST['taiwan_catch_nonce'] ) ) {
    return;
  }

  // Verify that the nonce is valid.
  if ( ! wp_verify_nonce( $_POST['taiwan_catch_nonce'], 'taiwan_catch_action' ) ) {
    return;
  }

  // If this is an autosave, our form has not been submitted, so we don't want to do anything.
  if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
    return;
  }

  //See if the check box exists in post
  if ( ! isset( $_POST['tai_checked'] ) ) {
    return;
  }
  //$current_user = wp_get_current_user();
  $current_user = get_user_by('id',$_POST['user_ID']);
  $message = 
  '----------BEWARE----------'.PHP_EOL.
  'Taiwan entered into a field in '.get_bloginfo('url').PHP_EOL.
  'BY, '.$current_user->user_email.PHP_EOL.
  'Here: '.$_POST['_wp_original_http_referer'];

  // Update the meta field in the database.
  update_post_meta( $post_id, '__tai_checked', $_POST['tai_checked'] ? true :  false );
  wp_mail('webteam@gsma.com','Taiwan Check '.time(),$message);
}

add_action( 'save_post', 'taiwan_save_meta_box_data' );