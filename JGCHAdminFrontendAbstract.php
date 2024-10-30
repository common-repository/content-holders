<?php
/*
    "Content Holders" Copyright © 2012 John Gile  (email : jpg5f2@gmail.com)

    This file is part of Content Holders Extension.

    Content Holders Extension is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    Content Holders Extension is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with Contact Form to Database Extension.
    If not, see <http://www.gnu.org/licenses/>.
*/

abstract class JGCHAdminFrontendAbstract {

	/**
	 * Registers and enqueues stylesheets for the administration panel and the public facing site
	 */
	public function register_scripts_and_styles() {
		if ( is_admin() ) {
			$this->load_file( JGContentHolders::slug . '-admin-script', '/js/admin.js', true );
			$this->load_file( JGContentHolders::slug . '-admin-style', '/css/admin.css' );
		}
	}
	
	/**
	 * Helper function for registering and enqueueing scripts and styles.
	 * @param The ID to register with WordPress
	 * @param The path to the actual file
	 * @param Optional argument for if the incoming file_path is a JavaScript source file.
	 */
	public function load_file( $name, $file_path, $is_script = false ) {

		$url = plugins_url($file_path, __FILE__);
		$file = plugin_dir_path(__FILE__) . $file_path;

		if( file_exists( $file ) ) {
			if( $is_script ) {
				wp_register_script( $name, $url, array('jquery') ); //depends on jquery
				wp_enqueue_script( $name );
			} else {
				wp_register_style( $name, $url );
				wp_enqueue_style( $name );
			} 
		}

	}
}