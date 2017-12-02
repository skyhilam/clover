<?php 

namespace Clover;

use Telegram as TG;

class Telegram {
	public function sendNotification($message, $chat_id = '') 
	{
		TG::sendMessage([
    		'chat_id' => empty($chat_id)? env('TELEGRAM_CHAT_ID', ''): $chat_id,
    		'text' => $this->formatMessage($message)
		]);
	}

	protected function formatMessage(Array $arr) 
    {
    	$msg = 'domain: '.request()->url(). "\r\n";
    	foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $value = implode(',', $value);
            }
    		$msg .= $key. ': '. $value. "\r\n";
    	}

        \Log::notice($msg);
    	return $msg;
    }    
}
