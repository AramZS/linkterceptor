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

require_once(RSSPF_ROOT . "/lib/OpenGraph.php");

/** This is the function to check the HTML of each item for open tags and close them.
 * I've altered it specifically for some odd HTML artifacts that occur when WP sanitizes the content input.
**/
require_once(RSSPF_ROOT . "/lib/htmlchecker.php");

//A slightly altered version of the Readability library from Five Filters, who based it off readability.com's code.
//As modified for the Center for History and New Media at GMU.
require_once(RSSPF_ROOT . "/lib/fivefilters-readability/Readability.php");

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
		
		set_time_limit(0);
		
		$url = $this->de_https($url);
		$url = str_replace('&amp;','&', $url);
		
		$request = wp_remote_get( $link, array('timeout' => '30') );
		if (is_wp_error($request)) {
			$content = 'error-secured';
			//print_r($request); die();
			return $content;
		}
		if ( ! empty( $request['body'] ) ){
			$html = $request['body'];
		
			if ($link_title == ''){
				$title = getTitle($html);
			}

			$content = $this->readability_object($html);
			
			
		} else {
			$content = false;
			return $content;
		}	

		//Readability imp from work with CHNM.
				
		
		die();
	
	}
	
	function getTitle($content){
		if(strlen($str)>1){
			preg_match("/\<title\>(.*)\<\/title\>/",$str,$title);
			return $title[1];
		}
	}

	
	# Tries to turn any HTTPS URL into an HTTP URL for servers without ssl configured.
	public function de_https($url) {
		$urlParts = parse_url($url);
		if (in_array('https', $urlParts)){
			$urlParts['scheme'] = 'http';
			$url = $urlParts['scheme'] . '://'. $urlParts['host'] . $urlParts['path'] . $urlParts['query'];
		}
		return $url;
	}

	
		//Readability imp from work with CHNM.
	# The function that runs a URL through Readability and attempts to give back the plain content.
	public function readability_object($html) {
	//ref: http://www.keyvan.net/2010/08/php-readability/
		
		//check if tidy exists to clean up the input.
		if (function_exists('tidy_parse_string')) {
			$tidy = tidy_parse_string($html, array(), 'UTF8');
			$tidy->cleanRepair();
			$html = $tidy->value;
		}
		// give it to Readability
		$readability = new Readability($html, $url);

		// print debug output?
		// useful to compare against Arc90's original JS version -
		// simply click the bookmarklet with FireBug's
		// console window open
		$readability->debug = false;

		// convert links to footnotes?
		$readability->convertLinksToFootnotes = false;

		// process it
		$result = $readability->init();

		if ($result){
			$content = $readability->getContent()->innerHTML;
			//$content = $contentOut->innerHTML;
				//if we've got tidy, let's use it.
				if (function_exists('tidy_parse_string')) {
					$tidy = tidy_parse_string($content,
						array('indent'=>true, 'show-body-only'=>true),
						'UTF8');
					$tidy->cleanRepair();
					$content = $tidy->value;
				}

		} else {
			# If Readability can't get the content, send back a FALSE to loop with.
			$content = false;
		}

		return $content;

	}
		

}


global $linkterceptor;
$linkterceptor = new linkterceptor();

?>