<?php
/*
Plugin Name: Linkterceptor
Plugin URI: http://aramzs.me
Description: A plugin to allow users to intercept and preview links. 
Version: 0.0.01
Author: Aram Zucker-Scharff
Author URI: http://aramzs.me
License: GPL2
*/

/*
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


//Set up some constants
define( 'LTRCPR_SLUG', 'linkterceptor' );
define( 'LTRCPR_TITLE', 'Linkterceptor' );
define( 'LTRCPR_MENU_SLUG', LTRCPR_SLUG . '-menu' );
define( 'LTRCPR_ROOT', dirname(__FILE__) );
define( 'LTRCPR_FILE_PATH', LTRCPR_ROOT . '/' . basename(__FILE__) );
define( 'LTRCPR_URL', plugins_url('/', __FILE__) );

class linkterceptor {

	function __construct() {
		add_action('wp_enqueue_scripts', array( $this, 'linkterceptor_scripts') );
		add_action( 'wp_ajax_nopriv_linktercept', array( $this, 'linktercept') );
		add_action( 'wp_ajax_linktercept', array( $this, 'linktercept') );
	
	}
	
	function linkterceptor_scripts() {
		wp_enqueue_script('linkterceptor', LTRCPR_URL . 'assets/js/linkterceptor.js', array( 'jquery' ));
	}
	
	function linktercept() {
	
		$link = $_POST['url'];
		$link_title = $_POST['link_title'];
		
		die();
	
	}

}


global $linkterceptor;
$linkterceptor = new linkterceptor();

?>