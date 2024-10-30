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

class JGCHInstall {

	private $tableName = 'jgch_shortcodes';
	
    /**
     * Installs the needed tables to database
     * @return void
     */
    public function installDatabaseTables() {
		global $wpdb;
		$table = $wpdb->prefix . $this->tableName;
		$wpdb->show_errors();		
		$sql = "CREATE TABLE $table (
		  id mediumint(9) NOT NULL AUTO_INCREMENT,
		  time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		  name tinytext NOT NULL,
		  content text NOT NULL,
		  type text NOT NULL,
		  UNIQUE KEY id (id)
		);";
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
		$wpdb->hide_errors();
    }
    

    /**
     * Uninstalls the plugin
     * @return void
     */
    public function unInstallDatabaseTables() {

    }

}