<?php

class Adams {
	//This class is named after my favorite minimalist composer.
	//Like any good minimalist, it is obsessed with permutations.
	//Specifically, we want to find all possible sharp/flat combinations in each solfege theme.
	//That means toggling syllables like "ra" and "di", "me" and "ri", etc.,.
	//In other words, this is essentially a bit-flipping excercise.
	public static function getTestString(){
		//This function will throw out a test string. It is for testing only.
		$testString = 'me re re me fa me re re me me me fa se sol sol fa sol la sol fa me';
		//$testString = 'me re re me sol me';
		return($testString);
	}
	public static function raiseAll($inputArray){
		//This function turns all "flat" notes into "sharp" equivalents.
		//Syllables come to us courtesy of the chart at http://bit.ly/2hc2MlQ
		//Function takes in an array of syllables. Function returns a modified array.
		$outputArray = $inputArray;
		foreach($outputArray as $key=>$value){
			switch($value){
				case 'ra':
					$outputArray[$key] = 'di';	break;
				case 'me':
					$outputArray[$key] = 'ri'; break;
				case 'se':
					$outputArray[$key] = 'fi'; break;
				case 'le':
					$outputArray[$key] = 'si'; break;
				case 'te':
					$outputArray[$key] = 'li'; break;
			}	
		}
		return($outputArray);	
	}
	public static function lower($inputArray,$position){
		//This function takes in a single set of syllables as an array.
		//It then lowers the syllable at the given position and passes back a modified array.
		$outputArray = $inputArray;
		switch($inputArray[$position]){
			case 'di':
				$outputArray[$position] = 'ra'; break;	
			case 'ri':
				$outputArray[$position] = 'me'; break;
			case 'fi':
				$outputArray[$position] = 'se'; break;
			case 'si':
				$outputArray[$position] = 'le'; break;
			case 'li':
				$outputArray[$position] = 'te'; break;
		}	
		return($outputArray);
	}
	public static function branch($inputArray,$position){
		//This function takes in an array of existing variations and a position we're toggling.
		//It returns an array of variations that have the specified position toggled (lowered).
		$outputArray = Array();
		foreach($inputArray as $subArray){
			$subArrayLowered = Adams::lower($subArray,$position);
			$outputArray[] = $subArrayLowered;
		}	
		return($outputArray);
	}
	public static function getPermutations($inputArray){
		//Function takes in an array of syllables (assumed to be in fully "sharped" form)
		//Function adds all possible sharp/flat combinations and returns a nested array of the permutations.
		$outputArray = Array($inputArray);	
		$syllablesToLower = Array('di','ri','fi','si','li');
		foreach($inputArray as $position=>$value){
			if(in_array($value,$syllablesToLower)){
				foreach(Adams::branch($outputArray,$position) as $lowered){
					$outputArray[] = $lowered;	
				}	
			}
		}
		return($outputArray);
	}
	public static function test(){
		//This function just does some testing.
		//It takes in no arguments and doesn't return anything.
		//It prints out a bunch of permutations.
		$testArray = explode(' ', Adams::getTestString());
		$testArray = Adams::raiseAll($testArray);
		$permutations = Adams::getPermutations($testArray);	
		//print_r($permutations);
		foreach($permutations as $permutation){
			$outputString .= implode(' ',$permutation)."\n";	
		}
		print $outputString;
	}
}

Adams::test();

?>
