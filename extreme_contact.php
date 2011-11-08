<?php
/*
Plugin Name: Bytewire Contact Form
Plugin URI: http://www.bytewire.co.uk
Description: Basic Contact Form
Version: 1.0
Author: Elliot Reeve
License: GPL2
*/

function bwc_checkEmail($email){
  return preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $email) ? TRUE : FALSE;
}

function bwc_checkdata($data,$type){
	if($type==1){
		if(strlen($data)>30){
			return 0;
		} elseif(ereg('[^0-9]', $data)) {
			return 0;
		} else {
			return $data;
		}
	}
	elseif($type==2){
		return addslashes(strip_tags(trim($data)));		
	}
	elseif($type==3){
		if(strlen($data)>50){
			return 0;
		}elseif (ereg('[^A-Za-z0-9]', $data)){
			return 0;
		}else{
			return $data;
		}	
	} elseif($type==4) {
		if(strlen($data)>50){
			return 0;
		}elseif (ereg('[^-A-Za-z0-9_!| ]', $data)){
			return 0;
		}else{
			return $data;
		}	
	} elseif($type==5) {
		if(strlen($data)>200){
			return 0;
		}elseif (ereg('[^-A-Za-z0-9_!| ]', $data)){
			return 0;
		}else{
			return $data;
		}	
	} elseif($type==6) {
		if(strlen($data) < 6) {
			return 0;
		} else {
			return $data;
		}
	}
}


function bw_shortcode() {
	

	if(isset($_POST['contact_submit'])){
		$name = bwc_checkdata($_POST['contact_name'],4);
		$email = bwc_checkEmail($_POST['contact_email']);
		$subject = bwc_checkdata($_POST['contact_subject'],4);
		$body = htmlspecialchars(stripslashes(strip_tags(nl2br($_POST['contact_message_body']))));
		$anti = bwc_checkdata($_POST['contact_anti'],1);	
	
		if($name){
			if($email){
				if($subject){
					if($body){
						if($anti){
							if($anti == 4){

								$headers  = 'MIME-Version: 1.0' . "\r\n";
								$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
															
								$html_text = "<h2>Email from Bytewire Contact form</h2><hr><b>From:</b> ".$name."</br><b>Email Address:</b> ".$_POST['contact_email']."<br><b>Subject:</b> ".$subject."<br><b>Message:</b> ".nl2br($body);								
															
								$to      = 	'bytewire@bytewire.co.uk';
								$subject = 'Bytewire Contact: '.$subject;
								$headers.= 'From: contact@bytewire.co.uk' . "\r\n" .
								    'Reply-To: '.$_POST['contact_email'] . "\r\n" .
								    'X-Mailer: PHP/' . phpversion();
								    
echo '<script type="text/javascript">
/* <![CDATA[ */
var google_conversion_id = 1000026411;
var google_conversion_language = "en";
var google_conversion_format = "3";
var google_conversion_color = "ffffff";
var google_conversion_label = "Ow82COX6ogMQq-Ls3AM";
var google_conversion_value = 0;
/* ]]> */
</script>
<script type="text/javascript" src="http://www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="http://www.googleadservices.com/pagead/conversion/1000026411/?label=Ow82COX6ogMQq-Ls3AM&amp;guid=ON&amp;script=0"/>
</div>
</noscript>';
								    
								    
					
								mail($to, $subject, $html_text, $headers);
								$message = gettext("<div class='message success'>Thanks! We will get back to you shortly.</div>");	
							
							} else {
								$message = gettext("<div class='message fail'>You filled in the answer of 2 + 2 wrong…</div>");
							}
						} else {
							$message = gettext("<div class='message fail'>You must fill in the anti script.</div>");
						}
					} else {
						$message = gettext("<div class='message fail'>You must fill out anti script check.</div>");
					}
				} else {
					$message = gettext("<div class='message fail'>You must enter a subject.</div>");
				}
			} else {
				$message = gettext("<div class='message fail'>You must include a valid email address.</div>");
			}
		} else {
			$message = gettext("<div class='message fail'>You must enter your name.</div>");
		}
	}
	
	$output .= $message;
	
	$output .= 	'
					<form method="post">
						<div class="grid_4 marginbottom5">
							Name:
						</div>
						<div class="grid_8 marginbottom5">
							<input type="text" name="contact_name" class="contact_text">
						</div>
						<div class="clear"></div>
						<div class="grid_4 marginbottom5">
							Email Address:
						</div>
						<div class="grid_8 marginbottom5">
							<input type="text" name="contact_email" class="contact_text">
						</div>
						<div class="clear"></div>
						<div class="grid_4 marginbottom5">
							Subject:
						</div>
						<div class="grid_8 marginbottom5">
							<input type="text" name="contact_subject" class="contact_text">
						</div>
						<div class="clear"></div>
						<div class="grid_4 marginbottom5">
							Message:
						</div>
						<div class="grid_8 marginbottom5">
							<textarea cols="38" rows="6" name="contact_message_body" class="contact_text"></textarea>
						</div>
						<div class="clear"></div>
						<div class="grid_4 marginbottom5">
							Answer of 2 + 2:
						</div>
						<div class="grid_8 marginbottom5">
							<input type="text" name="contact_anti" class="contact_text">
						</div>
						<div class="clear"></div>
						<div class="grid_12 center margintop10">
							<button type="submit" name="contact_submit" class="button green">Send</button>
						</div>
					</form>
				';
	return $output;
	
}


// Register the shortcode to the function ec_shortcode()
add_shortcode( 'bytewire-contact', 'bw_shortcode' );
