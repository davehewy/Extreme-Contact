<?php
/*
Plugin Name: Extreme Contact
Plugin URI: http://www.bytewire.co.uk/wordpress-plugins/extreme-contact/
Description: Extensive & Simple Contact Form.
Version: 1.0
Author: Bytewire
License: GPL2
*/

/**
* Action block
*/ 

register_activation_hook(__FILE__, 'xtrcon_activate' );
register_deactivation_hook( __FILE__, 'xtrcon_deactivate' );
add_action( 'admin_init' , 'xtrcon_save_settings');
add_action('admin_menu', 'xtrcon_admin_menu');

/**
* end action and hook registrations 
*/

/**
 * xtrcon_activate function.
 * 
 * @access public
 * @return void
 */

if(!function_exists('xtrcon_activate')):

	function xtrcon_activate(){
		
		/**
		* Register all of the settings defaults
		*/ 
		
		$from_address = 'webmaster@'.xtrcon_remove_http(get_bloginfo('siteurl'));
		
		add_option( 'xtrcon_sent_from', get_bloginfo('name'), '', 'yes' );
		add_option( 'xtrcon_subject','New Contact Form Submission', '', 'yes' );
		add_option( 'xtrcon_to_address', get_bloginfo('admin_email'), '', 'yes' );
		add_option( 'xtrcon_from_address', get_bloginfo('admin_email'), '', 'yes' );
		add_option( 'xtrcon_use_plain_text', 'yes', '', 'yes' );
		add_option( 'xtrcon_successful_submission_text', 'We have received your form. A member of our team will respond shortly.', '', 'yes' );
		add_option( 'xtrcon_use_wp_nonce', 'yes', '', 'yes' );
		
	}
	
endif;

/**
 * xtrcon_deactivate function.
 * 
 * @access public
 * @return void
 */

if(!function_exists('xtrcon_deactivate')):
	function xtrcon_deactivate(){
		delete_option('xtrcon_to_address');
		delete_option('xtrcon_from_address');
		delete_option('xtrcon_use_plain_text');
		delete_option('xtrcon_successful_submission_text');
		delete_option('xtrcon_use_wp_nonce');
	
			$check_exists = array('xtracon_cc_list'=>'xtrcon_cc_addresses','xtrcon_use_anti' => 'xtrcon_use_anti','xtrcon_anti_secret_question' => 'xtrcon_anti_secret_question','xtrcon_anti_secret_answer' => 'xtrcon_anti_secret_answer','xtrcon_use_storage' => 'xtrcon_use_storage','xtrcon_google_conversion_code'=>'xtrcon_google_conversion_code','xtrcon_use_tricky_field' => 'xtrcon_use_tricky_field');
				
	
		foreach($check_exists as $v):
			delete_option($v);	
		endforeach;
		
		remove_shortcode( 'xtrcon-contact' );
		
	}
endif;

/**
 * xtrcon_remove_http function.
 * 
 * @access public
 * @param mixed $url
 * @return void
 */

if(!function_exists('xtrcon_remove_http')):

	function xtrcon_remove_http($url){
		$remove = array('http://','https://','www.');

		$stripedUrl = str_replace($remove,'', $url);
		
		return strtolower($stripedUrl);		
	}
endif;


/**
 * xtrcon_admin_menu function.
 * 
 * @access public
 * @return void
 */

if(!function_exists('xtrcon_admin_menu')):
	function xtrcon_admin_menu(){
		add_options_page('Extreme Contact Options', 'Extreme Contact', 'manage_options', 'xtrcon_options_id', 'xtrcon_admin_page');		
	}
endif;


/**
 * xtrcon_checkdata function.
 * 
 * @access public
 * @param mixed $email
 * @return void
 */

if(!function_exists('xtrcon_check_email')):
	function xtrcon_check_email($email){
	  return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
	}
endif;


/**
 * xtrcon_checkdata function.
 * 
 * @access public
 * @param mixed $data
 * @param mixed $type
 * @return void
 */

if(!function_exists('xtrcon_checkdata')):
	function xtrcon_checkdata($data,$type){
	
		if($type==1):
		
			if(strlen($data)>30):
				return 0;
			elseif(ereg('[^0-9]', $data)):
				return 0;
			else:
				return $data;
			endif;
				
		elseif($type==2):
			
			return addslashes(strip_tags(trim($data)));		

		elseif($type==3):
		
			if(strlen($data)>50):
				return 0;
			elseif (ereg('[^A-Za-z0-9]', $data)):
				return 0;
			else:
				return $data;
			endif;	
		
		elseif($type==4):
		
			if(strlen($data)>50):
				return 0;
			elseif (ereg('[^-A-Za-z0-9_!| ]', $data)):
				return 0;
			else:
				return $data;
			endif;
			
		elseif($type==5):
		
			if(strlen($data)>200):
				return 0;
			elseif (ereg('[^-A-Za-z0-9_!| ]', $data)):
				return 0;
			else:
				return $data;
			endif;
				
		elseif($type==6):
			if(strlen($data) < 6)
				return 0;
			else
				return $data;
		endif;
			
	}
endif;

/**
 * xtrcon_save_settings_message function.
 * 
 * @access public
 * @return void
 */

if(!function_exists('xtrcon_save_settings_message')):
	function xtrcon_save_settings_message(){
		echo '<div class="updated"><p>'.__("Extreme Contact settings updated").'</p></div>';
	}
endif;

/**
 * xtrcon_save_settings function.
 * 
 * @access public
 * @return void
 */

if(!function_exists('xtrcon_save_settings')):

	function xtrcon_save_settings(){
		if(isset($_POST['xtrcon_save_settings'])):	
		
			add_action('admin_notices','xtrcon_save_settings_message');
			
			print_r($_POST);
			
			$xtrcon_sent_from = $_POST['xtrcon_sent_from'];
			$xtrcon_subject = $_POST['xtrcon_subject'];
			$xtrcon_to_sub = $_POST['xtrcon_to_address'];
			$xtrcon_from_sub = $_POST['xtrcon_from_address'];
			$xtrcon_cc_sub = $_POST['xtracon_cc_list'];
			$xtrcon_plain_text_alt_sub = $_POST['xtrcon_plain_text_alt'];
			$xtrcon_successful_submission_sub = $_POST['xtrcon_successful_submission_text'];
			$xtrcon_google_conversion_code_sub = $_POST['xtrcon_google_conversion_code'];
			$xtrcon_use_nonce_sub = $_POST['xtrcon_use_nonce'];
			$xtrcon_use_anti_sub = $_POST['xtrcon_use_anti'];
			$xtrcon_anti_secret_question_sub = $_POST['xtrcon_anti_secret_question'];
			$xtrcon_anti_secret_answer_sub = $_POST['xtrcon_anti_secret_answer'];
			$xtrcon_use_storage_sub = $_POST['xtrcon_use_storage'];
			$xtrcon_button_classes = $_POST['xtrcon_button_classes'];
						
			// Now update them or create them.
			update_option( 'xtrcon_sent_from', $xtrcon_sent_from );
			update_option( 'xtrcon_subject', $xtrcon_subject );
			update_option( 'xtrcon_to_address', $xtrcon_to_sub );
			update_option( 'xtrcon_from_address', $xtrcon_from_sub );
			update_option( 'xtrcon_use_plain_text', $xtrcon_plain_text_alt_sub );
			update_option( 'xtrcon_successful_submission_text', $xtrcon_successful_submission_sub );
			update_option( 'xtrcon_use_wp_nonce', $xtrcon_use_nonce_sub );
			
			// Ones we don't know exist.
						
			$check_exists = array(
				'xtracon_cc_list'=>'xtrcon_cc_addresses',
				'xtrcon_use_anti' => 'xtrcon_use_anti',
				'xtrcon_anti_secret_question' => 'xtrcon_anti_secret_question',
				'xtrcon_anti_secret_answer' => 'xtrcon_anti_secret_answer',
				'xtrcon_use_storage' => 'xtrcon_use_storage',
				'xtrcon_google_conversion_code'=>'xtrcon_google_conversion_code',
				'xtrcon_use_tricky_field' => 'xtrcon_use_tricky_field',
				'xtrcon_button_classes'=>'xtrcon_button_classes'
			);
			
			
			foreach($check_exists as $k=>$v):	
												
				// update option;
				if($_POST[$k]):
					update_option($v,$_POST[$k]);
				endif;
			
			endforeach;
			
		endif;
					
	}
endif;

/**
 * xtrcon_admin_page function.
 * 
 * @access public
 * @return void
 */

if(!function_exists('xtrcon_admin_page')):
	function xtrcon_admin_page(){
		
		$xtrcon_sent_from = get_option( 'xtrcon_sent_from','');
		$xtrcon_subject = get_option( 'xtrcon_subject','');						
		$xtrcon_to = get_option( 'xtrcon_to_address', get_bloginfo('admin_email') );
		$xtrcon_from = get_option( 'xtrcon_from_address', '' );
		$xtrcon_cc = get_option( 'xtrcon_cc_addresses', '' );
		$xtrcon_plain_text_alt = get_option( 'xtrcon_use_plain_text', 'no' );
		$xtrcon_successful_submission = get_option( 'xtrcon_successful_submission_text', '');
		$xtrcon_google_conversion_code = get_option( 'xtrcon_google_conversion_code', '' );
		$xtrcon_use_nonce = get_option( 'xtrcon_use_wp_nonce', 'no' );
		$xtrcon_use_tricky_field = get_option( 'xtrcon_use_tricky_field', 'no' );
		$xtrcon_use_anti = get_option( 'xtrcon_use_anti', 'no' );
		$xtrcon_anti_secret_question = get_option( 'xtrcon_anti_secret_question', '' );
		$xtrcon_anti_secret_answer = get_option( 'xtrcon_anti_secret_answer', '' );
		$xtrcon_use_storage = get_option( 'xtrcon_use_storage', 'no' );
		$xtrcon_button_classes = get_option('xtrcon_button_classes','');
		
		echo '<div class="wrap">';
		echo '<h2>'.__('Extreme Contact').'</h2>';
		
		echo '<p>'.__('You can tweak all of the extreme contact forms settings here.').'</p>';
		
		echo '<h3>'.__('Sending details').'</h3>';
		
		echo '<form action="" method="post">';
		
		echo '<table class="form-table">';
		echo '<tr><th><label for="xtrcon_sent_from">From (name):</label></th><td><input type="text" name="xtrcon_sent_from" id="xtrcon_sent_from" value="'.$xtrcon_sent_from.'"><br><span class="description">Defaults to get_bloginfo(\'name\')</span></td></tr>';
		echo '<tr><th><label for="xtrcon_subject">Subject:</label></th><td><input type="text" name="xtrcon_subject" id="xtrcon_subject" value="'.$xtrcon_subject.'"></td></tr>';				
		echo '<tr><th><label for="xtrcon_to_address">To address:</label></th><td><input type="text" name="xtrcon_to_address" id="xtrcon_to_address" value="'.$xtrcon_to.'"><br><span class="description">Defaults to get_bloginfo(\'admin_email\')</span></td></tr>';
		echo '<tr><th><label for="xtrcon_from_address">From address:</label></th><td><input type="text" name="xtrcon_from_address" id="xtrcon_from_address" value="'.$xtrcon_from.'"></td></tr>';
		echo '<tr><th valign="top"><label for="xtracon_cc_list">CC addresses:</label></th><td><input type="text" name="xtracon_cc_list" id="xtracon_cc_list" value="'.$xtrcon_cc.'"><br><span class="description">Comma seperated list dave@gmail.com, joe@gmail.com etc</span></td></tr>';
		
		echo '<tr><td valign="top"><label for="xtrcon_plain_text_alt">Always send plain text alternative:</label></td><td><input type="checkbox" name="xtrcon_plain_text_alt" id="xtrcon_plain_text_alt" value="'.$xtrcon_plain_text_alt.'" ';checked($xtrcon_plain_text_alt,'yes'); echo '></td></tr>';
		
		echo '</table>';
		
		echo '<p class="submit"><input type="submit" name="xtrcon_save_settings" id="submit" class="button-primary" value="Apply"></p>';
			
		echo '<h3>'.__('Output settings').'</h3>';
		
		echo '<table class="form-table">';
		echo '<tr><th><label for="xtrcon_successful_submission_text">Successful submission text:</label></th><td><textarea class="large-text code" name="xtrcon_successful_submission_text" id="xtrcon_successful_submission_text">'.$xtrcon_successful_submission.'</textarea><br><span class="description">On successful submission, enter a message to display</span></td></tr>';
		echo '<tr><th><label for="xtrcon_google_conversion_code">Google conversion code:</label></th><td><textarea class="large-text code" name="xtrcon_google_conversion_code" id="xtrcon_google_conversion_code">'.esc_attr(stripslashes($xtrcon_google_conversion_code)).'</textarea><br><span class="description">On successful submission, have the plugin output your conversion code</span></td></tr>';		
		echo '</table>';	
		
		echo '<p class="submit"><input type="submit" name="xtrcon_save_settings" id="submit" class="button-primary" value="Apply"></p>';			
		echo '<h3>'.__('Security details').'</h3>';
		
		echo '<table class="form-table">';
		echo '<tr><th><label for="xtrcon_use_nonce">Use wp_nonce:</label></th><td><input type="checkbox" name="xtrcon_use_nonce" id="xtrcon_use_nonce" value="yes" ';checked($xtrcon_use_nonce,'yes'); echo '></td></tr>';
		echo '<tr><th><label for="xtrcon_use_tricky_field">Use tricky field:</label></th><td><input type="checkbox" name="xtrcon_use_tricky_field" id="xtrcon_use_tricky_field" value="yes" ';checked($xtrcon_use_tricky_field,'yes'); echo '></td></tr>';
		echo '<tr><th><label for="xtrcon_use_anti">Use anti script:</label></th><td><input type="checkbox" name="xtrcon_use_anti" id="xtrcon_use_anti" value="yes" ';checked($xtrcon_use_anti,'yes'); echo '></td></tr>';
		echo '<tr><th><label for="">IF yes</label></th><td></td></tr>';
		echo '<tr><th><label for="xtrcon_anti_secret_question">Secret question:</label></th><td><input type="text" name="xtrcon_anti_secret_question" id="xtrcon_anti_secret_question" value="'.esc_attr($xtrcon_anti_secret_question).'"><br><span class="description">Example what does 2 x 10 = ?</span></td></tr>';
		echo '<tr><th><label for="xtrcon_anti_secret_answer">Secret answer:</label></th><td><input id="xtrcon_anti_secret_answer" type="text" name="xtrcon_anti_secret_answer"><br><span class="description">Example: 20</span></td></tr>';
		echo '</table>';

		echo '<p class="submit"><input type="submit" name="xtrcon_save_settings" id="submit" class="button-primary" value="Apply"></p>';
		
		echo '<h3>'.__('Storage details').'</h3>';
		
		echo '<table class="form-table">';
		echo '<tr><th><label for="xtrcon_use_storage">Use database storage:</label></th><td><input type="checkbox" name="xtrcon_use_storage" id="xtrcon_use_storage" value="yes" ';checked($xtrcon_use_storage,'yes'); echo '>&nbsp;<span class="description">Will use the database to store all contact forms</span></td></tr>';		
		echo '</table>';
		
		echo '<p class="submit"><input type="submit" name="xtrcon_save_settings" id="submit" class="button-primary" value="Apply"></p>';
		
		echo '<h3>'.__('Control Look & Feel').'</h3>';
		
		echo '<p>'.__("We allow you to control the look and feel of the form by offering a few simple options to wrap the input boxes in your own html.<br>We also understand the importance of CSS controls and you can fully override the css or  You can also add additional classes to the inputs themselves.").'</p>';
		
		echo '<table class="form-table">';
		
		echo '<tr><th><label for="xtrcon_use_storage">Overwrite default output?:</label></th><td><input type="checkbox" name="xtrcon_use_storage" id="xtrcon_use_storage" value="yes" ';checked($xtrcon_use_storage,'yes'); echo '>&nbsp;<span class="description">WARNING! It\'s your responsibility from here on out.</span></td></tr>';
		echo '<tr><th><label for="xtrcon_use_storage">Exclude default CSS:</label></th><td><input type="checkbox" name="xtrcon_use_storage" id="xtrcon_use_storage" value="yes" ';checked($xtrcon_use_storage,'yes'); echo '>&nbsp;<span class="description">WARNING! It\'s your responsibility from here on out.</span></td></tr>';
		echo '<tr><th><label for="xtrcon_button_classes">Button classes:</label></th><td><input type="text" name="xtrcon_button_classes" id="xtrcon_button_classes" '; if($xtrcon_button_classes): echo 'value="'.esc_attr($xtrcon_button_classes).'"'; endif; echo '">&nbsp;<span class="description">Separate each additional class name with a comma.</span></td></tr>';
		echo '<tr><th><label for="xtrcon_use_storage">Additional input classes:</label></th><td><input type="text" name="xtrcon_anti_secret_answer" id="xtrcon_anti_secret_answer" '; if($xtrcon_anti_secret_answer): echo 'value="'.esc_attr($xtrcon_anti_secret_answer).'"'; endif; echo '">&nbsp;<span class="description">Separate each additional class name with a comma.</span></td></tr>';		
		
		echo '<tr><th><label for="xtrcon_use_storage">Before inputs (wrapped by form):</label></th><td><textarea class="large-text code" name="xtrcon_google_conversion_code" id="xtrcon_google_conversion_code">'.esc_attr(stripslashes($xtrcon_google_conversion_code)).'</textarea><br><span class="description">'.__("Include any HTML tags you to proceed the output of the inputs, perhaps a div for padding etc").'</span></td></tr>';	
		
		echo '<tr><th><label for="xtrcon_use_storage">After inputs (wrapped by /form):</label></th><td><textarea class="large-text code" name="xtrcon_google_conversion_code" id="xtrcon_google_conversion_code">'.esc_attr(stripslashes($xtrcon_google_conversion_code)).'</textarea><br><span class="description">'.__("Include any extra content and or closing tags required after the form.").'</span></td></tr>';
		
		echo '<tr><th><label for="xtrcon_use_storage">Before each input:</label></th><td><textarea class="large-text code" name="xtrcon_google_conversion_code" id="xtrcon_google_conversion_code">'.esc_attr(stripslashes($xtrcon_google_conversion_code)).'</textarea><br><span class="description">'.__("Include any HTML tags you to proceed the output of the inputs, perhaps a div for padding etc").'</span></td></tr>';
		
		echo '<tr><th><label for="xtrcon_use_storage">After each input:</label></th><td><textarea class="large-text code" name="xtrcon_google_conversion_code" id="xtrcon_google_conversion_code">'.esc_attr(stripslashes($xtrcon_google_conversion_code)).'</textarea><br><span class="description">'.__("Include any HTML tags you to proceed the output of the inputs, perhaps a div for padding etc").'</span></td></tr>';	
		
		echo '</table>';		
		
		echo '<p class="submit"><input type="submit" name="xtrcon_save_settings" id="submit" class="button-primary" value="Apply"></p>';		
		
		echo '</form>';
		
		echo '</div>';
	}
endif;

/**
 * xtrcon_store_submission function.
 * 
 * @access public
 * @return void
 */

if(!function_exists('xtrcon_store_submission')):

	function xtrcon_store_submission(){
		
		$args = func_get_args();
	
		global $wpdb;
		
		// Look for the xtrcon_submissions table.
		
		$table = $wpdb->prefix."xtrcon_submissions";
		$structure = "CREATE TABLE IF NOT EXISTS `$table` (
		  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
		  `name` varchar(255) DEFAULT NULL,
		  `email` varchar(255) DEFAULT NULL,
		  `telephone` varchar(25) DEFAULT NULL,
		  `subject` varchar(255) DEFAULT NULL,
		  `message` text,
		  `time` int(11) DEFAULT NULL,
		  `ip` varchar(25) DEFAULT NULL,
		  `responded` tinyint(4) DEFAULT NULL,
		  `notes` text,
		  PRIMARY KEY (`id`)
		) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=latin1;";
		$wpdb->query($structure);
		
		// Insert the submission.
		
		$wpdb->insert($table,array(
			"name" => $args[0],
			"email" => $args[1],
			"telephone" => $args[3],
			"subject" => $args[2],
			"message" => $args[4],
			"time" => time(),
			"ip" => $_SERVER['REMOTE_ADDR'],
			"responded" => 0
		));
				
	}
endif;

/**
 * xtrcon_shortcode function.
 * 
 * Processes the shortcode
 *
 * @access public
 * @return void
 */

if(!function_exists('xtrcon_shortcode')):
	function xtrcon_shortcode($args){
	
		$supporting_text = $args['supporting_text'];
	
		$xtrcon_sent_from = get_option('xtrcon_sent_from');
		$xtrcon_subject = get_option('xtrcon_subject');
		$xtrcon_to_address = get_option('xtrcon_to_address');
		$xtrcon_from_address = get_option('xtrcon_from_address');
		$xtrcon_cc_address_list = get_option('xtrcon_cc_addresses');
		$xtrcon_successful_submission_text = get_option('xtrcon_successful_submission_text');
		$xtrcon_google_conversion_code = get_option('xtrcon_google_conversion_code');
		$xtrcon_use_storage = get_option('xtrcon_use_storage');
		$xtrcon_use_wp_nonce = get_option('xtrcon_use_wp_nonce');
		
		// Anti script measures
		
		$xtrcon_use_anti = get_option('xtrcon_use_anti');
		$xtrcon_anti_secret_question = get_option('xtrcon_anti_secret_question');
		$xtrcon_anti_secret_answer = get_option('xtrcon_anti_secret_answer');
		
		// Tricky field
		
		$xtrcon_use_tricky_field = get_option( 'xtrcon_use_tricky_field', 'no' );	
		
		// CSS Controls
		
		$xtrcon_button_classes = get_option('xtrcon_button_classes');
		
		$success = false;
		
		$supporting_message = '<p>'.$supporting_text.'</p>';
						
		if(isset($_POST['xtrcon_submit'])):
					
			$xtrcon_sub_name = xtrcon_checkdata($_POST['xtrcon_name'],4);
			$xtrcon_sub_email = $_POST['xtrcon_email'];
			$xtrcon_sub_subject = (xtrcon_checkdata($_POST['xtrcon_subject'],4)) ? xtrcon_checkdata($_POST['xtrcon_subject'],4) : '';
			$xtrcon_sub_body = htmlspecialchars(stripslashes(strip_tags(nl2br($_POST['xtrcon_message_body']))));
			$xtrcon_sub_anti = xtrcon_checkdata($_POST['xtrcon_anti'],4);	
			$xtrcon_phone = $_POST['xtrcon_phone'];
		
			if($xtrcon_sub_name):
				
				if(xtrcon_check_email($xtrcon_sub_email)):
								
					if($xtrcon_phone):
									
					if($xtrcon_sub_body):
					
					
						// If the user has indicated to use a tricky field, it should not be present.
						
						$tricky_field_proceed = true;
												
						if($xtrcon_use_tricky_field == 'yes'):
						
							if(isset($_POST['xtrcon_tricky']) && $_POST['xtrcon_tricky']!='')
								$tricky_field_proceed = false;
													
						endif;
						
						// Proceed if tricky field validated ok.
						
						if($tricky_field_proceed):
						
																					
							// If the user is using the wp_nonce field it must also validate through it.
							
							$wp_nonce_proceed = true;
					
							if($xtrcon_use_wp_nonce == 'yes'):
							
								if(!wp_verify_nonce($_POST['xtrcon_contact_nonce_submit'],'xtrcon_contact_nonce')):
									
									$wp_nonce_proceed = false;
									
								endif;
							
							endif;								
								
							if($wp_nonce_proceed):
																						
								// IF the user has set anti script to yes and set question / answer
								
								$xtrcon_anti_proceed = true;
								
								if($xtrcon_use_wp_nonce == 'yes' && $xtrcon_anti_secret_question!='' && $xtrcon_anti_secret_answer!=''):
									if($anti != $xtrcon_anti_secret_answer):
									
										$xtrcon_anti_proceed = false;
									
									endif;
								
								endif;
									
								if($xtrcon_anti_proceed):
									
									// Send mail using wordpress wp-mail			
									
									$to = $xtrcon_to_address;
									$subject = $xtrcon_subject;
									$headers = 'From: '.$xtrcon_sent_from.' <'.$xtrcon_from_address.'>' . "\r\n";
									
									if($xtrcon_cc_address_list):
									
										$cc_list = explode(",",$xtrcon_cc_address_list);
										
										foreach($cc_list as $k=>$v):
											
											if(!xtrcon_check_email($v))
												unset($cc_list[$k]);
											else
												trim($cc_list[$k]);
																					
										endforeach;
										
										$cc_list = implode(",",$cc_list);
										
										if(count($cc_list)>0)
											$headers .= "Cc: ".$cc_list."\r\n";
									
									endif;
									
									// Html text
									
									$html_text = "<h2>Email from Bytewire Contact form</h2>";
									$html_text.= "<hr><b>From:</b> ".$xtrcon_sub_name."</br>";
									$html_text.= "<b>Email Address:</b> ".$xtrcon_sub_email."<br>";
									$html_text.= "<b>Telephone:</b> ".$xtrcon_phone."<br>";
									$html_text.= "<b>Message:</b> ".nl2br($xtrcon_sub_body);
									
									// Attachments
									$attachments = '';
																		
									add_filter('wp_mail_content_type',create_function('', 'return "text/html";'));
									wp_mail( $to, $subject, $html_text, $headers, $attachments );
																												
									// Output google conversion code
									
									if($xtrcon_google_conversion_code === true)
										echo stripslashes($xtrcon_google_conversion_code);
										
								 	// Store the data in the database
									
									if($xtrcon_use_storage == 'yes'):
																		
										xtrcon_store_submission($xtrcon_sub_name,$xtrcon_sub_email,$xtrcon_sub_subject,$xtrcon_phone,$xtrcon_sub_body);
										
									endif;
									
									// Use the stored success text
									   									 
									$message = '<div class="alert-message block-message success">'.$xtrcon_successful_submission_text.'</div>';
									
									$success = true;
									
								else:									
									$message.= __('<div class="alert-message block-message error">You failed to answer the anti script correctly please try again.</div>');	
									$errors++;	
								endif;
								
							else:
								$message.= __('<div class="alert-message block-message error">You failed to validate it.</div>');	
												
								$errors++;							
							endif;																									
						else:
							$errors++;
						endif; // Tricky field is so tricky, it outputs no error message ;-)																								
					else:
						$message.= __('<div class="alert-message block-message errorl">You must include a message to send to us.</div>');
						$errors++;
					endif;
					
					else:
					
						$message.= __('<div class="alert-message block-message error">You must enter a valid telephone number.</div>');
						$errors++;
					
					endif;
					
				else:
					$message.= __('<div class="alert-message block-message error">You must include a valid email address.</div>');
					$errors++;
				endif;
				
			else:
				$message.= __('<div class="alert-message block-message error">You must enter your name.</div>');
				$errors++;
			endif;
			
		endif;
		
		// Prepend the error or success message to the output
		
		if($message && $errors>0):
			
			if(!$success):
				$retmessage = $supporting_message;
			endif;
			$retmessage .= '<div id="contact_response">';
			$retmessage .= $message;
			$retmessage .= '</div>';
		
		elseif($message && $errors<=0):
		
			$retmessage = $message;
		
		else:
			$retmessage = '';
		endif;

		$output = $retmessage;
		
		if(!$success):

    	// Add the rest of the form output

		$output .= 	'<form id="commentform" class="contactform" method="post" action="">';
		
		$output .= '<p class="content_name">';
		$output .= '<input type="text" tabindex="1" size="22" value="'.$_POST['xtrcon_name'].'" id="xtrcon_name" name="xtrcon_name">';
		$output .= '<label for="xtrcon_name">';
		$output .= '<small>Name (required)</small>';
		$output .= '</label>';
		$output .= '</p>';
		
		$output .= '<p class="content_email">';
		$output .= '<input type="text" tabindex="2" size="22" value="'.$_POST['xtrcon_email'].'" id="xtrcon_email" name="xtrcon_email">';
		$output .= '<label for="xtrcon_email">';
		$output .= '<small>Email (required)</small>';
		$output .= '</label>';
		$output .= '</p>';

		$output .= '<p class="content_phone">';
		$output .= '<input type="text" tabindex="3" size="22" value="'.$_POST['xtrcon_phone'].'" id="xtrcon_phone" name="xtrcon_phone">';
		$output .= '<label for="xtrcon_phone">';
		$output .= '<small>Phone</small>';
		$output .= '</label>';
		$output .= '</p>';			
		
		$output .= '<p class="content_message">';
		$output .= '<textarea tabindex="4" rows="10" cols="95%" id="contact_message" name="xtrcon_message_body">'.stripslashes($_POST['xtrcon_message_body']).'</textarea>';
		$output .= '<label for="xtrcon_phone">';
		$output .= '<small>Message (required)</small>';
		$output .= '</label>';
		$output .= '</p>';		
		
		// Only output the anti checker if the user wants one
		
		if($xtrcon_use_anti == 'yes' && $xtrcon_anti_secret_question!='' && $xtrcon_anti_secret_answer!=''):
		
			$output .= '<p class="xtrcon_anti">';
			$output .=	$xtrcon_anti_secret_question;
			$output .=	'<input type="text" id="xtrcon_anti" name="xtrcon_anti" class="contact_text">';
			$output .= '<label for="xtrcon_anti">';
			$output .= '<small>'.$xtrcon_anti_secret_question.'</small>';
			$output .= '</label>';
			$output .= '</p>';		
		
		endif;
		
		
		// Only output the secret question if the user wants it there.
		
		if($xtrcon_use_wp_nonce == 'yes'):
			
			// Output a wordpress protection nonce.
			
			$output.= wp_nonce_field('xtrcon_contact_nonce','xtrcon_contact_nonce_submit'); 
			
		endif;
		
		// If the user is using a tricky field, output it.
		
		if($xtrcon_use_tricky_field == 'yes'):
		
			$output .= '<input type="text" class="xtrcon_tricky" name="xtrcon_tricky">';
		
		endif;
		
		$output .= '<p class="content_submit">';
		$output .= '<button type="submit"'; 
		
		if($xtrcon_button_classes):
			$classes = explode(",",$xtrcon_button_classes);
			if(count($classes)==1):
				$output .= ' class="'.$xtrcon_button_classes.'" ';
			else:		
				$output .= ' class="';	
				$output .= implode(" ",$classes);
				$output .= '" ';
			endif;
		endif;
		
		$output .= 'tabindex="5" id="submit" name="xtrcon_submit">';
		$output .= __("Submit");
		$putput .= '</button>';
		$output .= '</p>';		

		
		$output .=	'</form>';
		
		endif; /* End success check */

		// Return it for use in shortcode
		
		return $output;
		
	}
endif;

// register the shortcode

add_shortcode( 'xtrcon-contact', 'xtrcon_shortcode' );
