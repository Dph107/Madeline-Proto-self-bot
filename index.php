<?php

//die ("Script Stoped");

if(!file_exists('madeline.php'))  
    copy('https://phar.madelineproto.xyz/madeline.php', 'madeline.php'); 
 
ini_set('memory_limit', '4096M'); 
define('MADELINE_BRANCH', '');

include 'madeline.php'; 
include 'functions.php';

//=============( Start MadeLineProto Library )=============// 

$settings['logger']['max_size'] = 5*1024*1024;
//$settings = ['app_info'=> ['api_id'=>xxx,'api_hash'=> 'xxx']]; 

$MadelineProto = new \danog\MadelineProto\API('MyBot.Ses', $settings);

echo dump($MadelineProro);

$MadelineProto->start(); 
$MadelineProto->setCallback(function ($update) use ($MadelineProto) { 
    if ($update['_'] == 'updateNewMessage' or $update['_'] == 'updateNewChannelMessage') { 
    	$Info = yield $MadelineProto->get_info($update); 
    	$type = $Info['type']; 
    	$chat_id = isset($Info['bot_api_id']) ? $Info['bot_api_id'] : null; 
    	$from_id = isset($update['message']['from_id']) ? $update['message']['from_id'] : null; 
    	$message_id = isset($update['message']['id']) ? $update['message']['id'] : null; 
    	$text = isset($update['message']['message']) ? $update['message']['message'] : null; 
    	
    	try{  
            
            // Bot Commands 
 
            if ($text == "!restart") { 
            
                    $restart = '♻ Restarted...';
 
                    yield $MadelineProto -> restart(); 
 
                    yield $MadelineProto -> messages -> editMessage(['peer' => $chat_id, 'id' => $message_id, 'message' => $restart]); 
 
            } 
 
            else if ($text == "!ping") { 
     
			        	$memory = memory_get_peak_usage(); 
 				
			        	$ping  = "<b>🤖 Condition Bot : </b><code>online\n"; 
  	    			$ping .= "</code><b>🐘 PHP Version : </b><code>" . phpversion(); 
  	    			$ping .= "</code><b>\n💻 Memory Usage : </b><code>" . convert($memory) . "\n</code>" . "<b>💡 CPU Usage : </b><code>" . getCpuUsage() . "</code>";
  				
  		    		$ping .= "\n💠 Command Guide : !help";
  
  
  		    		yield $MadelineProto -> messages -> editMessage(['peer' => $chat_id, 'id' => $message_id, 'message' => $ping, 'parse_mode' => "MarkDown"]); 
 
			} 
 
			else if (preg_match('/^!php\n([\s\S]*)$/', $text, $r)) {
    			
    			$php = RexTester(8, $r[1]); 
			
    			$result ="<b>Language:\n</b>" . "<code>php</code>\n\n" . "<b>Source:\n</b>" . "<code>" . $r[1] . "</code>\n\n";
    			
    			if (isset($php["Result"]))
        			$result .= "<b>Result:</b>\n" . "<code>" . $php["Result"] . "</code>\n\n";
    		
    			
    			if (isset($php["Warnings"])) 
			        $result .= "<b>Warnings:</b>\n" . "<code>" . $php["Warnings"] . "</code>\n\n";
    			
     			
    			if (isset($php["Errors"])) 
        			$result .=  "<b>Errors:</b>\n" . "<code>" . $php["Errors"] . "</code>\n\n";
    			
    			
    			if (mb_strlen ($result) >= 4096) yield $MadelineProto ->messages ->editMessage(['peer' => $chat_id, 'id' => $message_id, 'message' => "You have too many characters"]);
    			
    			else yield $MadelineProto ->messages ->editMessage(['peer' => $chat_id, 'id' => $message_id, 'message' => $result, 'parse_mode' => 'MarkDown']);
    			
			}

			else if ($text == "!file") {
			
    			$scan = scandir(getcwd());
    			$scan = array_diff($scan, ['.', '..']);
    			$a_file_size = ""; /*-----*/ $c_file_size = 0;
			
    			foreach ($scan as $values) {
        			$a_file_size .= "$values  : " . convert (filesize ($values)) . "\n";
        			$c_file_size +=  filesize ($values);
    			}
    			
    			
    			$c_file_size = "<u>All directory files : " . convert($c_file_size) . "</u>";
    			
    			$message = "<b>💿 Space occupied by each file:</b>\n" . "<u>$a_file_size</u>" . "\n\n<b>💿 Occupied space of all current directory files:</b>\n" . $c_file_size;
    			
    			yield $MadelineProto ->messages ->editMessage(['peer' => $chat_id, 'id' => $message_id, 'message' => $message, 'parse_mode' => 'MarkDown']);
    			
			}

			else if ($text == "!log") {
    			
    			if (file_exists("MadelineProto.log")) 
    			yield $MadelineProto->messages->sendMedia([
            			'peer' => $chat_id, 'media' => [ '_' => 'inputMediaUploadedDocument', 'file' => realpath("MadelineProto.log")], 'message' => "<b>MadelineProto.log</b>", 'parse_mode' => "MarkDown"
        			]);
    			
    			else 
        			yield $MadelineProto->messages->editMessage(['peer' => $chat_id, 'id' => $message_id, 'message' => "The log file does not exist in the current directory"]);
			}

			else if ($text == "!deleteLog") {
			
    			unlink("MadelineProto.log");
    			yield $MadelineProto->messages->editMessage(['peer' => $chat_id, 'id' => $message_id, 'message' => "ok..."]);
    			
			}
			
			
			else if ($text == "!source") {
    			yield $MadelineProto->messages->sendMedia([
        			'peer' => $chat_id, 'media' => [ '_' => 'inputMediaUploadedDocument', 'file' => realpath("index.php")], 'message' => "<b>index.php</b>", 'parse_mode' => "MarkDown"
    			]);
    			yield $MadelineProto->messages->sendMedia([
        			'peer' => $chat_id, 'media' => [ '_' => 'inputMediaUploadedDocument', 'file' => realpath("functions.php")], 'message' => "<b>functions.php</b>", 'parse_mode' => "MarkDown"
        			
          //If you have another file, add it here
    			]);
			}

			else if ($text == "!chatID") {
			
    			$reply_to_msg_id = $update["message"] ["reply_to_msg_id"];
    			$c = yield $MadelineProto->channels->getMessages(['channel' => $chat_id , 'id' => [$reply_to_msg_id]]);
    			$c = $c["messages"][0]["from_id"];
    			yield $MadelineProto->messages->editMessage(['peer' => $chat_id, 'id' => $message_id, 'message' => "<b>📂 Your chat ID:</b>\n" . "<code>$c</code>", 'parse_mode' => "MarkDown"]);
    			
			}

			else if(preg_match("/^[\/\#\!]?(spam) ([0-9]+) (.*)$/i", $text)){
			
				preg_match("/^[\/\#\!]?(spam) ([0-9]+) (.*)$/i", $text, $m);
    				
    			for($i = 1; $i <= $m[2]; $i++)
        			yield $MadelineProto->messages->sendMessage(['peer' => $chat_id, 'message' => $m[3]]);
        			
			}

else if(preg_match("/^[\/\#\!]?(flood) ([0-9]+) (.*)$/i", $text)){
			
	preg_match("/^[\/\#\!]?(flood) ([0-9]+) (.*)$/i", $text, $m);
				
$flood = "";
				
for($i=1; $i <= $m[2]; $i++)
    		$flood .= $m[3] . "\n";
    				
    				
yield $MadelineProto->messages->editMessage(['peer' => $chat_id, 'id' => $message_id, 'message' => $flood]);

    }
	
} catch (\danog\MadelineProto\RPCErrorException $e) {} catch(\danog\MadelineProto\Exception $e){}  
} 
});
 
$MadelineProto->async(true); 
$MadelineProto->loop();