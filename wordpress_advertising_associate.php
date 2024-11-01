<?php
/*
  Plugin Name: WordPress Advertising Associate
  Plugin URI: http://mdbitz.com/wpaa/
  Description: Quickly and easily monetize your website through the integration of Amazon products and widgets targeted by visitors' geo-location.
  Author: MDBitz - Matthew John Denton
  Version: 0.9.0
  Requires at least: 3.2.1
  Author URI: http://mdbitz.com
  License: GPL v3
*/

/*
 * copyright (c) 2010-2013 Matthew John Denton - mdbitz.com
 *
 * This file is part of WordPress Advertising Associate Plugin.
 *
 * WordPress Advertising Associate is free software: you can redistribute it
 * and/or modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * WordPress Advertising Associate is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with WordPress Advertising Associate.
 * If not, see <http://www.gnu.org/licenses/>.
*/

/**
 * WordPress Advertising Associate Plugin
 *
 * This file configures and initializes the
 * WordPress Advertising Associate Plugin
 *
 * @author Matthew John Denton <matt@mdbitz.com>
 * @package com.mdbitz.wordpress.wpaa
 */

// Plugin Version / Update Date
global $wpaa_version;
global $wpaa_update_date;
$wpaa_version = "0.9.0";
$wpaa_update_date = "10-11-2013";


/**
 * Verify domain is in compliance with Amazon's Associates Program Operating Agreement
 */
function WPAA_on_activation() {

    // Validate function is being called properly
    if ( ! current_user_can( 'activate_plugins' ) ){
        return;
    }
    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
    check_admin_referer( "activate-plugin_{$plugin}" );

    // Amazon Trademarks and variants
    $_blackList = array(
            'amazon', 'amaz0n',
            'azon', 'az0n', 'amzn',
            'kindle',
            'imdb'
    );

    $urlparts = parse_url(site_url()); // Get WordPress address
    $domain = strtolower($urlparts[host]); // Get domain section of address (wordpress could be in sub directory)
    foreach($_blackList as $token) {
        if (stripos($domain,$token) !== false) {
            wp_die( __('<h1>Sorry, WPAA can not be installed on this domain.</h1><p>It has been determined that your domain (<em>' . $domain . '</em>) is in conflict with <strong>Section 2 item G</strong> of the <a href="https://affiliate-program.amazon.com/gp/associates/agreement" target="_blank">Associates Program Operating Agreement</a> by containing the Amazon Trademark or variant <strong><u>' . $token . '</u></strong>! Please review the operating agreement and modify your domain to enable use of the WPAA plugin.<p><p>A non-exhaustive list of Amazon Trademarks that cannot be included in domain names can be found <a href="http://www.amazon.com/gp/help/customer/display.html/?nodeId=200738910" target="_blank">here</a>.</p><p>Thank You ~Matthew J. Denton</p>') );
        }
    }

}

register_activation_hook( __FILE__, 'WPAA_on_activation' );

// load Admin Class
require_once plugin_dir_path(__FILE__) . 'WPAA.php';
spl_autoload_register(array('WPAA', 'autoload'));
$wpaa = new WPAA( $wpaa_version, $wpaa_update_date );