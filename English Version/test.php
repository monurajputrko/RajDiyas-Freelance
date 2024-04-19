<?php
session_start();
error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set('Europe/Berlin');
header('Content-type: text/html; charset=utf-8');


#########################################################################
#	Kontaktformular.com         					                                #
#	http://www.kontaktformular.com        						                    #
#	All rights by KnotheMedia.de                                    			#
#-----------------------------------------------------------------------#
#	I-Net: http://www.knothemedia.de                            					#
#########################################################################
// Do NOT remove the copyright notice!


  $script_root = substr(__FILE__, 0,
                        strrpos(__FILE__,
                                DIRECTORY_SEPARATOR)
                       ).DIRECTORY_SEPARATOR;



$remote = getenv("REMOTE_ADDR");

function encrypt($string, $key) {
$result = '';
for($i=0; $i<strlen($string); $i++) {
   $char = substr($string, $i, 1);
   $keychar = substr($key, ($i % strlen($key))-1, 1);
   $char = chr(ord($char)+ord($keychar));
   $result.=$char;
}
return base64_encode($result);
}
$sicherheits_eingabe = encrypt($_POST["sicherheitscode"], "8h384ls94");
$sicherheits_eingabe = str_replace("=", "", $sicherheits_eingabe);



if ($_POST['delete'])
{
unset($_POST);
}

if ($_POST["kf-km1212"]) {

  $email      = $_POST["email"];

   $date = date("d.m.Y | H:i");
   $ip = $_SERVER['REMOTE_ADDR']; 
   $UserAgent = $_SERVER["HTTP_USER_AGENT"];
   $host = getHostByAddr($remote);

$email = stripslashes($email);

 
if (!preg_match("/^[0-9a-zA-ZÄÜÖ_.-]+@[0-9a-z.-]+\.[a-z]{2,6}$/", $email)) {
   $fehler['email'] = "<font color=#cc3333>Please enter your <strong>email address</strong>.\n<br /></font>";
}

    if (!isset($fehler) || count($fehler) == 0) {
      $error             = false;
      $errorMessage      = '';
      $uploadErrors      = array();
      $uploadedFiles     = array();
      $totalUploadSize   = 0;

      if ($cfg['UPLOAD_ACTIVE'] && in_array($_SERVER['REMOTE_ADDR'], $cfg['BLACKLIST_IP']) === true) {
          $error = true;
          $fehler['upload'] = '<font color=#990000>Sie haben keine Erlaubnis Dateien hochzuladen.<br /></font>';
      }
$dsmsg = "". $_SERVER['HTTP_REFERER'] ."";
      if (!$error) {
          for ($i=0; $i < $cfg['NUM_ATTACHMENT_FIELDS']; $i++) {
              if ($_FILES['f']['error'][$i] == UPLOAD_ERR_NO_FILE) {
                  continue;
              }

              $extension = explode('.', $_FILES['f']['name'][$i]);
              $extension = strtolower($extension[count($extension)-1]);
              $totalUploadSize += $_FILES['f']['size'][$i];

              if ($_FILES['f']['error'][$i] != UPLOAD_ERR_OK) {
                  $uploadErrors[$j]['name'] = $_FILES['f']['name'][$i];
                  switch ($_FILES['f']['error'][$i]) {
                      case UPLOAD_ERR_INI_SIZE :
                          $uploadErrors[$j]['error'] = 'Die Datei ist zu groß (PHP-Ini Direktive).';
                      break;
                      case UPLOAD_ERR_FORM_SIZE :
                          $uploadErrors[$j]['error'] = 'Die Datei ist zu groß (MAX_FILE_SIZE in HTML-Formular).';
                      break;
                      case UPLOAD_ERR_PARTIAL :
						  if ($cfg['UPLOAD_ACTIVE']) {
                          	  $uploadErrors[$j]['error'] = 'Die Datei wurde nur teilweise hochgeladen.';
						  } else {
							  $uploadErrors[$j]['error'] = 'Die Datei wurde nur teilweise versendet.';
					  	  }
                      break;
                      case UPLOAD_ERR_NO_TMP_DIR :
                          $uploadErrors[$j]['error'] = 'Es wurde kein temporärer Ordner gefunden.';
                      break;
                      case UPLOAD_ERR_CANT_WRITE :
                          $uploadErrors[$j]['error'] = 'Fehler beim Speichern der Datei.';
                      break;
                      case UPLOAD_ERR_EXTENSION  :
                          $uploadErrors[$j]['error'] = 'Unbekannter Fehler durch eine Erweiterung.';
                      break;
                      default :
						  if ($cfg['UPLOAD_ACTIVE']) {
                          	  $uploadErrors[$j]['error'] = 'Unbekannter Fehler beim Hochladen.';
						  } else {
							  $uploadErrors[$j]['error'] = 'Unbekannter Fehler beim Versenden des Email-Attachments.';
						  }
                  }

                  $j++;
                  $error = true;
              }
              else if ($totalUploadSize > $cfg['MAX_ATTACHMENT_SIZE']*1024) {
                  $uploadErrors[$j]['name'] = $_FILES['f']['name'][$i];
                  $uploadErrors[$j]['error'] = 'Maximaler Upload erreicht ('.$cfg['MAX_ATTACHMENT_SIZE'].' KB).';
                  $j++;
                  $error = true;
              }
              else if ($_FILES['f']['size'][$i] > $cfg['MAX_FILE_SIZE']*1024) {
                  $uploadErrors[$j]['name'] = $_FILES['f']['name'][$i];
                  $uploadErrors[$j]['error'] = 'Die Datei ist zu groß (max. '.$cfg['MAX_FILE_SIZE'].' KB).';
                  $j++;
                  $error = true;
              }
              else if (!empty($cfg['BLACKLIST_EXT']) && strpos($cfg['BLACKLIST_EXT'], $extension) !== false) {
                  $uploadErrors[$j]['name'] = $_FILES['f']['name'][$i];
                  $uploadErrors[$j]['error'] = 'Die Dateiendung ist nicht erlaubt.';
                  $j++;
                  $error = true;
              }
              else if (preg_match("=^[\\:*?<>|/]+$=", $_FILES['f']['name'][$i])) {
                  $uploadErrors[$j]['name'] = $_FILES['f']['name'][$i];
                  $uploadErrors[$j]['error'] = 'Ungültige Zeichen im Dateinamen (\/:*?<>|).';
                  $j++;
                  $error = true;
              }
              else if ($cfg['UPLOAD_ACTIVE'] && file_exists($cfg['UPLOAD_FOLDER'].'/'.$_FILES['f']['name'][$i])) {
                  $uploadErrors[$j]['name'] = $_FILES['f']['name'][$i];
                  $uploadErrors[$j]['error'] = 'Die Datei existiert bereits.';
                  $j++;
                  $error = true;
              }
              else {
				  if ($cfg['UPLOAD_ACTIVE']) {
                     move_uploaded_file($_FILES['f']['tmp_name'][$i], $cfg['UPLOAD_FOLDER'].'/'.$_FILES['f']['name'][$i]);	
				  }
                  $uploadedFiles[] = $_FILES['f']['name'][$i];
              }
          }
      }

      if ($error) {
          $errorMessage = 'Es sind folgende Fehler beim Versenden des Kontaktformulars aufgetreten:'."\n";
          if (count($uploadErrors) > 0) {
              foreach ($uploadErrors as $err) {
                  $tmp .= '<strong>'.$err['name']."</strong><br/>\n- ".$err['error']."<br/><br/>\n";
              }
              $tmp = "<br/><br/>\n".$tmp;
          }
          $errorMessage .= $tmp.'';
          $fehler['upload'] = $errorMessage;
      }
  }



   if (!isset($fehler))
   {

   $recipient = "xxtestxx43@gmx.de";
   $betreff = "";
	//$mailheaders = "From: \"".stripslashes($_POST["vorname"])." ".stripslashes($_POST["name"])."\" <".$_POST["email"].">\n";
	//$mailheaders .= "Reply-To: <".$_POST["email"].">\n";
	//$mailheaders .= "X-Mailer: PHP/" . phpversion() . "\n";
	$mailheader_betreff = "=?UTF-8?B?".base64_encode($betreff)."?=";
	$mailheaders   = array();
	$mailheaders[] = "MIME-Version: 1.0";
	$mailheaders[] = "Content-type: text/plain; charset=utf-8";
	$mailheaders[] = "From: \"".stripslashes($_POST["name"])."\" <".$_POST["email"].">\n";

	
 $msg .= "$dsmsg";
   


   
  

   


	if (!$cfg['UPLOAD_ACTIVE'] && count($uploadedFiles) > 0) {
		$attachments = array();
		for ($i = 0; $i < $cfg['NUM_ATTACHMENT_FIELDS']; $i++) {
		   	if ($_FILES['f']['name'][$i] == UPLOAD_ERR_NO_FILE) {
				continue;
			}
			$attachments[] = $_FILES['f']['tmp_name'][$i];
		}
		$boundary = md5(uniqid(rand(), true));
		$mailheaders .= "MIME-Version: 1.0\n";
		$mailheaders .= "Content-Transfer-Encoding: 8bit\n";
		$mailheaders .= "Content-Type: multipart/mixed;\n\tboundary=\"".$boundary."\"\n";
		$mailheaders .= "\n--".$boundary."\n";
		$mailheaders .= "Content-Type: text/plain;\n\tcharset=\"iso-8859-1\"\n";
		$mailheaders .= "Content-Transfer-Encoding: 8bit\n";
		for ($i = 0; $i < count($uploadedFiles); $i++) {
			$file = fopen($attachments[$i],"r");
			$content = fread($file,filesize($attachments[$i]));
			fclose($file);
			$encodedfile = chunk_split(base64_encode($content));
			$msg .= "\n\n--".$boundary."\n";
			$msg .= "Content-Type: application/octet-stream;\n\tname=\"".$uploadedFiles[$i]."\"\n";
			$msg .= "Content-Transfer-Encoding: base64\n";
			$msg .= "Content-Disposition: attachment;\n\tfilename=\"".$uploadedFiles[$i]."\"\n\n";
			$msg .= $encodedfile."\n\n";
		}
		$msg .= "\n\n--".$boundary."--";
	}


   $msg = strip_tags ($msg);
$ihrname = "no-reply@kontaktformular.com";
$ihrname2 = "no-reply@kontaktformular.com";
	$dsubject = "Testmail"; 
	//$dmailheaders = "From: ".$ihrname." <".$recipient.">\n";
	//$dmailheaders .= "Reply-To: <".$recipient.">\n";
	$dmailheader_dsubject = "=?UTF-8?B?".base64_encode($dsubject)."?=";
   	$dmailheaders   = array();
	$dmailheaders[] = "MIME-Version: 1.0";
	$dmailheaders[] = "Content-type: text/plain; charset=utf-8";
	$dmailheaders[] = "From: =?UTF-8?B?".base64_encode($ihrname)."?= <".$ihrname2.">";
	$dmailheaders[] = "Reply-To: <".$ihrname2.">";
	$dmailheaders[] = "Subject: ".$dmailheader_dsubject;
	$dmailheaders[] = "X-Mailer: PHP/".phpversion();		

   
   
   

$dmsg  = "If you can read this email, the form has been successfully tested on your server.\n\n";
   $dmsg .= "You can now delete the test.php file and use the contact form [contact.php]. ";
$dmsg .= "If you have any questions or problems, we are always at your disposal with our free installation service. \n";
   $dmsg = strip_tags ($dmsg);


//if (mail($recipient,$betreff,$msg,$mailheaders)) {
//mail($email, $dsubject, $dmsg, $dmailheaders);
if (mail($recipient, $mailheader_betreff, $msg, implode("\n", $mailheaders))) {
mail($email, $dmailheader_dsubject, $dmsg, implode("\n", $dmailheaders));


echo "The test form was sent successfully. You should receive an email within the next few seconds. <a href=https://www.kontaktformular.com/en/faq-script-php-contact-form.html#not-receive-email>You did not receive an email? Click here!</a>";

exit;
 
}
}
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de-DE" lang="de-DE">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
<meta name="language" 			content="de"/>
<meta name="description"      content="kontaktformular.com"/>
<meta name="revisit"          content="After 7 days"/>
<meta name="robots"           content="INDEX,FOLLOW"/>
<meta http-equiv="Content-Style-Type" content="text/css" />   
<meta http-equiv="Content-Script-Type" content="text/javascript" />
<title>kontaktformular.com</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
<style type="text/css">
.kontaktformular  {
	 width:  390px;
     margin: 10px 0;
     padding: 10px;
     font-size: 11px;
     font-family: Tahoma, Verdana, Arial;
     border: 1px solid #666666;
     background: #F5F5F5;
     float: left;
     clear: both;
     }
     
.kontaktformular fieldset {margin: 10px 0;}
.kontaktformular a {color: #990000; text-decoration: none;}
.kontaktformular a:hover {color: #483D8A;}
  
.kontaktformular legend {
	 background: #483D8B;
	 color: #fff;
	 padding: 3px 5px;
	 border: 1px solid #ddd;
	 text-transform: uppercase;
	 }
	  
.kontaktformular label {
     width: 100px;
	 float: left;
     clear: both;
     padding: 3px 10px;
     margin: 3px 0;
     }
          
.kontaktformular input, .kontaktformular textarea {
     border-right: 1px solid #ddd; 
     border-bottom: 1px solid #ddd;
     border-left: 1px solid #666666; 
     border-top: 1px solid #666666;
     background: #ddc;
     padding: 0px 3px;
     margin: 3px 0;
	 width: 200px;
}

.kontaktformular input:active, .kontaktformular input:focus, .kontaktformular input:hover { background: #ffff88;}
.kontaktformular textarea:active, .kontaktformular textarea:focus, .kontaktformular textarea:hover { background: #ffff88;}

.kontaktdaten table, .anfrage table, .captcha table { border-spacing:0px; width:370px; border:0px solid;}
.label { width:100px; padding:0px;}
.field { width:250px; padding:0px;}
.error { width:200px; padding:0px 0px 0px 5px;}
.captchareload { padding-left:10px;}
input.errordesignfields, textarea.errordesignfields { background-color:#cc3333;}




.buttons { text-align: center;}
.buttons input { width:80px;}
.buttons input, .captchareload img { background: #555555; color: #fff; border-top:2px solid #fff; border-left:2px solid #fff; border-bottom:2px solid #666666; border-right:2px solid #666666;}
.buttons input:hover, .captchareload img:hover { background: #555555; color: #fff; border-top:2px solid #ddd; border-left:2px solid #ddd; border-bottom:2px solid #fff; border-right:2px solid #fff;}

.pflichtfeld {
	 color: #800000;
	 

</style>
</head>

<body id="Kontaktformularseite">

<div class="kontaktformular">
<p><b>Form to test your server’s mailing function</b><br /><br />Please enter your <b>email address</b> here and click on <b>send</b>.<br /><br />You should receive an email within a few seconds.<br />You may then delete this file.</p>
<form action="<?php $_SERVER['PHP_SELF'];?>" method="post" enctype="multipart/form-data">
<p><input style="width:0px; height:0px; visibility:hidden;" type="hidden" name="action" value="smail" /></p>
<p><input style="width:0px; height:0px; visibility:hidden;" type="hidden" name="content" value="formular"/></p>

<fieldset class="kontaktdaten">
    <legend>Test form</legend>
	<table>
		<tr>
			<td class="label"><label><b>YOUR email: </b><span class="pflichtfeld">*</span></label></td>
			<td class="field"><?php if ($fehler["email"] != "") { echo $fehler["email"]; } ?><input type="text" name="email" maxlength="200" value="" size="20" /></td>
		</tr>
	</table>
 </fieldset>


 <fieldset class="buttons">
   <legend>Your action</legend>
   <input type="submit" name="kf-km1212" value="Send" onclick="tescht();"/>
  
</fieldset></form> 
</div>

</body>
</html>