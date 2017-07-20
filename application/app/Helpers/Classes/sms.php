<?php

namespace App\Classes;

class SMS {

    public static function send($destination, $body, $sender) {

        //phone number should be 13 digits
        if (strlen($destination) != 13) {
            return false;
        }

        //phone number shold be number
        if (!is_numeric($destination)) {
            return false;
        }

        //phone number should be contain 880 first
        if (substr($destination, 0, 3) != '880') {
            return false;
        }


        //now we are sure that our phone number is a valid number
        //let's try to send sms
        //prepearing data array
        $dataArray = [
            'authentication' => [
                'username' => env('SMS_USERNAME'),
                'password' => env('SMS_PASSWORD')
            ],
            'messages' => [
                [
                    'sender' => $sender,
                    'text' => $body,
                    'type' => 'longSMS',
                    'datacoding' => 8,
                    'recipients' => [
                        [
                            'gsm' => $destination
                        ]
                    ]
                ]
            ]
        ];

        //prepearing end point
        $url = env('SMS_API') . "/" . env('SMS_API_VERSION') . "/sendsms/json";

        //trying to send sms
        $response = SMS::curlCall($url, $dataArray);
        
        return $response;
    }

    public static function getCredit(){
        $url = "http://app.planetgroupbd.com/api/command?username=".env('SMS_USERNAME')."&password=".env('SMS_PASSWORD')."&cmd=Credits";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // Download the given URL, and return output
        $output = curl_exec($ch);

        if ($output === false) {
            
        }

        // Close the cURL resource, and free system resources
        curl_close($ch);

        return $output;
    }

    /**
     * Description: This function will handle all kinds of curl request
     * @param $url string
     * @param $data array
     * @return json
     */
    private static function curlCall($url, $data) {

        //converting data array to json
        $data_string = json_encode($data);
        //initialize curl
        $ch = curl_init($url);
        //setting up the curl options
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
        );

        // Download the given URL, and return output
        $output = curl_exec($ch);

        if ($output === false) {
            Events::notifyError(curl_error($ch));
        }

        // Close the cURL resource, and free system resources
        curl_close($ch);

        return $output;
    }

}