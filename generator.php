<?php
/* 
 * File: generator.php 
 * Late Update: 4/26/11
 * Version: n/a
 * Baked Potato Project ( https://github.com/Rombus/BakedPotato )
 */
 // includes markdown.php
 include_once 'markdown.php';
 
 // setting up directory to search for new posts
 $input_dir = "./Input/";
 $output_dir = "./Output/";
 $files1 = scandir($input_dir);
 
 // scanning though the array given to find just .txt files and run them though markdown
 foreach($files1 as $key => $value){
	if(preg_match('/.*.txt/',$value)) {
		$input_filename = $input_dir . $value;
		$input_handle = fopen($input_filename, "r");
		$input_content = fread($input_handle, filesize($input_filename));
		fclose($input_handle);
		$parsed_html = Markdown($input_content);
		$output_filename = $output_dir . $value;
		$output_handle = fopen($output_filename, "c");
		if( fwrite($output_handle, $parsed_html) === FALSE) { 
			echo "Cannot Write to File($output_filename)";
			exit;
		}
		fclose($output_handle);
		echo "Sucess, wrote to file ($output_filename)";
		}
}
 ?>
 