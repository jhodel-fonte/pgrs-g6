<?php
// Class to handle SMS and OTP using external API
class smsHandler {
    private $smsUrl = 'https://sms.iprogtech.com/api/v1/sms_messages';
    private $otpUrl = 'https://sms.iprogtech.com/api/v1/otp/send_otp';
    private $apiToken = '92b811ed5d06c8640a7122f69ac1eac583b41b55';
    private $logFile = __DIR__ . '/../log/sms.log'; // log file path

    private function logSms($phone, $message, $status) {
        $line = "[" . date('Y-m-d H:i:s') . "] To: $phone | Status: $status | Message: $message" . PHP_EOL;
        file_put_contents($this->logFile, $line, FILE_APPEND);
    }

    public function sendSms($dataArray) {
        $data = [
            'api_token' => $this->apiToken,
            'message' => isset($dataArray['message']) ? $dataArray['message'] : '',
            'phone_number' => isset($dataArray['phoneNumber']) ? $dataArray['phoneNumber'] : (isset($dataArray['phoneNunber']) ? $dataArray['phoneNunber'] : '')
        ];

        $ch = curl_init($this->smsUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        $response = curl_exec($ch);
        curl_close($ch);

        // Determine status from response
        $res = json_decode($response, true);
        $status = (isset($res['status']) && $res['status'] === 'success') ? 'success' : 'failed';

        // Log the SMS
        $this->logSms($data['phone_number'], $data['message'], $status);

        return $response;
    }

    public function sendOtp($number) {
        $data = [
            'api_token' => $this->apiToken,
            'phone_number' => $number
        ];

        $ch = curl_init($this->otpUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        $response = curl_exec($ch);
        curl_close($ch);

        // Log OTP send attempt
        $this->logSms($number, "OTP sent", 'success'); 

        return $response;
    }

    public function verifyOtp($num, $otp) {
        $url = 'https://sms.iprogtech.com/api/v1/otp/verify_otp';

        $data = [
            'api_token' => $this->apiToken,
            'phone_number' => $num,
            'otp' => $otp
        ];

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/x-www-form-urlencoded']);
        $response = curl_exec($ch);
        curl_close($ch);

        // Log OTP verification attempt
        $res = json_decode($response, true);
        $status = (isset($res['status']) && $res['status'] === 'success') ? 'success' : 'failed';
        $this->logSms($num, "OTP verification: $otp", $status);

        return $response;
    }
}
