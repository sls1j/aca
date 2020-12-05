<?php
  // NSS American Caving Accidents
  // NSS_aca_header.php    31-January-2015
  // Written by Matt Bowers / Third Media / NSS 25863FE
  //
  // Inserts site headers.
  //
  // Please don't redistribute my code. The NSS has the right to use this for any internal
  // purpose, but the code may not be sold or used outside of the society. Some of this is
  // library material that I've written over years of my professional life. - Matt B./25863


// Page variables
//
// This defines how long the search engines should cache this content before refreshing
$date = date_add(date_create(),date_interval_create_from_date_string('21 days'));
$Content_Expires = date_format($date,'D, d M Y H:i:s O');   // Must be RFC1123 format

// This is the image the social media sites (Facebook, etc) will use when someone 'likes' the page...
$Social_Image = 'http://caves.org/pub/aca/_common/gfx/aca_cover2007_300.jpg';


?>
<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
<!-- National Speleological Society - American Caving Accidents            -->
<!-- www.caves.org                                                         -->
<!-- All content Copyright (C) <?php echo date('Y') ?>, National Speleological Society        -->
<!-- 6001 Pulaski Pike NW,  Huntsville, AL 35810-1122 USA   (256) 852-1300 -->
<!-- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -->
<meta name="resource-type" content="document" />
<meta name="author" content="National Speleological Society" />
<meta name="description" content="American Caving Accidents is the journal of record for accident and safety incident reports from the North American caving community, published by the National Speleological Society. Besides providing an archival record of caving accidents and safety incidents, the ACA serves as an educational resource for cavers and cave rescuers." />
<meta name="keywords" content="cave accidents; caving; American Caving Accidents; National Speleological Society" />
<meta http-equiv="cache-control" content="max-age=0" />
<meta http-equiv="cache-control" content="no-cache" />
<meta name="expires" content="<?php echo $Content_Expires; ?>" />
<meta name="MSSmartTagsPreventParsing" content="TRUE" />
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<meta http-equiv="imagetoolbar" content="no" />
<meta http-equiv="PICS-Label" content='(PICS-1.1 "http://vancouver-webpages.com/VWP1.0/" l gen true comment "VWP1.0" by "nss75th@caves.org" on "2014.04.21T17:241700" for "www.caves.org" r (Gam 0 V 0 Env -2 SF -1 Com 1 Can 0 Edu -2 S 0 P 0 Tol 0 MC -1 ))' />
<link rel="icon" type="image/png" href="http://<?php echo $NSS_domain; ?>/favicon.ico" />
<link rel="image_src" href="<?php echo $Social_Image; ?>" />
<meta name="ICBM" content="34.7953035;-86.6202505" />
<meta name="geo.position" content="34.7953035;-86.6202505" />
<meta name="geo.placename" content="Huntsville, Alabama, USA" />
<meta name="geo.region" content="us-al" />
<meta itemprop="name" content="American Caving Accidents - National Speleological Society">
<meta itemprop="description" content="American Caving Accidents is the journal of record for accident and safety incident reports from the North American caving community, published by the National Speleological Society. Besides providing an archival record of caving accidents and safety incidents, the ACA serves as an educational resource for cavers and cave rescuers." />
<meta itemprop="image" content="<?php echo $Social_Image; ?>">
<meta property="og:type" content="website" />
<meta property="og:title" content="American Caving Accidents - National Speleological Society" />
<meta property="og:url" content="<?php echo NSS_This_URL; ?>" />
<meta property="og:image" content="<?php echo $Social_Image; ?>" />
<meta property="og:description" content="American Caving Accidents is the journal of record for accident and safety incident reports from the North American caving community, published by the National Speleological Society. Besides providing an archival record of caving accidents and safety incidents, the ACA serves as an educational resource for cavers and cave rescuers." />
<meta property="og:locale" content="en_US" />
<meta property="og:site_name" content="NSS American Caving Accidents" />
<link rel="stylesheet" type="text/css" href="http://caves.org/pub/aca/_common/css/nss_aca.css" />
