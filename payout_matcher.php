<?php

/*
Plugin Name: Payout-User Export
Plugin URI:
Description: This plugin is made to add "First Name", "Last Name", "Bank Acc Type", "Account No" to this affiliate payout csv file.
Author: Kz Sherwin
Author URI: 
Version: 0.1
*/

if ( ! defined( 'ABSPATH' ) ) { 
    exit; // Exit if accessed directly
}


add_action('admin_menu', 'addMenu');
function addMenu(){
    add_menu_page('Payout-User Export', 'Payout-User Export', 4, 'payout-user-export', 'payout_user_export');
}

add_action('admin_post_submit-form', '_handle_form_action');
add_action('admin_post_nopriv_submit-form', '_handle_form_action');

function _handle_form_action(){
    include 'includes/comb_expo.php';
}

function payout_user_export() {
    include '../wp-content/plugins/payout_matcher/includes/comb_expo_form.php';
}
?>
