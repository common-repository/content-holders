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

require_once('JGCHAdminFrontendAbstract.php');

class JGCHAdmin extends JGCHAdminFrontendAbstract {

	function __construct() {
		require_once('JGCHMessageHelper.php');
		require_once('JGCHShortcodeLoader.php');
		//trigger function to create admin menu button
		add_action('admin_menu', array(&$this, 'createAdminMenu'));
		add_action('admin_init', array(&$this, 'editor_admin_init'));
		add_action('admin_head', array(&$this, 'editor_admin_head'));
		$this->register_scripts_and_styles();
	}

	public function editor_admin_init() {
	  wp_enqueue_script('word-count');
	  wp_enqueue_script('post');
	  wp_enqueue_script('editor');
	  wp_enqueue_script('media-upload');
	}
	 
	public function editor_admin_head() {
	  wp_tiny_mce();
	}
	
    /**
     * Retrieve data from POST and return in desired format array
     * @return array
     */
	private function getPostData() {
		$currentid = $_POST['id'];
		if (isset($_POST['id'])){
			$postData = array(
				'id' => intval($_POST['id']),
				'name' => $_POST['name'],
				'content' => $_POST['content' . $currentid],
				'type' => $_POST['type']
			);
			return $postData;
		}
		if (isset($_POST['create-new'])){
			$postData = array(
				'id' => null,
				'name' => 'New Content Holder',
				'content' => 'New Content',
				'type' => 'text'
			);
			return $postData;
		}
		return false;
	}
	
    /**
     * Calls DB functions to save/update new Content Holder or delete
     * @return boolean
     */
	private function handlePostData(){
		$postData = $this->getPostData();
		if (is_array($postData)) {
			$instance = new JGCHShortcodeBase;
			$instance->id = $postData['id'];
			$instance->content = $postData['content'];;
			$instance->name = $postData['name'];
			$instance->type = $postData['type'];
			if (isset($_POST['delete']) && $_POST['delete'] == '1') {
				$instance->deleteShortcode();
				JGCHMessageHelper::getInstance()->setMessage('Content Holder Deleted', 'good');
				return true;
			}
			$instance->updateShortcode();
			return true;
		}
		return true;
	}

	/**
	* Runs a query and returns all shortcodes to be displayed in admin
	* @return array Containing shortcodes
	*/
	private function loadShortcodes(){
		$allCodes = JGCHShortcodeLoader::getAllShortcodes();
		if (is_array($allCodes)) {
			return $allCodes;
		}
		return false;
	}
	
	/**
	* Get value of opened parameter
	* @return string Containing value of opened GET parameter
	*/
	private function openedTabs($_id){
		return (intval($_GET['opened']) == intval($_id))?'':'closed';
	}	
	
    /**
     * Install page for this plugin in WP Admin
     * @return void
     */
    public function createAdminMenu() {
        //create new top-level menu
        add_menu_page('Content Holders','Content Holders','manage_options','content_holder',array($this, 'settingsPage'));
                      
		/* OTHER MENU OPTIONS */
		//add_options_page('Content Holders','Content Holders','manage_options','options_page_slug',array($this, 'settingsPage'));		
        //add_submenu_page('Content Holders','Content Holders','manage_options','options_page_slug',array($this, 'settingsPage'));
    }

    /**
     * Register option fields with wordpress
     * @return void
     */
    private function registerSettings() {
        $settingsGroup = get_class($this) . '-settings-group'; 
        register_setting( $settingsGroup, 'name' );
        register_setting( $settingsGroup, 'time' );
        register_setting( $settingsGroup, 'date' );
    }
    
	private function getActionUrl($_id = null){
			$actionUrl = $_SERVER['PHP_SELF'];
			$actionUrl .= "?";
			$actionUrl .= preg_replace('/&opened\=[0-9]*/', '', $_SERVER['QUERY_STRING']);
			if ($_id !== null) {
				$actionUrl .= '&opened=';
				$actionUrl .= $_id;
				// $actionUrl .= "#box" . $_id; // Append box id to end of url
			}
			return $actionUrl;
	} 

	private function hideOrShow($_id) {
		return (intval($_GET['opened']) == intval($_id)) ? 'style="display: block"' : 'style="display: none"';
	}
		
    /**
     * Create the html and php for the options page
     * @return void
     */
    public function settingsPage() {
                    
   		$settingsGroup = get_class($this) . '-settings-group';
    	
        if (!current_user_can('manage_options')) {
            wp_die(__('You do not have sufficient permissions to access this page.'));
        }
		
		// Handle Post Data if there is any
		$this->handlePostData();
		
		// Load all codes for display
		$loadedCodes = $this->loadShortcodes();
		
        // HTML for the page
        ?>
        <div class="wrap">
            <h2>
            	<?php _e('Content Holders'); ?>
            	<form action="<?php echo $this->getActionUrl($code['id']) ?>" method='post'>
            		<input type="hidden" name="create-new" value="1"/>
            		<input type="submit" class="add-new-h2" value="Add New"/>
            	</form>
            </h2>
            <h4>Create a new code by clicking "Add New."  Add content and use the code (shortcode in Wordpress, or php code in templates) to display the content.</h4>
            <?php 
            	JGCHMessageHelper::getInstance()->displayMessage();
            ?>
            <?php settings_fields($settingsGroup); ?>            
            <?php if ( !empty($loadedCodes) ){ ?>	
				<?php foreach ($loadedCodes as $code) { ?>
						<div class="jgch-postbox jgch <?php echo $this->openedTabs($code['id']) ?>" id="box<?php echo $code['id'] ?>">
							<div class="jgch-handlediv" title="Click to toggle"><div class="jgch-arrow-down"></div></div>
							<h3 class="jgch-hndle"><span><?php echo $code['name'] ?></span></h3>
							<form method="post" action="<?php echo $this->getActionUrl($code['id']) ?>" <?php echo $this->hideOrShow($code['id']); ?>>
								<table class="form-table"><tbody>
									<tr>
										<td>
											<div class="code-wrapper">
												<span class="code">Your Code: <strong>[<?php echo $code['name'] ?>]</strong> or <strong>&lt;?php echo do_shortcode('[<?php echo $code['name'] ?>]'); ?&gt;</strong></span>
											</div>
										</td>
									</tr>
									<tr>
										<td>
											<input type="hidden" name="id" value="<?php echo $code['id'] ?>"/>
											<input type="text" placeholder="Name" name="name" value="<?php echo $code['name'] ?>" /> 
										</td>
									</tr>
									<tr>
										<td><?php the_editor(stripslashes($code['content']), "content" . $code['id'], "", true); ?></td>
									</tr>
									<tr>
										<td><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>"/></td>
									</tr>
								</tbody></table>
							</form>	
							<form action="<?php echo $this->getActionUrl($code['id']) ?>" method='post' <?php echo $this->hideOrShow($code['id']); ?>>
								<input type="hidden" name="delete" value="1"/>
								<input type="hidden" name="id" value="<?php echo $code['id'] ?>"/>
								<input type="submit" class="btn alert" value="Delete"/>
							</form>	               
						</div>
					<script>
						jQuery(function(){
							jQuery('#type<?php echo $code['id']?>').val('<?php echo $code['type'] ?>');
						});
					</script>
				<?php } ?>
			<?php } ?>	
        </div>
        <?php
    }
}