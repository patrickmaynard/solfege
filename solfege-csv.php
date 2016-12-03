<?php

  class Solfege{

    public function addSlugs(){
      //Open the csv
      $raw = file_get_contents('solfege_divided.csv');
      $csv = str_getcsv($raw);
      $row = -1;
      //Now do some cleanup to get a nice, nested array.
      //(Our default array is just a general mess.)
      //(See note below about line endings.)
      foreach($csv as $key=>$value){
        if($key % 7 == 0){
          //This is the penultimate field. 
          //Auto-detection of line endings doesn't work on my mac.
          //For that reason, this script detects them manually. 
          $rowstarter = array_pop(explode("\n",$value));
          $value = array_shift(explode("\n",$value));
        }
        elseif(($key - 1) % 7 == 0){
          //This is a new row.
          //Put it into our output array.
          $row++;
          $output[$row][] = $rowstarter;
        }
        $output[$row][] = $value;
      }
      //Now for each row of our output array, add a slug.
      //Slugs are lowercase. They replace all non-alphanumerics with dashes.
      foreach($output as $key=>$value){
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/','-',($output[$key][3].'-'.$output[$key][4])));
        $slug = preg_replace('/-+/','-',$slug);
        $slug = preg_replace('/-$/','',$slug);
        $output[$key][] = $slug;

      }
      print_r($output);
      //TODO:Write a different csv
      $fp = fopen('output.csv','w');
      foreach($output as $fields){
        fputcsv($fp,$fields); 
      }
      fclose($fp);
    }    
    
    public function convertThemes(){
      //This is a stub for now.
    }

  }

  Solfege::addSlugs();

?>
