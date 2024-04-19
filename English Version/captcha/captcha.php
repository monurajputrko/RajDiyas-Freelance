<?php
#########################################################################
#	Kontaktformular.com         					                                #
#	http://www.kontaktformular.com        						                    #
#	All rights by KnotheMedia.de                                    			#
#-----------------------------------------------------------------------#
#	I-Net: http://www.knothemedia.de                            					#
#########################################################################
// Do NOT remove the copyright notice!

session_start();
	unset($_SESSION['captcha_spam']);

	function MakeAlphabet(){
		// Use uppercase letters except for "L", "I", "O"
		for ($x = 65; $x <= 90; $x++) {
			if($x != 73 && $x != 76 && $x != 79)
				$alphabet[] = chr($x);
		}

		// Use lowercase letters except for "L", "I", "O"
		for ($x = 97; $x <= 122; $x++) {
			if($x != 105 && $x != 108 && $x != 111)
				$alphabet[] = chr($x);
		}

		// Use numbers except for "0", "1"
		for ($x = 48; $x <= 57; $x++) {
			if($x != 48 && $x != 49)
				$alphabet[] = chr($x);
		}

		return $alphabet;
	}

	//Generate word list
	$alphabet = MakeAlphabet();

	// Randomize alphabet array
	shuffle($alphabet); 

	// First 4 characters of a shuffled word list
	for ($i=0; $i<4; $i++) {
		$text .= $alphabet[$i];
	}
	
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

	$_SESSION['captcha_spam'] = encrypt($text, "8h384ls94"); //Key
	$_SESSION['captcha_spam'] = str_replace("=", "", $_SESSION['captcha_spam']);

	header('Content-type: image/png');
	$img = ImageCreateFromPNG('captcha.PNG'); //Background image
	$color = ImageColorAllocate($img, 0, 0, 0); //Color
	$ttf = "/Imperator.ttf";
	$ttfsize = 25; //Font size
	$angle = rand(0,5);
	$t_x = rand(5,50);
	$t_y = 35;
	imagettftext($img, $ttfsize, $angle, $t_x, $t_y, $color, $ttf, $text);
	imagepng($img);
	imagedestroy($img);
?>