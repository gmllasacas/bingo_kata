<?php

	$base = [];

	/**
	* Generate the base of numbers for the current bingo
	* @param Array Global $base
	*/
	function generate_base(){
		global $base;
		for ($i=1; $i <= 75 ; $i++) { 
			$base[$i] = $i;
		}
		//$base = range(1, 75);
	}

	/**
	* Generate a random number between a defined range, uses the available random function
	* @param Int $min
	* @param Int $max
	* @return Int random number
	*/
	function generate_random($min = 1, $max = 75) {
		if (function_exists('random_int')):
			return random_int($min, $max);
		elseif (function_exists('mt_rand')):
			return mt_rand($min, $max);
		endif;
		return rand($min, $max);
	}

	/**
	* Choose a random number from the base of numbers available
	* if the number exists then delete it for the base and return it
	* if not exists use the same function for the next number
	* @param Array $base
	* @return Int random number chosen
	*/
	function bingo_number($base){
		//global $base;
		$chosen_key = generate_random();
		//echo print_r('Data: '.$base[$chosen_key],true).'<br>';
		if(array_key_exists($chosen_key, $base) && isset($base[$chosen_key]) && $chosen_key>=0){
			//echo print_r('chosen_key: '.$chosen_key,true).'<br>';
			//unset($base[$chosen_key]);
			return $chosen_key;
		}else{
			bingo_number($base);
		}
	}

	/**
	* Creates a random card,
	* TODO rules 
	* @return Array random card
	*/
	function generate_card(){
		$base_card = range(1, 75);
		$card = [];
		$columns = ['B','I','N','G','O'];
		//echo print_r($base_card,true);

		for ($n=0; $n < 25; $n++) { 
			//echo print_r($base_card,true);
			$chosen_key = bingo_number($base_card);
			$card[] = $base_card[$chosen_key];
			unset($base_card[$chosen_key]);
		}

		return $card;
	}

	/**
	* Check if a card won
	* @param Array $card
	* @return String result 
	*/
	function check_card($card,$bingo){
		if ($card == $bingo)
			return 'Bingo!';
		else
			return 'No match';
	}

	try {
		$bingo = [];
		//Generate inital base for the bingo
		generate_base();
		echo "<pre>";
		//echo print_r($base,true);

		//Generate card
		$card = generate_card();
		echo 'Card:<br>';
		echo print_r($card,true);

		//Chose random numbers
		for ($n=0; $n < 25; $n++) { 
			//echo print_r($base,true);
			$chosen_key = bingo_number($base);
			//echo print_r('chosen_key: '.$chosen_key,true).'<br>';
			//echo print_r('Data: '.$base[$chosen_key],true).'<br>';
			$bingo[] = $base[$chosen_key];
			unset($base[$chosen_key]);
		}
		echo 'Bingo:<br>';
		echo print_r($bingo,true);

		echo 'Check card:<br>';
		echo check_card($card,$bingo);
	
		//echo print_r($base,true);

	} catch (\Throwable $e) {
		http_response_code(400);
		error_log('['.date('Y-m-d H:i:s').'] '.$e.PHP_EOL , 3, 'error.log');
		//echo json_encode(['status'=>400,'error'=>$e->getMessage()]);
	}