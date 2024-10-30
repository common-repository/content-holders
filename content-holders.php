<?php
/*
Plugin Name: Content Holders
Plugin URI: http://cliquestudios.com
Description: This plugin allows you to easily create "Content Holders" which can be populated with any text or html you want and placed throughout your website.  You can place the content in either Wordpress content areas or in your php template files simply by inserting the generated shortcode.  
Version: 1.1
Author: John Gile
Author Email: jpg5f2@gmail.com
License:

  Copyright 2012 John Gile (jpg5f2@gmail.com)

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

class JGContentHolders {

	/*--------------------------------------------*
	 * Constants
	 *--------------------------------------------*/
	const name = 'ContentHolders';
	const slug = 'content_holders';
	const tableName = 'jgch_shortcodes';
	
	/**
	 * Constructor
	 */
	function __construct() {		
		//register an activation hook for the plugin
		register_activation_hook( __FILE__, array( &$this, 'install_myplugin' ) );
		//Hook up to the init action
		add_action( 'init', array( &$this, 'init_myplugin' ) );
	}
  
    /**
     * Runs when plugin is activated.  Put installation functions here
     * @return void
     */
	function install_myplugin() {
		require_once('JGCHInstall.php');
		$installer = new JGCHInstall;
		$installer->installDatabaseTables();
	}
  
    /**
     * Runs when plugin is initialized
     * @return void
     */
	function init_myplugin() {
		// Setup localization
		load_plugin_textdomain( self::slug, false, dirname( plugin_basename( __FILE__ ) ) . '/lang' );
		
		if ( is_admin() ) {
			require_once('JGCHAdmin.php');
			$admin = new JGCHAdmin;
		}
		
		require_once('JGCHShortcodeLoader.php');
		$allCodes = JGCHShortcodeLoader::getAllShortcodes();
		
		require_once('JGCHShortcodeBase.php');
		
		if ( is_array($allCodes) ) {
			foreach($allCodes as $currentCode){
				$instance = new JGCHShortcodeBase;
				$instance->id = $currentCode['id'];
				$instance->content = $currentCode['content'];
				$instance->name = $currentCode['name'];
				$instance->type = $currentCode['type'];
	
				$instance->registerShortcode();
			}
		}
	}
	  
}
new JGContentHolders();