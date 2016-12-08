<?php

  class Solfege{

    public function readData(){
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
      return($output);
    }

    public function addSlugs($input){
      $output = $input;
      //Now for each row of our output array, add a slug.
      //Slugs are lowercase. They replace all non-alphanumerics with dashes.
      foreach($output as $key=>$value){
        $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/','-',($output[$key][3].'-'.$output[$key][4])));
        $slug = preg_replace('/-+/','-',$slug);
        $slug = preg_replace('/-$/','',$slug);
        $output[$key][] = $slug;
      }
      return($output);
    }

    public function convertThemes($input){
      $output = $input;
      foreach($output as $key=>$value){
        $output[$key][7] = trim($output[$key][7]);
        $themestring = $output[$key][7];
        $themestring = str_replace('do','00',$themestring);
        $themestring = str_replace('di','01',$themestring);
        $themestring = str_replace('rah','01',$themestring);
        $themestring = str_replace('re','02',$themestring);
        $themestring = str_replace('ri','03',$themestring);
        $themestring = str_replace('me','03',$themestring);
        $themestring = str_replace('mi','04',$themestring);
        $themestring = str_replace('fa','05',$themestring);
        $themestring = str_replace('fi','06',$themestring);
        $themestring = str_replace('se','06',$themestring);
        $themestring = str_replace('sol','08',$themestring);
        $themestring = str_replace('si','09',$themestring);
        $themestring = str_replace('le','09',$themestring);
        $themestring = str_replace('la','10',$themestring);
        $themestring = str_replace('li','11',$themestring);
        $themestring = str_replace('te','11',$themestring);
        $themestring = str_replace('ti','12',$themestring);
        $themestring = trim($themestring);
        $themestring = preg_replace('/[^0-9 ]/','',$themestring);
        $output[$key][] = $themestring;
      }
      return($output);
    }

    public function writeData($input){
      //Write a different csv
      $output = $input;
      $fp = fopen('solfege-slugged.csv','w');
      foreach($output as $fields){
        fputcsv($fp,$fields);
      }
      fclose($fp);
    }

  }

  $data = Solfege::readData();
  $data = Solfege::addSlugs($data);
  $data = Solfege::convertThemes($data);
  Solfege::writeData($data);

?>
