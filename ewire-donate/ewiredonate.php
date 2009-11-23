<?php
/*
Plugin Name: Ewire Donation
Plugin URI: http://ewirepayment.codeplex.com/
Description: A smart and easy way to receive donations.
Version: 1.0.0
Author: Ewire
Author URI: http://ewirepayment.codeplex.com/
*/
// Default data
$receiver_email			= 'Your Ewire e-mail';
$subject				= 'Type a subject';
$message				= 'Type a message';
$currency				= 'DKK';
$amount					= '0';
$btn_lang				= 'DK';


// Put our defaults in the "wp-options" table
add_option("ewiredonate-receiver", $receiver_email);
add_option("ewiredonate-subject", $subject);
add_option("ewiredonate-message", $message);
add_option("ewiredonate-currency", $currency);
add_option("ewiredonate-amount", $amount);
add_option("ewiredonate-btnlang", $btn_lang);


	// Installation
	if ( ! class_exists( 'EWIREDONATE_Admin' ) ) {
		class EWIREDONATE_Admin {
			// prep options page insertion
	function add_config_page() {
		if ( function_exists('add_submenu_page') ) {
			add_options_page('Donate', 'Ewire Donations', 10, basename(__FILE__), array('EWIREDONATE_Admin','config_page'));
		}
	}
	// Options/Settings page in WP-Admin
	function config_page() {
		if ( isset($_POST['submit']) ) {
			$nonce = $_REQUEST['_wpnonce'];
			if (! wp_verify_nonce($nonce, 'ewiredonate-updatesettings') ) die('Security check failed'); 
			if (!current_user_can('manage_options')) die(__('You cannot edit the search-by-category options.'));
			check_admin_referer('ewiredonate-updatesettings');
			
			// Get our new option values
			$receiver_email		= $_POST['receiver_email'];
			$subject			= $_POST['subject'];
			$message			= $_POST['message'];
			$currency			= $_POST['currency'];
			$amount				= $_POST['amount'];				
			$btn_lang			= $_POST['btnlang'];			
				
		if(isset($_POST['post_category'])){
			$raw_excluded_cats 	= $_POST['post_category'];
			$fix				= $raw_excluded_cats;
			array_unshift($fix, "1");
			$excluded_cats		= implode(',',$fix);
			}
				
			
			// Update the DB with the new option values
			update_option("ewiredonate-receiver", mysql_real_escape_string($receiver_email));
			update_option("ewiredonate-subject", mysql_real_escape_string($subject));
			update_option("ewiredonate-message", mysql_real_escape_string($message));
			update_option("ewiredonate-currency", mysql_real_escape_string($currency));
			update_option("ewiredonate-amount", mysql_real_escape_string($amount));
			update_option("ewiredonate-btnlang", mysql_real_escape_string($btn_lang));				
	}

	$receiver_email		= get_option("ewiredonate-receiver");
	$subject			= get_option("ewiredonate-subject");
	$message			= get_option("ewiredonate-message");
	$currency			= get_option("ewiredonate-currency");
	$amount				= get_option("ewiredonate-amount");
	$btn_lang			= get_option("ewiredonate-btnlang");			


	echo'<script src="'.get_option('siteurl'). '/wp-content\plugins\ewire-donate\inc\js\jquery.js"> </script>';
	echo'<script src="'.get_option('siteurl'). '/wp-content\plugins\ewire-donate\inc\js\main.js"> </script>';				
?>
<style>
pre{display:block;font:100% "Courier New", Courier, monospace;padding:10px;border:1px solid #bae2f0;background:#e3f4f9;margin:.5em 0;overflow:auto;width:800px;}
img{border:none;}
#screenshot{position:absolute;border:1px solid #ccc;background:#333;padding:5px;display:none;color:#fff;}
</style>
	<div class="wrap">
		<h2>Ewire Donate</h2>
		<p>Accept donation on your blog. Just fill out the form below 
			and you are ready to go!.<br>Go to your Ewire account
		<a href="http://www.ewire.dk/" target="_blank">here</a>.</p>
	<form action="" method="post" id="sbc-config">
				<table class="form-table">
					<?php if (function_exists('wp_nonce_field')) { wp_nonce_field('ewiredonate-updatesettings'); } ?>
						<tr>
							<th scope="row" valign="top"><label for="search-text">
								Ewire e-mail:</label></th>
								<td>
								<input type="text" name="receiver_email" id="search-text" class="regular-text" value="<?php echo $receiver_email; ?>"/></td>
						</tr>
						<tr>
							<th scope="row" valign="top"><label for="focus">
							Subject:</label></th>
							<td>
							<input type="text" name="subject" id="subject" class="regular-text" value="<?php echo $subject; ?>"/></td>
						</tr>
						<tr>
							<th scope="row" valign="top">Message:</th>
							<td>
								<textarea name="message" style="width: 231px; height: 86px"><?php echo $message; ?></textarea></td>
						</tr>
						<tr>
							<th scope="row" valign="top">Currency:</th>
							<td>
								Danish 
								<input type="radio" name="currency" id="currency" value="DKK" <?php if ($currency == 'DKK') echo 'checked="checked"'; ?> style="width: 20px" /> 
								Swedish 
								<input type="radio" name="currency" id="currency" value="SEK" <?php if ($currency == 'SEK') echo 'checked="checked"'; ?> /> 
								Norwegian 
								<input type="radio" name="currency" id="currency" value="NOK" <?php if ($currency == 'NOK') echo 'checked="checked"'; ?> /></td>
								</tr>
						<tr>
							<th scope="row" valign="top">Amount</th>
							<td>
								<input type="text" name="amount" id="focus0" class="regular-text" value="<?php echo $amount; ?>" style="width: 59px"/> <?php echo $currency; ?></td>
						</tr>
						<tr>
							<th scope="row" valign="top" style="height: 25px">
							Button</th>
							<td style="height: 25px">
								<a class="screenshot" rel="..\wp-content\plugins\ewire-donate\inc\img\btn_dk.gif" title="Dansk Udgave">Danish</a> 
								<input type="radio" name="btnlang" id="currency" value="DK" <?php if ($btn_lang == 'DK') echo 'checked="checked"'; ?> style="width: 20px" /> 
								<a class="screenshot" rel="..\wp-content\plugins\ewire-donate\inc\img\btn_en.gif" title="English Edition">English</a> 
								<input type="radio" name="btnlang" id="currency" value="EN" <?php if ($btn_lang == 'EN') echo 'checked="checked"'; ?> /> 
							</td>
						</tr>
				</table>
			<br/>
		<span class="submit" style="border: 0;"><input type="submit" name="submit" value="Save Settings" /></span>
	</form>
</div>

<?php
		}
	}
}
// Ewire Donate Widgets
	function ewire_widget() {
			$receiver_email		= get_option("ewiredonate-receiver");
			$subject			= get_option("ewiredonate-subject");
			$message			= get_option("ewiredonate-message");
			$currency			= get_option("ewiredonate-currency");
			$amount				= get_option("ewiredonate-amount");
			$btn_lang			= get_option("ewiredonate-btnlang");				
			$fbshare			= get_option("ewiredonate-fbshare");
		
	if ($btn_lang == 'DK')
		{$DonateBtnLang = '<img src="wp-content\plugins\ewire-donate\inc\img\btn_dk.gif" />';}
	if ($btn_lang == 'EN')
		{$DonateBtnLang = '<img src="wp-content\plugins\ewire-donate\inc\img\btn_en.gif" />';}
			echo '<h2>Donate</h2>';
			echo '<br />';	
		$DonateLink = 'https://secure.ewire.dk/payment/email.asp?TransactionEmail_Emailaddress='.$receiver_email.'&TransactionEmail_Subject='.$subject.'&TransactionEmail_Message='.$message.'&TransactionEmail_Amount='.$amount.'%2c00&TransactionEmail_Currency='.$currency.'&TransactionEmail_AllowChange=0';
			echo '<a target="_blank" href="'.$DonateLink.'">'.$DonateBtnLang.'</a><br />';
	}
	
	function ewire_widgetADDTHIS(){
			$receiver_email		= get_option("ewiredonate-receiver");
			$subject			= get_option("ewiredonate-subject");
			$message			= get_option("ewiredonate-message");
			$currency			= get_option("ewiredonate-currency");
			$amount				= get_option("ewiredonate-amount");
			$btn_lang			= get_option("ewiredonate-btnlang");				
			$fbshare			= get_option("ewiredonate-fbshare");
			$DonateLink = 'https://secure.ewire.dk/payment/email.asp?TransactionEmail_Emailaddress='.$receiver_email.'&TransactionEmail_Subject='.$subject.'&TransactionEmail_Message='.$message.'&TransactionEmail_Amount='.$amount.'%2c00&TransactionEmail_Currency='.$currency.'&TransactionEmail_AllowChange=0';
			echo '
			<h2>Share Donation</h2>
			<br />
			<!-- AddThis Button BEGIN -->
			<a class="addthis_button" href="http://addthis.com/bookmark.php?v=250&amp;pub=xa-4afbf4137fff7d5e" addthis:url="'.$DonateLink.'" addthis:title="'.$subject.'"><img src="http://s7.addthis.com/static/btn/sm-share-en.gif" width="83" height="16" alt="Bookmark and Share" style="border:0"/></a><script type="text/javascript" src="http://s7.addthis.com/js/250/addthis_widget.js#pub=xa-4afbf4137fff7d5e"></script>
			<!-- AddThis Button END -->
			<br />
			<br />
			';
	}

	function ewire_widgetFB() {
			$receiver_email		= get_option("ewiredonate-receiver");
			$subject			= get_option("ewiredonate-subject");
			$message			= get_option("ewiredonate-message");
			$currency			= get_option("ewiredonate-currency");
			$amount				= get_option("ewiredonate-amount");
			$btn_lang			= get_option("ewiredonate-btnlang");				
			$fbshare			= get_option("ewiredonate-fbshare");
			$DonateLink = 'https://secure.ewire.dk/payment/email.asp?TransactionEmail_Emailaddress='.$receiver_email.'&TransactionEmail_Subject='.$subject.'&TransactionEmail_Message='.$message.'&TransactionEmail_Amount='.$amount.'%2c00&TransactionEmail_Currency='.$currency.'&TransactionEmail_AllowChange=0';
				echo '<h2>Share on Facebook</h2>';
				echo '<br />';
				echo '<a name="fb_share" type="icon_link" share_url="'.$DonateLink.'" href="http://www.facebook.com/sharer.php">Share donation</a><script src="http://static.ak.fbcdn.net/connect.php/js/FB.Share" type="text/javascript"></script>';
				echo '<br />';
				echo '<br />';						
	}

	function init_ewireBT(){register_sidebar_widget("Ewire Donate", "ewire_widget");}
	function init_ewireFB(){register_sidebar_widget("Ewire Donate - Share on FB", "ewire_widgetFB");}
	function init_ewireAT(){register_sidebar_widget("Ewire Donate - Addthis Button", "ewire_widgetADDTHIS");}
 
		// Add Widgets to list
		add_action("plugins_loaded", "init_ewireBT");
		add_action("plugins_loaded", "init_ewireFB");
		add_action("plugins_loaded", "init_ewireAT");

		// Settings for admin
		add_action('admin_menu', array('EWIREDONATE_Admin','add_config_page'));
?>




