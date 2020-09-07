<?php

include("db.php");

if($squ = $mysqli->query("SELECT * FROM settings WHERE id='1'")){

    $settings = mysqli_fetch_array($squ);

    $squ->close();
	
}else{
    
	 printf("Error: %s\n", $mysqli->error);
}

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
		
	
		<ul class="chat">
                        <li class="left clearfix"><span class="chat-img pull-left">
                            <img src='.$d['avatarlink'].' width="50" height="50" alt='.$d['name'].' class="media-object" />
                        </span>
                            <div class="chat-body clearfix">
                                <div class="chat-header">
                                    <strong class="primary-font">'.$d['name'].'</strong> <small class="pull-right text-muted">
                                        <span class="glyphicon glyphicon-time"></span> Just Now</small>
                      </div>
	<p>'.$d['body'].'</p>
                            </div>
                        </li>                     
                    </ul>
	
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
		
		if(!($data['toid'] = filter_input(INPUT_POST,'toid',FILTER_CALLBACK,array('options'=>'Comment::validate_text'))))
		{
			$errors['toid'] = 'There seems to be a problem.';
		}
		
		if(!($data['toname'] = filter_input(INPUT_POST,'toname',FILTER_CALLBACK,array('options'=>'Comment::validate_text'))))
		{
			$errors['toname'] = 'There seems to be a problem.';
		}
		
		if(!($data['pmid'] = filter_input(INPUT_POST,'pmid',FILTER_CALLBACK,array('options'=>'Comment::validate_text'))))
		{
			$errors['pmid'] = 'There seems to be a problem.';
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