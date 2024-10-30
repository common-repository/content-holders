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

class JGCHShortcodeLoader {
		
    /**
	 * Runs a query to retrieve all savedshortcodes.  Used in init.
     * @return array Containing all short codes and info (id, name, content, type)
     */
	public function getAllShortcodes(){
		global $wpdb;
		$table = $wpdb->prefix . JGContentHolders::tableName;
		//$sql = $wpdb->prepare("SELECT * FROM %s", $table); // NOT WORKING CURRENTLY
		$shortcode = $wpdb->get_results( "SELECT * FROM " .  $table , ARRAY_A );
		if ( $shortcode ) {
			return $shortcode;
		} 
		return false;
	}
	
    /**
	 * Runs a query to retrieve all savedshortcodes.  Used in init.
     * @return array Containing all short codes and info (id, name, content, type)
     */
	public function getCodeByName($_name){
		global $wpdb;
		$table = $wpdb->prefix . JGContentHolders::tableName;
		$shortcode = $wpdb->get_results( "SELECT * FROM " .  $table . " WHERE name ='" . $_name ."'", ARRAY_A );
		if ( $shortcode ) {
			return $shortcode;
		} 
		return false;
	}
	
	
}