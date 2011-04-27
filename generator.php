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
 $template = "./Template/XHTML1_STRICT.html";
 $files1 = scandir($input_dir);
 
 // scanning though the array given to find just .txt files and run them though markdown
 foreach($files1 as $key => $value){
	if(preg_match('/.*.txt/',$value)) {
		
		// Opens the .txt file and copys the contents
		$input_filename = $input_dir . $value;
		$input_handle = fopen($input_filename, "r");
		$input_content = fread($input_handle, filesize($input_filename));
		fclose($input_handle);
		
		// runs the markdown script
		$parsed_html = Markdown($input_content);
		
		// Opening the Template file
		$template_handle = fopen($template, "r");
		$template_content = fread($template_handle, filesize($template));
		fclose($template_handle);
		
		// Put the parsed_html in between the <body> tags
		$finished_html = str_replace("<body>" , "<body>\n" . $parsed_html , $template_content );
		
		// For testing, will be labled something specific, just trying html rename
		$output_filename = $output_dir . basename($input_filename, ".txt") . '.html';
		$output_handle = fopen($output_filename, "c");
		if( fwrite($output_handle, $finished_html) === FALSE) { 
			echo "Cannot Write to File( $output_filename )";
			exit;
		}
		fclose($output_handle);
		echo "Sucess, wrote to file ( $output_filename )";
		}
}
 ?>
 