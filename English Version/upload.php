<?php
$cfg = array();

#########################################################################
#	Kontaktformular.com         					                                #
#	http://www.kontaktformular.com        						                    #
#	All rights by KnotheMedia.de                                    			#
#-----------------------------------------------------------------------#
#	I-Net: http://www.knothemedia.de                            					#
#########################################################################
// Do NOT remove the copyright notice!

  

  // true = attachments are uploaded to a directory
  // false = attachments are sent as email attachments (standard)
  $cfg['UPLOAD_ACTIVE'] = false;

  //--------------------------------------------------------------------------------------//
  //--------------------------------------------------------------------------------------//
  //--------------------------------------------------------------------------------------//


  // Maximum file size of one file in KB.
  // This option depends on the PHP and server settings.
  $cfg['MAX_FILE_SIZE'] = 1024;

  // Maximum file size of several files in KB. (if more than 1 upload field)
  $cfg['MAX_ATTACHMENT_SIZE'] = 2048;

  // Number of attachment fields
  $cfg['NUM_ATTACHMENT_FIELDS'] = 0;

  // Forbidden file extensions
  // Example: exe|com|pif
  $cfg['BLACKLIST_EXT'] = 'exe|pif|gif|php|htm|html|com|bat';

  // Blocked IPs
  // Example: array('192.168.1.2', '192.168.2.4');
  $cfg['BLACKLIST_IP'] = array();
  
  //--------------------------------------------------------------------------------------//
  //--------------------------------------------------------------------------------------//
  //--------------------------------------------------------------------------------------//
  
  // If you want to upload the attachments to a directory, please complete this information!!

  // You must create an "upload" folder. This requires write permissions. (chmod 777)
  $cfg['UPLOAD_FOLDER'] = 'upload';

  // Path to the form (without / at the end!!)
  $cfg['DOWNLOAD_URL'] = 'https://www.website.com/contactform';

?>