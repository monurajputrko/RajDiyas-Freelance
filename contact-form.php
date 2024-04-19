<?php 
if (isset($_POST['contact-form-btn'])) {
		$name = $_POST['name'];
		$mobile = $_POST['mobile'];
		$email = $_POST['email'];
		$subject ="Subject: ".$_POST['subject'];
		$service =$_POST['service'];
		$message =$_POST['message'];
		$to = " monurko99@gmail.com";
		$body = "Name: ".$name."\n"."Mobile Number: ".$mobile."\n"."E-mail Address: ".$email."\n"."Service: ".$service."\n"."Message: ".$message;
		$header ="From: ".$email;
		if (mail($to,$subject,$body,$header)) {
			$referer = $_SERVER['HTTP_REFERER'];
			header("Location: $referer");
		}else{
			echo "Error";
		}
	}
	if(isset($_POST['subscribe-btn'])){
		$email = $_POST['email'];
		$to = "monurko99@gmail.com";
		$subject = "Subscriber: ".$email;
		$body = "User: ".$email." is subscribed for Newsletter.";
		$header = "From: ".$email;
		if(mail($to,$subject,$body,$header)){
			$referer = $_SERVER['HTTP_REFERER'];
			header("Location: $referer");
		}else{
			echo "Error";
		}
	}
?>