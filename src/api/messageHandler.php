<?php

class smsHandler {
    private $smsUrl = 'https://sms.iprogtech.com/api/v1/sms_messages';
    private $otpUrl = 'https://sms.iprogtech.com/api/v1/otp/send_otp';
    private $apiToken = '92b811ed5d06c8640a7122f69ac1eac583b41b55';
    

    function sendSms($dataArray) {

        $data = [
        'api_token' => $this->apiToken,
        'message' => $dataArray['message'],
        'phone_number' => $dataArray['phoneNunber']
        ];
        
        $ch = curl_init($this->smsUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    function sendOtp($number) {
        $data = [
        'api_token' => $this->apiToken,
        'phone_number' => $number
        ];
        
        $ch = curl_init($this->otpUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);

        var_dump($response);

        if ($response['status'] == 'success') {
            return true;
        }

        return false;
    }

    function verifyOtp($num, $otp) {
        $url = 'https://sms.iprogtech.com/api/v1/otp/verify_otp';

        $data = [
            "api_token" => $this->apiToken,
            "phone_number" => $num,
            "otp" => $otp
        ];
        
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded'
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }


}


// $otps = new smsHandler();
// echo $otps->sendOtp('09949751617');

// echo $otps->verfyOtp('09949751617', '835624');
// echo "<br>";
// echo $otps->verfyOtp('09949751617', '835623');

 
?>



?>