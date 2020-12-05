<?php
// File: caves.org/pub/aca/aca-report-form.php
// Scope: New ACA accident report form
//
// Modification history:
//	31-January-2015/mwb: New data processing procedure. Replaced CGI script
//	7-February-2015/wgb: Added County field and modified drop-down list for Type of Incident.
//	9-February-2015/wgb: Added drop-down list for Country.
//	   12-July-2015/wgb: Added question with radio buttons about photos.
// 
// Please don't redistribute my code. The NSS has the right to use this for any internal
// purpose, but the code may not be sold or used outside of the society. Some of this is
// library material that I've written over years of my professional life. - Matt B./25863
//

// You are advised to comment out the next two lines for a production site. These simply give you better error
// reporting while building & debugging a page, but you don't necessarily want this turned on once the page
// is launched to the general public.
//
ini_set('display_errors', true);
error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);


// Session management...
ini_set("session.name", $NSS_Content_Area);        // Changes the Apache/PHP session name so we don't interfere with another section of the domain
ini_set("session.gc_maxlifetime", "7200");         // Extends the session lifetime to 2 hours. That should be more than enough to fill out an accident form.
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY'] > 7200)) {
    // If the last request was more than 2 hours ago (7200 seconds), we no longer trust this session - its stale.
    session_unset();     // Unset $_SESSION variable for the run-time 
    session_destroy();   // Destroy any session data in storage
	}
session_start();
$_SESSION['LAST_ACTIVITY'] = time(); // update last activity time stamp


// GUID token...
// This GUID is like a checksum to prevent people from hacking in by spoofing a session ID. It's stored in
// the session data & frequently passed as a hidden form variable. While the client has access to the form
// data, they have no access to the session data. So, if a hacker moved the HTML form to another domain, it
// would fail on submit because the GUIDs would not match. (The function is defined at the bottom of the page.)
//
if (!isset($_SESSION['NSS_Audit_ID'])) {
    $_SESSION['NSS_Audit_ID'] = fnGuid();
}


// Page variables
$NSS_domain = 'caves.org';				// Root domain of this website
$NSS_root_path = '../../';				// Path to site root
$NSS_Content_Area = 'NssCaveAccidents';	// This is just used to identify a specific content area on caves.org
$NSS_ACA_Editor = 'aca@caves.org';		// This is the person that receives the emails (separate multiple addresses with a semi-colon)
$NSS_Sys_Admin = 'matt.bowers@caves.org';	// This person gets an email if something goes drastically wrong (like hackers)
$NSS_This_URL = fnCurPageURL();			// The complete URL of whatever page we're on
if (!isset($_SESSION['aca_report']['description'])) {	// This loads the blank report template
    $_SESSION['aca_report']['description'] = file_get_contents('_common/templates/blank_report.txt', false);  //
}


?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ACA Accident Report Form</title>
<?php
require_once($NSS_root_path.'pub/aca/_common/bin/NSS_aca_header.php');

require_once($NSS_root_path.'includes/top.html');
?>
	<style>
		body {
			font-family: 'Open Sans', sans-serif;
			font-size: 14;
		}
	</style>
</head>
<body>

<?php
require_once($NSS_root_path.'includes/header.html');
?>
<h1 align="center">National Speleological Society<br />
<small>Caving Accident/Incident Report Form</small></h1>
<table width="100%" cellpadding="4" cellspacing="0">
	<tr bgcolor="#FFFFFF">
		<td colspan=2>
		<hr /><p>This form collects information to be included in the report summaries published in
		  American Caving Accidents.<br />The completed form will be emailed to the Editor when you click
		  the Submit button at the bottom. <b>A copy will also be sent<br />to your email.</b> If you have a question regarding this form <a
		  href="contact.shtml">contact the Editor</a>.</p>
		  <p><span class="strong">Please note</span>: Items marked in <span class="required strong">red</span> are 
		  <span class="required strong">required</span>. If you do not fill them in, your browser will report an
		  error when you submit the form.  Also, to conserve server resources, you have two hours to complete this form.  After two hours the form will close and you'll have to start over.</p>
		<hr />
		<form method="POST" action="acaform-handler.php">
		<div align="left">
		<table border="0" cellpadding="10" style="border-collapse: collapse" id="table1" width="80%">
			<tr>
				<td align="right" colspan="6"><div align="left"><p>How can we contact you if there are questions about this report?</div></td>
 			</tr>
	                <tr>
				<td align="right"><span class="required">Your Name: </span></td>
				<td colspan="5"><input type="text" name="__aca_form__name" size="40" maxlength="40" value="<?php echo $_SESSION['aca_report']['name']; ?>"><span class="required"> &nbsp; (required)</span></td>
			</tr>
			<tr>
				<td align="right"><span class="required">Email Address: </span></td>
				<td colspan="5"><input type="text" name="__aca_form__email" size="40" maxlength="40" value="<?php echo $_SESSION['aca_report']['email']; ?>"><span class="required"> &nbsp; (required)</span></td>
			</tr>
			<tr>
				<td align="right">Street Address: </td>
				<td colspan="5"><input type="text" name="__aca_form__street" size="40" maxlength="64" value="<?php echo $_SESSION['aca_report']['street']; ?>"></td>
			</tr>
			<tr>
				<td align="right">City: </td>
				<td><input type="text" name="__aca_form__city" size="20" maxlength="32" value="<?php echo $_SESSION['aca_report']['city']; ?>"></td>
				<td align="right">State: </td>
				<td><input type="text" name="__aca_form__state" size="2" maxlength="2" value="<?php echo $_SESSION['aca_report']['state']; ?>"></td>
				<td align="right">ZIP code: </td>
				<td><input type="text" name="__aca_form__zip" size="10" maxlength="10" value="<?php echo $_SESSION['aca_report']['zip']; ?>"></td>
			</tr>
			<tr>
				<td align="right">Phone Number: </td>
				<td colspan="5"><input type="text" name="__aca_form__phone" size="40" maxlength="40" value="<?php echo $_SESSION['aca_report']['phone']; ?>"></td>
			</tr>
			<tr>
				<td colspan="5"><br />When and where did the incident take place? </td>
			</tr>
			<tr>
				<td align="right"><span class="required">Date of Incident: </span></td>
				<td colspan="5"><input type="text" name="__aca_form__idate" size="40" maxlength="40" value="<?php echo $_SESSION['aca_report']['idate']; ?>"><span class="required"> &nbsp; (required)</span></td>
			</tr>
			<tr>
				<td align="right">Name of the cave: </td>
				<td colspan="5"><input type="text" name="__aca_form__icave" size="40" maxlength="64" value="<?php echo $_SESSION['aca_report']['icave']; ?>"></td>
			</tr>
			<tr>
				<td align="right"><span class="required">County of Cave: </span></td>
				<td colspan="5"><input type="text" name="__aca_form__icounty" size="40" maxlength="40" value="<?php echo $_SESSION['aca_report']['icounty']; ?>"><span class="required"> &nbsp; (required)</span></td>
			</tr>
			<tr>
				<td align="right"><span class="required">State or Province: </span></td>
				<td colspan="5"><input type="text" name="__aca_form__istate" size="40" maxlength="40" value="<?php echo $_SESSION['aca_report']['istate']; ?>"><span class="required"> &nbsp; (required)</span></td>
			</tr>
			<tr>
				<td align="right"><span class="required">Country: </span></td>
				<td colspan="5"><select	name="__aca_form__icountry" size="1" style="font-weight: normal; font-style: normal">
				<option value="United States">United States</option>
				<option value="American Samoa">American Samoa</option>
				<option value="Antigua and Barbuda">Antigua and Barbuda</option>
				<option value="Bahamas">Bahamas</option>
				<option value="Barbados">Barbados</option>
				<option value="Belize">Belize</option>
				<option value="Canada">Canada</option>
				<option value="Costa Rica">Costa Rica</option>
				<option value="Cuba">Cuba</option>
				<option value="Dominica">Dominica</option>
				<option value="Dominican Republic">Dominican Republic</option>
				<option value="El Salvador">El Salvador</option>
				<option value="Grenada">Grenada</option>
				<option value="Guam">Guam</option>
				<option value="Guatemala">Guatemala</option>
				<option value="Haiti">Haiti</option>
				<option value="Honduras">Honduras</option>
				<option value="Jamaica">Jamaica</option>
				<option value="Mexico">Mexico</option>
				<option value="Nicaragua">Nicaragua</option>
				<option value="Northern Marianas">Northern Marianas</option>
				<option value="Panama">Panama</option>
				<option value="Puerto Rico">Puerto Rico</option>
				<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option>
				<option value="Saint Lucia">Saint Lucia</option>
				<option value="Saint Vincent and the Grenadines">Saint Vincent and the Grenadines</option>
				<option value="Trinidad and Tobago">Trinidad and Tobago</option>
				<option value="Virgin Islands">Virgin Islands</option>
				</select><span class="required"> &nbsp; (required)</span></td>
			</tr>
		</table>
		</div>
		<p>How would you describe the incident type and results? </p>
		<div class="l_margin36">
			<span class="required">Type of Incident:</span><br />
			<select	name="__aca_form__itype" size="1" style="font-weight: normal; font-style: normal">
			<option value="">choose one ...</option>
			<option value="Caver fall">Caver fall</option>
			<option value="Rockfall">Rockfall</option>
			<option value="Trapped/Stranded">Trapped/Stranded</option>
			<option value="Lost">Lost</option>
			<option value="Equipment Problem">Equipment Problem</option>
			<option value="Difficulty on rope">Difficulty on rope</option>
			<option value="Other">Other</option>
			<option value="Hypothermia">Hypothermia</option>
			<option value="Exhaustion">Exhaustion</option>
			<option value="Flooding/Flood Entrapment">Flooding/Flood Entrapment</option>
			<option value="Bad Air">Bad Air</option>
			<option value="Acetylene">Acetylene</option>
			<option value="Illness">Illness</option>
			<option value="Stuck">Stuck</option>
			<option value="Drowning">Drowning</option>
			<option value="Diving incident">Diving incident</option>
			</select><span class="required"> &nbsp; (required)</span>
<br /><br />
			<span class="required">Result of Incident:</span><br />
			<select name="__aca_form__iresult" size="1">
			<option value="">choose one ...</option>
			<option value="Injury and Aid">Injury and Aid (someone was hurt and required outside assistance)</option>
			<option value="Injury, No Aid">Injury, No Aid (someone was hurt, but no outside assistance was required)</option>
			<option value="Aid, No Injury">Aid, No Injury (no one hurt, but someone	needed outside help)</option>
			<option value="No Consequence">No consequence (no one hurt, problem solved, but worth noting)</option>
			<option value="Fatality">Fatality (one or more people died)</option>
			</select><span class="required"> &nbsp; (required)</span></p>
		</div>

<p>Were you present when the accident/incident occurred?
<input type="radio" name="__aca_form__upresent" value="yes">Yes
<input type="radio" name="__aca_form__upresent" value="no">No </p>

<p>Were you involved in the response and/or rescue?
<input type="radio" name="__aca_form__urescuer" value="yes">Yes
<input type="radio" name="__aca_form__urescuer" value="no">No
<br />
&nbsp; If yes, what was your role?
		<div class="l_margin36">
			<textarea name="__aca_form__urole" rows="2" cols="75" wrap="hard" maxlength="1024"><?php echo $_SESSION['aca_report']['urole']; ?></textarea>
		</div></p>

<p>Were any photos taken during this incident?
<input type="radio" name="__aca_form__uphotos" value="yes">Yes
<input type="radio" name="__aca_form__uphotos" value="no">No </p>

		<p>Please describe the accident or incident as completely as possible. You can fill in the
		 blanks or describe it in your own way, but try to include all the requested information.
		 This part of the form can be edited or arranged as necessary. Use as much room as you
		 need to be thorough, but please don't exceed 8,000 characters (about 4 times the size of the box).
		 <br />A report in the <a href="http://www.caves.org/pub/aca/style.shtml">style of American
		 Caving Accidents</a> is ideal. </p>

		<div class="l_margin36">
		<textarea name="__aca_form__description" rows="30" cols="75" wrap="hard" maxlength="8192">
<?php echo $_SESSION['aca_report']['description']; ?>
		</textarea> </div>
		<div align="center"><center><p>&nbsp; <br />
		<input type="hidden" name="_Audit_IP" value="<?php echo $_SERVER["REMOTE_ADDR"]; ?>">		
		<input type="hidden" name="NSS_Audit_ID" Value="<?php echo $_SESSION['NSS_Audit_ID']; ?>">
		<input id="swizzel" type="hidden" name="__aca_form__swizzel" value="junk-n933"/>
		<span>Click Circle to prove you're not a robot </span>
		<canvas id="circle" height="32" width="32" style="cursor: pointer" >			
		</canvas>
		<script>
			var needHelp = true;
			console.log("drawing circle");
			var canvas = document.getElementById("circle");
			var rng = function(num){
				return Math.floor(Math.random() * num ) + 1;
			}
			canvas.style.marginTop = (rng(20) * 5) + "px";
			canvas.style.marginLeft = (rng(20) * 5) + "px";
			canvas.addEventListener('click', circleClick);
			var ctx = canvas.getContext("2d");
			ctx.beginPath();
			ctx.arc(16,16,12,0,2*Math.PI);
			ctx.strokeStyle = "red";
			ctx.lineWidth = 4;
			ctx.stroke();

			function circleClick(evt){				
				if ( needHelp ){
					needHelp = false;					
					var sb = document.createElement("input");
					sb.type = "submit";
					sb.value = "Submit Report";
					sb.name = sb.type;

					var swizzel = document.getElementById("swizzel");
					swizzel.value = "it clicked";

					var sbc = document.getElementById("subContainer");
					sbc.appendChild(sb);
					
					ctx.clearRect(0,0, canvas.width, canvas.height);
					ctx.beginPath();
					ctx.arc(16,16,12,0,2*Math.PI);
					ctx.strokeStyle = "green";
					ctx.lineWidth = 4;
					ctx.moveTo(0,0);
					ctx.lineTo(32,32);
					ctx.moveTo(32,0);
					ctx.lineTo(0,32);
					ctx.stroke();
				}
			}
		</script>
		<span id="subContainer"></span>
		&nbsp; &nbsp; 		
		<input type="reset" value="Erase Form"></p>
		</center></div>
                </form>
		</td>
	</tr>
</table>
<hr align="center">
<div align="center"><center><p>
<b>If you do not receive a delivery confirmation for your report within 7 days,
 please contact the editor at <?php echo $NSS_ACA_Editor; ?>.</b>
<br />
</center></div>
<p><div align="center"><center><address>
All reports become the property <br />
of the National Speleological Society <br />
and may be published in American Caving Accidents. 
</address></center></div></p>
<hr align="center">
<p><div align="center"><center><address>
Copyright &copy; 2007-<?php echo date('Y') ?> National Speleological Society 
</address></center></div></p>

<?php
require_once($NSS_root_path.'includes/footer.html');
?>
</body>
</html>
<?php

// Function declarations

function fnGuid(){
    // Make a globally unique identifier
    if (function_exists('com_create_guid')){
        return com_create_guid();
    } else {
        mt_srand((double)microtime()*10000); //optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
                .substr($charid, 0, 8).$hyphen
                .substr($charid, 8, 4).$hyphen
                .substr($charid,12, 4).$hyphen
                .substr($charid,16, 4).$hyphen
                .substr($charid,20,12)
                .chr(125);// "}"
        return $uuid;
    }
}


function fnCurPageURL() {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) and ($_SERVER["HTTPS"] == "on")) {$pageURL .= "s";}
    $pageURL .= "://";
    if ($_SERVER["SERVER_PORT"] != "80") {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}


?>