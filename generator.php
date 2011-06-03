<?php
/* 
 * File: generator.php 
 * Late Update: 5/5/11
 * Version: n/a
 * Baked Potato Project ( https://github.com/Rombus/BakedPotato )
 */
 
 // includes markdown.php
 include_once 'markdown.php';
 
 // setting up directory to search for new posts
 // (eventually these will move over to a settings file)
 $input_dir = "./Input/";
 $output_dir = "./Output/";
 $template = "./Template/XHTML1_STRICT.html";
 
 // scanning though the array given to find just .txt files and run them though markdown
 $files1 = scandir($input_dir);
 foreach($files1 as $key => $value){
	if(preg_match('/.*.txt/',$value)) {
		
		// Opens the .txt file and copys the contents
		$input_filename = $input_dir . $value;
		$input_handle = fopen($input_filename, "r");
		$input_content = fread($input_handle, filesize($input_filename));
		fclose($input_handle);

		// Splits the input into an array for use to break out the tags
		// Note: We are only getting the first 6 tags which should be below
		//       That way we avoid any tags in the content
		$input_array = preg_split("(<Type>|<Status>|<Title>|<Tags>|<Slug>|<Markdown>)", $input_content, 6,PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
		
		// Makes it a little more programmer friendly, (Need to figure out an expandable way to do this)
		$input_type = trim($input_array[0]);
		$input_status = trim($input_array[1]);
		$input_title = trim($input_array[2]);
		$input_tags = trim($input_array[3]);
		$input_slug = trim($input_array[4]);
		$input_markdown = $input_array[5];
		$input_time = time();
		
		// runs the Markdown script
		$parsed_html = Markdown($input_markdown);
		
		// Opening the Template file
		$template_handle = fopen($template, "r");
		$template_content = fread($template_handle, filesize($template));
		fclose($template_handle);
		
		// Put the parsed_html in between the <body> tags
		$finished_html = str_replace("<title>" , "<title>" . $input_title , $template_content );
		$finished_html = str_replace("<body>" , "<body>\n" . $parsed_html , $finished_html );
		$finished_markdown = "<Type> " . $input_type . "\n";
		$finished_markdown .= "<Status> " . $input_status . "\n";
		$finished_markdown .= "<Title> " . $input_title . "\n";
		$finished_markdown .= "<Tags> " . $input_tags . "\n";
		$finished_markdown .= "<Slug> " . $input_slug . "\n";
		$finished_markdown .= "<Time> " . $input_time . "\n";
		$finished_markdown .= "<Markdown>" . $input_markdown;
		
		// Creates the approprate markdown and text files
		$output_html_filename = $output_dir . $input_slug . '.html';
		$output_markdown_filename = $output_dir . $input_slug . '.txt';
		$output_html_handle = fopen($output_html_filename, "c");
		$output_markdown_handle = fopen($output_markdown_filename, "c");
		if( fwrite($output_html_handle, $finished_html) === FALSE) { 
			echo "Cannot Write to File( $output_filename )";
			exit;
		}
		fclose($output_html_handle);
		echo "Success, wrote to file ( $output_html_filename )";

		if( fwrite($output_markdown_handle, $finished_markdown) === FALSE) { 
			echo "Cannot Write to File( $output_markdown_filename )";
			exit;
		}
		fclose($output_markdown_handle);
		echo "Success, wrote to file ( $output_markdown_filename )";
		}
}
 ?>
 