<?php
/**
 * Plugin Name: SC SVG Uploads
 * Plugin URI:  https://simoncodrington.com.au
 * Description: Enabled SVG file uploads to the WordPress media library. In addition, it replaces the default icon used in the media library for SVG's with the 
 * correct image, visually highlighting your SVGs. Also included are a series of CSS tweaks for the admin back-end to make using your SVGs easier.
 * Version:     1.0.0
 * Author:      Simon Codrington
 * Author URI:  https://simoncodrington.com.au
 * Text Domain: sc-svg-uploads
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 */

 class sc_svg_uploads{
 	
	private static $instance = null;
	
	public function __construct(){
		add_filter('upload_mimes', array($this, 'add_file_types_to_uploads')); //adds SVG to the allowed filetypes
		add_action('admin_enqueue_scripts', array($this, 'enqueue_admin_scripts_and_styles')); //enqueue admin styles
		add_action('wp_enqueue_scripts', array($this, 'enqueue_public_scripts_and_styles')); //enqueue public styles
		add_action('wp_ajax_svg_get_attachment_url', array($this, 'get_attachment_url_media_library')); //main ajax function to get SVG url by ID
	}
	
	//add SVG to allowed file uploads
	public function add_file_types_to_uploads($file_types){
			
		$new_filetypes = array();
		$new_filetypes['svg'] = 'image/svg+xml';
		$file_types = array_merge($file_types, $new_filetypes );

		return $file_types;
	}

	//admin only scripts / styles
	public function enqueue_admin_scripts_and_styles(){
			
		$directory = plugin_dir_url( __FILE__ );
		
		wp_enqueue_style('sc-svg-admin-style', $directory . '/css/sc-svg-admin-styles.css');
		wp_enqueue_script('sc-svg-admin-script', $directory . 'js/sc-svg-admin-scripts.js', array('jquery'));
	}
	
	//front facing scripts / styles
	public function enqueue_public_scripts_and_styles(){
			
		$directory = plugin_dir_url( __FILE__ );
		
		wp_enqueue_style('sc-svg-public-style', $directory . '/css/sc-svg-public_styles.css');
		
	}
	
	
	//called via ajax. returns the full URL of a media attachment (SVG)
	public function get_attachment_url_media_library(){
		
		$url = '';
		$attachmentID = isset($_REQUEST['attachmentID']) ? $_REQUEST['attachmentID'] : '';
		if($attachmentID){
			$url = wp_get_attachment_url($attachmentID);
		}
		
		echo $url;
		
		die();
	}
	
	//set / return singleton
	public static function getInstance(){
		if(is_null(self::$instance)){
			self::$instance = new self();
		}
		return self::$instance;
	}
	
 }
 $sc_svg_uploads = sc_svg_uploads::getInstance();
 
 
 ?>