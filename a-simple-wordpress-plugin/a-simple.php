<?php
/*
Plugin Name: A Simple Wordpress Plugin
Plugin URI: http://www.nioya.com
Description: The plugin shows some practices for these: How to add custom boxes into post / page management pages. How to use custom postmeta. How to use js opeations juch as autocomplete at admin pages. How to call REST service. How to create option pages and save option data in the DB.
Author: Emrah Onder
Version: 1.0
Author URI: http://www.nioya.com
*/

class NY_simple_assessment {
	public function __construct() {
		$this->init();
	}
	
	public function init() {
		// Previous to initializing
		do_action( 'nyda_pre_init' );

		// Initialize
		$this->constants();
		$this->includes();
		$this->start();

		// Finished initializing
		do_action( 'nyda_init' );
	}
	// manages includes
	public function includes() {
		//contains ajax operations
		require_once('ajax_operations.php');
		//contains metabox operations
		require_once('post_meta_operations.php');
		//contains option page operations
		require_once('option_operations.php');
	}
	// manages constants - magic numbers or inline strings are not allowed :)
	public function constants() {
		// Set the core file path
		define( 'NYDA_FILE_PATH', dirname( __FILE__ ) );

		// Define the path to the plugin folder
		define( 'NYDA_DIR_NAME',  basename( NYDA_FILE_PATH ) );
		define( 'NYDA_PLUGIN_BASENAME', plugin_basename( __FILE__ ) );

		// Define the URL to the plugin folders
		define( 'NYDA_FOLDER',    dirname( NYDA_PLUGIN_BASENAME ) );
		define( 'NYDA_URL',       plugins_url( '', __FILE__ ) );
		define( 'NYDA_PLUGIN_IMAGES', NYDA_URL.'/assets/images' );
		define( 'NYDA_PLUGIN_CSS', NYDA_URL.'/assets/css' );
		define( 'NYDA_PLUGIN_JS', NYDA_URL.'/assets/js' );
		
		// Define option page constants
		define( 'NYDA_MENU_NAME_OPTIONS',  __( 'Simple Plugin Options', 'NYDA' ) );
		define( 'NYDA_MENU_SLUG_OPTIONS', 'simple-plugin-options' );
		define( 'NYDA_OPTION_SHOW_FOR_PAGES', 'NYDA_show_for_pages' );
		
		define( 'NYDA_OPTION_SHOW_FOR_POSTS', 'NYDA_show_for_posts' );
		define( 'NYDA_OPTION_CHAR_COUNT', 'NYDA_char_count' );
		
		// Define char count to start autocomplete
		define( 'NYDA_CONSTANT_CHAR_COUNT', 5 );
		
		// Define general constant texts
		define( 'NYDA_CONSTANT_METABOX_NAME',  __( 'Simple Plugin Custom Postmeta', 'NYDA' ) );
		define( 'NYDA_CONSTANT_INPUT_TEXT',  __( 'Country', 'NYDA' ) );
		define( 'NYDA_CONSTANT_INPUT_NAME',  __( 'NY_country', 'NYDA' )  );
		define( 'NYDA_CONSTANT_INPUT_DESCRIPTION',  __( 'You can easily type in the first 5 characters and a suggestion is made from a list of countries. If you cannot find your country from list, you can keep yours.', 'NYDA' )  );
		define( 'NYDA_REST_API_URI', 'https://restcountries.eu/rest/v2/name/');
		
		
	}
	// starts
	public function start() {

		// TO DO - load text domain
		
		add_action('admin_menu', array( $this, 'setup_dashboard' ));
		add_action('admin_footer-edit.php', array( $this, 'set_footer_actions' ),1 );
		add_action('admin_footer-post.php', array( $this, 'set_footer_actions' ),1 );
		add_action('admin_footer-post-new.php', array( $this, 'set_footer_actions' ),1 );

	}
	
	// adds js components
	
	function set_footer_actions($hook) {
		
		$char_count = get_option(NYDA_OPTION_CHAR_COUNT);
		if(!$char_count){
			$char_count = NYDA_CONSTANT_CHAR_COUNT;
			update_option(NYDA_OPTION_CHAR_COUNT, NYDA_CONSTANT_CHAR_COUNT);
		}
		echo '<script>
			var charCount = '.$char_count.';
		</script>';
		echo '<script src="'.NYDA_PLUGIN_JS.'/ny-admin-custom.js"></script>';
	}

	// for option pages
	public function setup_dashboard() {
		add_submenu_page('options-general.php',NYDA_MENU_NAME_OPTIONS,NYDA_MENU_NAME_OPTIONS, 'manage_options',NYDA_MENU_SLUG_OPTIONS, 'NYDA_theme_optionpage');
	}

}

$simple_plugin = new NY_simple_assessment();


?>