<?php

include_once('internalaccess/connectdb.php');
//include_once('internalaccess/session.php');
//include_once('internalaccess/functions.php');
#include_once('sendmail_smtp.php');
function SendHTMLMail($to,$subject,$mailcontent,$from1)
	{
	
		$limite = "_parties_".md5 (uniqid (rand()));
		$headers  = "MIME-Version: 1.0\r\n";
		//$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
		$headers .= "Content-type: text/html; charset=UTF-8\r\n";
		$headers .= "From: $from1\r\n";
		if(mail($to,$subject,$mailcontent,$headers))
			return true;
	}
$contactclass='active';

if(isset($_POST['btnSubmit'])){
	
	$c_name = mysql_real_escape_string($_POST['c_name']);
	$c_email = mysql_real_escape_string($_POST['c_email']);
	$c_phone = mysql_real_escape_string($_POST['c_phone']);
	$c_message = mysql_real_escape_string($_POST['c_message']);
	
	session_start();
	if(empty($_SESSION['captcha_code'] ) || strcasecmp($_SESSION['captcha_code'] , $_POST['captcha_code']) != 0)
	{  
		//$msgc="<span style='color:red; padding-left:188px;'>The Validation code does not match!</span>";// Captcha verification is incorrect.
		$msgc="<script>
			alert('captch does not match!');
		</script>";
		
	}
	else
	{
			//Admin send Mail
			
			$content = "Dear Administrator, <br><br>";
			$content .="Name:".$c_name."<br>";
			$content .="Email:".$c_email."<br>";
			$content .="Phone No.:".$c_phone."<br>";
			$content .="Message:".$c_message."<br><br>";
			$content .= "Thanks <br>" .$c_name. "<br><br>";
			$to = $ADMINEMAIL;
			$from = $ADMINEMAIL;
			
			SendHTMLMail($to,"Contact Us",$content,$c_email);
			
			//user send Mail
			
				$content_user = "Hello," .$c_name. "<br><br>";
				$content_user .="Your Contact Details submitted successfully.<br><br>";
				$content_user .= "Thanks <br>";
				$content_user .= "Real Business Broker Team <br>";
				if(SendHTMLMail($c_email,"Contact Us",$content_user,$from))
					$msg = "Your contact enquiry has been submitted successfully.";
				else
					$msg = "Can not sent. Please Try again!";
			
	}
}
?>
<!DOCTYPE>
<html lang="eg">
<head>
<meta charset="utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Business Broker</title>
<link rel="stylesheet" href="assert/css/style.css">
<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,600,700' rel='stylesheet' type='text/css'>
<link rel="stylesheet" href="assert/css/responsive-menu.css">
<script src="assert/js/jquery.min.js"></script>
<script src="assert/js/bootstrap.min.js"></script>
<script src="http://maps.googleapis.com/maps/api/js"></script>
<script>
	
jQuery(document).ready(function(){
		  var myCenter = new google.maps.LatLng(39.894918,-74.943433);
		  var mapOptions = {
				zoom: 16,
				center: myCenter,
				mapTypeId: google.maps.MapTypeId.ROADMAP,
			};
			var mapelement = 'googleMap';
			var map_new = new google.maps.Map(document.getElementById(mapelement), mapOptions);
			google.maps.visualRefresh = true;
			var marker_new=new google.maps.Marker({
			  position:myCenter,
			  map: map_new,
			 });
			
			marker_new.setMap(map_new);
		
		jQuery('.numers_only').on('keydown',function(e){
 		var val = jQuery(this).val();
			
			// Allow: backspace, delete, tab, escape, enter and .
			//alert(e.keyCode+'=>'+val);
			if (e.keyCode == 110 && val.indexOf('.') !== -1){
				e.preventDefault();
				return false;
			}
			
        if (jQuery.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
             // Allow: Ctrl+A
            (e.keyCode == 65 && e.ctrlKey === true) || 
             // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
                 // let it happen, don't do anything
                 return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }
	});
	

});


	/*jQuery('.numers_only').on('blur',function(){
		var val = jQuery(this).val();
		if(val!='' && val != null){
			val = parseFloat(val).toFixed(2);
		}else{
			val = '';
		}
		jQuery(this).val(val);
	});*/
	
function refreshCaptcha(){
	var img = document.images['captchaimg'];
	img.src = img.src.substring(0,img.src.lastIndexOf("?"))+"?rand="+Math.random()*1000;
}

function validate(){
		//var isValid = true;
		if(jQuery('#c_name').val()==""){
			jQuery('#c_name').css({'border-color':'red'});
			return false;
		}
		else if(jQuery('#c_email').val()==""){
			jQuery('#c_email').css({'border-color':'red'});
			return false;
		}
		else if(!emailInvalid(jQuery('#c_email').val())){
			jQuery('#c_email').css({'border-color':'red'});
			return false;
		}
		else if(jQuery('#c_phone').val()==""){
			jQuery('#c_phone').css({'border-color':'red'});
			return false;
		}
		/*else if(jQuery('#c_phone').val().length<10){
			jQuery('#c_phone').css({'border-color':'red'});
			return false;
		}*/
		else if(jQuery('#c_message').val()==""){
			jQuery('#c_message').css({'border-color':'red'});
			return false;
		}
		else if(jQuery('#captcha_code').val()==""){
			jQuery('#captcha_code').css({'border-color':'red'});
			return false;
		}
		else{
			return true;
		} 		
	}
	function emailInvalid(s){
	if(!(s.match(/^[\w]+([_|\.-][\w]{1,})*@[\w]{2,}([_|\.-][\w]{1,})*\.([a-z]{2,4})$/i) ))
        {
		return false;
	}
	else{
		return true;
	}
}

  </script>
</head>

<body>
<!----------------Header Starts---------------->
<?php include('header.php')?>
<!----------------Header Ends---------------->
<!----------------Middle Content Starts---------------->
<section id="middle-content">
	<div class="middle-main">
    	<div class="center">
        	<div class="middle-wrapper">
            	 <?php
					$co_query="SELECT id,content_title,content_description FROM tbl_website_content WHERE id='2'";
					$co_res = mysql_query($co_query);
					$co_row = mysql_fetch_array($co_res);
				?>
            	<h3 class="header-title"><?php echo $co_row['content_title'];?></h3>
                <div class="contact-page">
                    <div class="ctn-part-left">
                    
                    	<div style="padding-bottom:10px;text-align:center;color:#54b0d7;font-size:15px;"><?php if(isset($_POST['btnSubmit'])){ echo $msg; }?></div>
                    	<form name="contact_us" onSubmit="return validate()" id="contact_us" action="" method="post" >
                       <ul>
                            <li>
                                <label>Name<div style="color:#FF0000;display: inline-block;">*</div> :</label>
                                <span><input type="text" class="input-ctn-field" name="c_name" id="c_name"></span>
                            </li>
                            <li>
                                <label>Email<div style="color:#FF0000;display: inline-block;">*</div> :</label>
                                <span><input type="text" class="input-ctn-field" name="c_email" id="c_email"></span>
                            </li>
                            <li>
                                <label>Phone<div style="color:#FF0000;display: inline-block;">*</div> :</label>
                                <span><input type="text" class="input-ctn-field numers_only" name="c_phone" id="c_phone" maxlength="10"></span>
                            </li>
                            <li>
                                <label>Message<div style="color:#FF0000;display: inline-block;">*</div> :</label>
                                <span><textarea class="textarea-field" name="c_message" id="c_message"></textarea></span>
                            </li>
                            <?php if(isset($msgc)){
										echo $msgc; 
										}
								?>
                            <li>
                            	<label>&nbsp;</label>
                                <img src="captcha.php?rand=<?php echo rand();?>" id='captchaimg'><a href='javascript: refreshCaptcha();'><image src="images/refresh.png" style="padding-left:10px; height: 32px; width: 32px"/></a>
									
                            </li>
                            <li>
                            	<label>&nbsp;</label>
                                <span><input id="captcha_code" name="captcha_code" type="text" class="input-ctn-field"></span>
                            </li>	
                            <li>
                                <label class="last">&nbsp;</label>
                                <span><button type="submit" class="blue-btn" name="btnSubmit">Send</button></span>
                            </li>
                        </ul> 
                        </form>          	   	 
                    </div>
                    <div class="ctn-part-right">
                    	<div class="address-box">
                        	<?php echo $co_row['content_description'];?>
                        </div>
                        <div class="map-box1">
                            <div id="googleMap" style="width:100%;height:380px;"></div>
                        </div>
                    </div>
                </div>
                
                <!----------------Right Side bar Starts---------------->
                
                <!----------------Right Side bar Ends---------------->
            </div>
        </div>
    </div>
</section>
<!----------------Middle Content Ends---------------->
<!----------------Footer Starts---------------->
<?php include('footer.php');?>
<!----------------Footer Ends---------------->
</body>
<script>
	
</script>
</html>
