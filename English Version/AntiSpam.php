<?php
header('Content-type: text/html; charset=utf-8');
$data = array(
	0 => array("What is the shape of a ball?","round"),
	1 => array("How many hours in a day?",24),
	2 => array("How many days in a week?",7),
	3 => array("How many seconds in a minute?",60),
	4 => array("What is 5 times 3?",15),
	5 => array("What is 9 minus 2?",7),
	6 => array("What is 12 plus 1?",13),
	7 => array("What is 50 divided by 10?",5)
);

class AntiSpam{

	public static function getAnswerById($id){
		global $data;
		
		return $data[$id][1];
	}	
	
	public static function getRandomQuestion(){
		global $data;
		
		$rand = rand(0,count($data)-1);
		return array($rand,$data[$rand][0]);
	}
	
}

?>