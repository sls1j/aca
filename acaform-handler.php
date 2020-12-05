<?php

// ---------------------------------------------------------------
// Form handler for acaform.html
//
// Author: Andy Niekamp  (for sponsorform.html)
// Date: 9/18/2006
//
// Revised By: Gary Bush  (for sponsorform.shtml)
// Date: 8/15/2010
//
// Revised By: Gary Bush  (modified for acaform.html)
// Date: 1/31/2015 
//
// Revised By: Matt Bowers  (modified for aca-report-form.php)
// Date: 2/03/2015 
//
// Revised By: Gary Bush  (added county to aca-report-form.php)
// Date: 2/06/2015 & 6/15/2015
//
// Revised By: Gary Bush  (added photo question to aca-report-form.php)
// Date: 7/12/2015
//
// ---------------------------------------------------------------

$server = 'yes';

$host = getenv("HTTP_HOST");

if ($host == "localhost") {$server = 'no';}

$today = date("F j, Y, g:i a");

$name=substr(strip_tags($_POST['__aca_form__name']),0,50);
$email=substr(strip_tags($_POST['__aca_form__email']),0,50);
$street=substr(strip_tags($_POST['__aca_form__street']),0,50);
$city=substr(strip_tags($_POST['__aca_form__city']),0,30);
$state=substr(strip_tags($_POST['__aca_form__state']),0,10);
$zip=substr(strip_tags($_POST['__aca_form__zip']),0,12);
$phone=substr(strip_tags($_POST['__aca_form__phone']),0,50);
$idate=substr(strip_tags($_POST['__aca_form__idate']),0,50);
$icave=substr(strip_tags($_POST['__aca_form__icave']),0,50);
$icounty=substr(strip_tags($_POST['__aca_form__icounty']),0,50);
$istate=substr(strip_tags($_POST['__aca_form__istate']),0,50);
$icountry=substr(strip_tags($_POST['__aca_form__icountry']),0,50);
$itype=substr(strip_tags($_POST['__aca_form__itype']),0,50);
$iresult=substr(strip_tags($_POST['__aca_form__iresult']),0,76);
$upresent=substr(strip_tags($_POST['__aca_form__upresent']),0,8);
$urescuer=substr(strip_tags($_POST['__aca_form__urescuer']),0,8);
$urole=substr(strip_tags($_POST['__aca_form__urole']),0,250);
$uphotos=substr(strip_tags($_POST['__aca_form__uphotos']),0,8);
$description=substr(strip_tags($_POST['__aca_form__description']),0,8300);
$swizzel=substr(strip_tags($_POST['__aca_form__swizzel']),0,20);

if ($swizzel != 'it clicked'){
	print "<b>Error: Robot submit detected</b></p>";
	print "Dropping submission";
	exit(0);
}

if ($name == '' or $email == '' or $idate == '' or $istate == '' or $icounty == '' or $icountry == '' or $itype == '' or $iresult == '') {

  print "<b>Error: Incomplete Form Entry</b><p>";
  print "Please enter the required fields.<p>";
  print "Please enter your Name, your Email, Date of Incident, County of Incident, State of Incident, Country of Incident, Type, and Result.<p>";
  print "Go back and make the required entries.";
  exit(0);

}

    if (valid_email($email)) {} else {

  print "<b>Error: Email Address Not Valid</b><p>";
  print "Your email address does not appear to be a valid email address.<p>";
  print "Go back and make the required entries.";
  exit(0);

}


$body = "";
$body .= "* * * ACA Incident Report * * *\n\n";
$body .= "Incident Date: $idate\n";
$body .= "    Cave Name: $icave\n";
$body .= "       County: $icounty\n";
$body .= "        State: $istate\n";
$body .= "      Country: $icountry\n\n";
$body .= "Date Reported: $today\n";
$body .= "  Reported By: $name\n";
$body .= "        Email: $email\n";
$body .= "      Address: $street\n";
$body .= " City, ST Zip: $city, $state, $zip\n";
$body .= "        Phone: $phone\n\n";
$body .= "  Incident Type: $itype\n";
$body .= "Incident Result: $iresult\n\n";
$body .= "Present at accident: $upresent\n";
$body .= " Involved in rescue: $urescuer\n";
$body .= "     Role in rescue: $urole\n";
$body .= "Photos taken at accident: $uphotos\n\n";
$body .= "Description of accident/incident: \n";
$body .= "$description\n";
$body .= "--\n";

if ($server == 'yes') {

   // -----------------------------------------
   // send an email
   // -----------------------------------------

   $subject = "[ACA] Incident Report - $name";
   $sendto  = "acareport@caves.org"; "caver07@wgbush.com";

   $headers  = "From: aca@caves.org\n";
   $headers .= "Bcc: $email\n";
   $headers .= "X-Mailer:PHP/";

   $rc = mail($sendto, $subject, $body, $headers);

}

// ----------------------------
// Write txt log file
// ----------------------------

   $file = fopen ("acaform_log.txt", "a");

   if (!$file) {
      print "<p>Unable to open file: $file for append.\n";
      print "Please contact <A HREF='mailto:asstwebmaster@caves.org'>asstwebmaster@caves.org</A>";
      exit;
   }

   flock ($file,2);    // exclusive write lock

   $line = "$body\n";
   $line .="Sent To: $sendto\n";
   $line .="Subject: $subject\n";
   $line .="$headers\n";
   $line .="---------------------------------------------------\n\n";

   fputs ($file,"$line\r\n"); // add crlf

   flock ($file,3);    // release lock

   fclose($file);


?>

<html>

		<head>
			<title></title>
			<STYLE TYPE="text/css">
			<!--
			A.main:link	{ font-weight: normal; font-style: italic; text-decoration: none; color: #506537; }
			A.main:visited	{ font-weight: normal; font-style: italic; text-decoration: none; color: #506537; }
			A.main:hover	{ font-weight: normal; font-style: italic; text-decoration: underline; color: #80A259; }

			a.nav:link	{ font-weight: normal; text-decoration: none; color: #FFFFFF; }
			a.nav:visited	{ font-weight: normal; text-decoration: none; color: #FFFFFF; }
			a.nav:hover 	{ color: #3F4F2B; }
//			a.nav 		{ display:block; margin:0; padding:0; border:0px none; }

			a.nav2:link	{ font-weight: normal; text-decoration: none; color: #FFFFFF; }
			a.nav2:visited	{ font-weight: normal; text-decoration: none; color: #FFFFFF; }
			a.nav2:hover 	{ color: #BBD68D; }
//			a.nav2 		{ display:block; margin:0; padding:0; border:0px none; }

			p 		{font-family: Helvetica,Verdana,Times,serif; font-size: 11pt; }
			.title 		{font-family: Helvetica,Verdana,Times,serif; font-size: 15pt; }

			.windowbg2	{ background-color: #FFFFFF; font-size: 12px; font-family: Helvetica,Verdana,Times,serif; color: #000000; }
			.titlebg	{ background-color: #FFFFFF; font-family: Helvetica,Verdana,Times,serif; color: #000000; }

			hr 		{ border: 0px none; color: #80A259; background-color: #506537; height: 1px }
			hr.small	{ border: 0px none; color: #80A259; background-color: #506537; height: 1px }

			-->
			</STYLE>

		</head>

	<body background="/images/nssback.jpg" bgcolor="#CDCDCD">
	<basefont size="3" face="Helvetica,Verdana,Times,serif" />
	<table width="984" align="center" border="0" bgcolor="#506537" cellpadding="2" cellspacing="0">
	<tr>
		<td width="100%" cellpadding="0" cellspacing="0" align="center">
                <img src="/images/NSS_980x136_Banner.jpg"></td>
	</tr>
	<tr><td>
		<table width="980" bgcolor="#FFFFFF" align="center" cellpadding="0" cellspacing="0">
		<tr><td>
			<table width="980" cellpadding="0" cellspacing="0">
			<tr bgcolor="#FFFFFF" cellpadding="0" cellspacing="0" align="center">
				<td width="980">
					<font class="title" color="#506537"><b>Success! Your Report Has Been Submitted</b><br />A copy of this form will be sent to your email.</font><hr/>
							<p>&nbsp;</p>
			</td></tr>
			<tr bgcolor="#FFFFFF" cellpadding="4" cellspacing="0"><td width="980">
				<pre><?= $body ?></pre>
			</td></tr>
			</table>
			<hr/>
			</td>
		</tr>
		<tr>
			<td align="center">
			<p><br /><a href=".">Return to ACA Main Page</a></p>
			<p>&nbsp; </p>
			</td>
		</tr>
		</table>
	</td></tr>
	</table>
</body>
</html>
<?php
// -----------------------------------------
// email validation routine
// -----------------------------------------

function valid_email($email) {
    if (ereg("^([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[@]([0-9,a-z,A-Z]+)([.,_,-]([0-9,a-z,A-Z]+))*[.]([0-9,a-z,A-Z]){2}([0-9,a-z,A-Z])*$",$email)) {
        return TRUE;
    } else {
        return FALSE;
    }
}

?>