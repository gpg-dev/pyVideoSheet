<?php

include("db.php");



//Comments Data

class Comment
{

	private $data = array();
	
	public function __construct($row)
	{
		/*
		/	The constructor
		*/
		
		$this->data = $row;
	}
	
	public function markup()
	{
		/*
		/	This method outputs the XHTML markup of the comment
		*/
		
		// Setting up an alias, so we don't have to write $this->data every time:
		$d = &$this->data;
		
		$link_open = '';
		$link_close = '';
		
		if($d['url']){
			
			// If the person has entered a URL when adding a comment,
			// define opening and closing hyperlink tags
			
			$link_open = '<a href="'.$d['url'].'">';
			$link_close =  '</a>';
		}
		
		// Converting the time to a UNIX timestamp:
		$d['dt'] = strtotime($d['dt']);
		
		
		

		
		return '
		
			<div class="comments-box">
	<div class="media">
					<img src='.$d['avatarlink'].' width="64" height="64" class="media-object pull-left" alt="'.$d['name'].'">
				
				
				<div class="media-body">
				<h4 class="media-heading">'.$link_open.$d['name'].$link_close.'<small><i> Posted Now</i></small></h4>
				<p>'.$d['body'].'</p>
			</div>
        </div>
    </div>
		';
	}
	
	public static function validate(&$arr)
	{
		/*
		/	This method is used to validate the data sent via AJAX.
		/
		/	It return true/false depending on whether the data is valid, and populates
		/	the $arr array passed as a paremter (notice the ampersand above) with
		/	either the valid input data, or the error messages.
		*/
		
		$errors = array();
		$data	= array();
		
		// Using the filter_input function introduced in PHP 5.2.0
		
		if(!($data['email'] = filter_input(INPUT_POST,'email',FILTER_VALIDATE_EMAIL)))
		{
			$errors['email'] = 'There seems to be a problem.';
		}
		
			
		// Using the filter with a custom callback function:
		
		if(!($data['body'] = filter_input(INPUT_POST,'body',FILTER_CALLBACK,array('options'=>'Comment::validate_text'))))
		{
			$errors['body'] = 'Please enter your review.';
		}
		
		if(!($data['name'] = filter_input(INPUT_POST,'name',FILTER_CALLBACK,array('options'=>'Comment::validate_text'))))
		{
			$errors['name'] = 'There seems to be a problem.';
		}
		
		if(!($data['ruid'] = filter_input(INPUT_POST,'ruid',FILTER_CALLBACK,array('options'=>'Comment::validate_text'))))
		{
			$errors['ruid'] = 'There seems to be a problem.';
		}
		
		if(!($data['sid'] = filter_input(INPUT_POST,'sid',FILTER_CALLBACK,array('options'=>'Comment::validate_text'))))
		{
			$errors['sid'] = 'There seems to be a problem.';
		}
		
		if(!($data['avatarlink'] = filter_input(INPUT_POST,'avatarlink',FILTER_CALLBACK,array('options'=>'Comment::validate_text'))))
		{
			$errors['avatarlink'] = 'There seems to be a problem.';
		}
		
		if(!empty($errors)){
			
			// If there are errors, copy the $errors array to $arr:
			
			$arr = $errors;
			return false;
		}
		
		// If the data is valid, sanitize all the data and copy it to $arr:
		
		foreach($data as $k=>$v){
			$arr[$k] = ($v);
		}
		
		// Ensure that the email is lower case:
		
		$arr['email'] = strtolower(trim($arr['email']));
		
		return true;
		
	}

	private static function validate_text($str)
	{
		/*
		/	This method is used internally as a FILTER_CALLBACK
		*/
		
		if(mb_strlen($str,'utf8')<1)
			return false;
		
		// Encode all html special characters (<, >, ", & .. etc) and convert
		// the new line characters to <br> tags:
		
		$str = nl2br(htmlspecialchars($str));
		
		// Remove the new line characters that are left
		$str = str_replace(array(chr(10),chr(13)),'',$str);
		
		return $str;
	}

}

?>