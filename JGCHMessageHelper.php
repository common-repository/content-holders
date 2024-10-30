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

class JGCHMessageHelper {

	private static $instance;
	private $message;
	private $goodbad;
	private $goodWrapper = "<div class='jgch-message good'>__MESSAGE__</div>";
	private $badWrapper = "<div class='jgch-message bad'>__MESSAGE__</div>";
		
	/**
	* Create instance if one does not exist and returns instance
	* @return object
	*/
	public static function getInstance() {
		if ( is_null( self::$instance ) ) {
		  self::$instance = new self();
		}
		return self::$instance;
	}
	
	/**
	* Sets the message to be displayed
	* @param The message to be displayed
	* @param Indicates whether to use the good or bad template (red or green)
	* @return boolean
	*/
	public function setMessage($_message, $_goodbad){
		$this->message = $_message;
		$this->goodbad = $_goodbad;
		return true;
	}

	/**
	* Displays the current message.
	* @return boolean
	*/
	public function displayMessage() {
		$wrapper = ($this->goodbad == 'good') ? $this->goodWrapper: $this->badWrapper;
		$body = str_replace('__MESSAGE__', $this->message, $wrapper);
		if (empty($this->message)) {
			return false;
		}
		echo $body;
		return true;
	}

}