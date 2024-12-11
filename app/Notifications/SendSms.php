<?php

namespace App\Notifications;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notification;
use App\Models\Sms_log;

class SendSms extends Notification
{
    public function toSms($mobile, $body, $old=false)
    {
        $url = config('services.bangladeshsms.domain');
        $apiKey = config('services.bangladeshsms.api_key');
        $senderId = config('services.bangladeshsms.senderid');

        $data = [
            "url"=>$url,
            "user_id"=>Auth::id(),
            "api_key" => $apiKey,
            "type" => "text",
            "contacts" => $mobile,
            "senderid" => $senderId,
            "label" => 'transactional',
            "msg" => $body
        ];

        try{
            $smslog;
            if($old === false){
                $smslog = new Sms_log();
                $smslog->fill($data)->save();
            }
            else
                $smslog = Sms_log::findOrFail($old);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            
            $update = ["response"=>$response];
            // dd($update);
            $smslog->update($update);

        }catch(\Exception $e) {
            flash()->addError($e);
        }
    }
    
    public function getBalance()
    {
        $url = config('services.bangladeshsms.domain');
        $apiKey = config('services.bangladeshsms.api_key');
        $url = str_replace('smsapi', 'miscapi', $url);
        $url = $url.'/'.$apiKey.'/getBalance';

        try{            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $response = curl_exec($ch);
            curl_close($ch);
            $bal = (float) str_replace('Your Balance is:BDT ', '', $response);
            return $bal + $bal * 0.25;
        }catch(\Exception $e) {
            flash()->addError($e);
        }
    }
}
