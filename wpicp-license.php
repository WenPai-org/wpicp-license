<?php
/**
 * Plugin Name: WPICP License
 * Plugin URI: https://wpicp.com/download
 * Description: Must-have for WordPress sites in China, showing your ICP license.
 * Author: WPICP.com
 * Author URI: https://wpicp.com/
 * Text Domain: wpicp-license
 * Domain Path: /languages
 * Version: 1.0
 * Network: True
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 *
 * WP ICP License is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.
 *
 * WP ICP License is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 */


// Add admin menu page
add_action( 'admin_menu', 'wpicp_license_menu' );

function wpicp_license_menu() {
    add_options_page(
         __( 'WP ICP License Settings', 'wpicp-license' ),
         __( 'ICP License', 'wpicp-license' ),
        'manage_options',
        'wpicp_license_settings',
        'wpicp_license_settings_page'
    );
}

/** Load translation */
$current_locale = get_locale();
if (!empty($current_locale)) {
    $mo_file = dirname(__FILE__) . '/languages/wpicp-license-' . $current_locale . ".mo";
    if (@file_exists($mo_file) && is_readable($mo_file)) {
        load_textdomain('wpicp-license', $mo_file);
    }
}

// Add settings page and field
add_action( 'admin_init', 'wpicp_license_settings' );

function wpicp_license_settings() {

    add_settings_section(
        'wpicp_license_section',
        __( 'WordPress ICP License Namber', 'wpicp-license' ),
        'wpicp_license_section_callback',
        'wpicp_license_settings'
    );

    add_settings_field(
        'wpicp_license_field',
        __( 'ICP License', 'wpicp-license' ),
        'wpicp_license_field_callback',
        'wpicp_license_settings',
        'wpicp_license_section'
    );

    register_setting( 'wpicp_license_settings', 'wpicp_license' );
}

// Settings section callback
function wpicp_license_section_callback() {
  echo '<p>' . __( 'This plugin is free forever, and its purpose is to supplement the essential functions that the Chinese version of WordPress lacks. More information at <a href="https://wpicp.com" target="_blank" rel="noopener">WPICP.com</a>', 'wpicp-license' ) . '</p>';
  echo '<h3>' . __( 'Why do you need?', 'wpicp-license' ) . '</h3>';
  echo '<p>' . __( 'The ICP license is a state-issued registration, All public websites in mainland China must have an ICP number listed on the homepage of the website. <a href="https://wpicp.com/document/what-would-happen-if-not" target="_blank" rel="noopener">(What would happen if not?)</a>', 'wpicp-license' ) . '</p>';
  echo '<h3>' . __( 'How to use?', 'wpicp-license' ) . '</h3>';
  echo '<p>' . __( '1. Enter your ICP license information below. <a href="https://wpicp.com/document/find-my-license" target="_blank" rel="noopener">(Find My License?)</a>', 'wpicp-license' ) . '</p>';
  echo '<p>' . __( '2. Use the shortcode <code>[wpicp_license]</code> to display the license information and link on your website. <a href="https://wpicp.com/document/integrate-into-theme" target="_blank" rel="noopener">(Integrate into theme?)</a>', 'wpicp-license' ) . '</p>';
}

// Settings field callback
function wpicp_license_field_callback() {
    $wpicp_license = get_option( 'wpicp_license' );
    echo '<input type="text" id="wpicp_license" name="wpicp_license" value="' . esc_attr( $wpicp_license ) . '"/>';
    echo '<p class="description" style="font-size:13px;">' . __( 'Enter your ICP license number information. <a href="https://wpicp.com/document/correct-format" target="_blank" rel="noopener">(Correct format?)</a>', 'wpicp-license' ) . '</p>';
}


// Settings page callback
function wpicp_license_settings_page() {
    ?>
    <div class="wrap">
        <h1><?php _e( 'ICP License Settings', 'wpicp-license' ); ?></h1>
        <form method="post" action="options.php">
            <?php settings_fields( 'wpicp_license_settings' ); ?>
            <?php do_settings_sections( 'wpicp_license_settings' ); ?>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}


// Add shortcode
add_shortcode( 'wpicp_license', 'wpicp_license_shortcode' );

function wpicp_license_shortcode() {
    $wpicp_license = get_option( 'wpicp_license' );
    if ( $wpicp_license ) {
        $license_text = '' . $wpicp_license;
        $license_url = 'https://beian.miit.gov.cn';
        $target = '_blank';
        $nofollow = 'nofollow';
        $license_link = '<a href="' . esc_url( $license_url ) . '" target="' . esc_attr( $target ) . '" rel="' . esc_attr( $nofollow ) . '">' . $license_text . '</a>';
        return $license_link;
    }
}

?>
