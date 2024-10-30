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

class JGCHShortcodeBase {

	public $id = null;
	public $content = null;
	public $name = null;
	public $type = "text";
	
	function __construct() {
		require_once('JGCHShortcodeLoader.php');
		require_once('JGCHMessageHelper.php');
	}
	
    /**
	 * Public method to begin registering shortcode
	 * @param 
     * @return void
     */
	public function registerShortcode(){
		if ($this->id !== null && $this->content !== null && $this->name !== null) {
			$this->register($this->name);
		} else {
			echo "You must set an id and content";
		}
	}

    /**
	 * Runs a query to check of name is taken.
     * @return boolean
     */
	protected function codeNameExists($_name){
		$allCodes = JGCHShortcodeLoader::getCodeByName($_name);
		if (is_array($allCodes) ) {
			return true;
		}
		return false;      
	}

    /**
	 * Runs a query to check of the code name exists, and if so, if it is the current code.
	 * Used when updating codes to ensure you don't change the code name to an existing code name.
     * @return boolean
     */
	protected function isCurrentCode($_name, $_id){
		$resultingCodes = JGCHShortcodeLoader::getCodeByName($_name);
		if (is_array($resultingCodes) && $_id != $resultingCodes[0]['id']) {
			return true;
		}
		return false;
	}

    /**
	 * Runs a query to update shortcode name, id, content, type.
	 * Must first set id of class 
     * @return array
     */
	public function updateShortcode(){
		global $wpdb;
		$shortcode = null;
		//var_dump($shortcode);e
		$table = $wpdb->prefix . JGContentHolders::tableName;
		if ($this->id != null) {
			if ($this->isCurrentCode($this->name, $this->id)) {
				JGCHMessageHelper::getInstance()->setMessage('Cannot create another Content Holder named "'.$this->name.'."  You must change the name of "'.$this->name.'."', 'bad');
				return false;
			}
			$shortcode = $wpdb->update( 
				$table, 
				array( 
					'name' => $this->name, // string
					'content' => $this->content, // string
					'type' => 'text'	// string
				), 
				array( 'ID' => $this->id ), 
				array( 
					'%s',	// name format
					'%s',	// content format
					'%s'	// content format
				), 
				array( '%d' ) 
			);	
			JGCHMessageHelper::getInstance()->setMessage('Content Holder updated', 'good');
		} else {
			if ($this->codeNameExists($this->name)) {
				JGCHMessageHelper::getInstance()->setMessage('Cannot create another Content Holder named "'.$this->name.'."  You must change the name of "'.$this->name.'."', 'bad');
				return false;
			}
			$shortcode = $wpdb->insert( 
				$table, 
				array( 
					'name' => $this->name, // string
					'content' => $this->content, // string
					'type' => 'text'	// string
				), 
				array( 
					'%s',	// name format
					'%s',	// content format
					'%s'	// content format
				) 
			);
			JGCHMessageHelper::getInstance()->setMessage('Content Holder created', 'good');
		}
		if ( $shortcode !==  null) {
			return $shortcode;
		}
		echo "Something went wrong with updating/creating your short code.";
		return false;
	}

	/**
	* Delete current shortcode
	* @return void
	*/
	public function deleteShortcode(){
		global $wpdb;
		$table = $wpdb->prefix . JGContentHolders::tableName;
		$result = $wpdb->query("DELETE FROM ".$table." WHERE id = ".$this->id);
	}
	
	/**
	* Register shortcode to the 'handleShortcode' method
	* @param string Containg name of shortcode 
	* @return void
	*/
    protected function register($shortcodeName) {
        $this->registerShortcodeToFunction($shortcodeName, 'handleShortcode');
    }

	/**
	* Register shortcode to a custom method
	* @param string Containg name of shortcode 
	* @param string Containing custom function name
	* @return void
	*/
    protected function registerShortcodeToFunction($shortcodeName, $functionName) {
        if (is_array($shortcodeName)) {
            foreach ($shortcodeName as $aName) {
                add_shortcode($aName, array($this, $functionName));
            }
        } else {
            add_shortcode($shortcodeName, array($this, $functionName));
        }
    }

	/**
	* Method registered to the shortcode
	* @param array Containing array of attribute passed by shortcode
	* @return void
	*/
    public function handleShortcode($atts){
	   return $this->content;
	}
}