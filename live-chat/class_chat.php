<?php

require __DIR__ . "/vendor/autoload.php";
// include('class_main.php');

use \Firebase\JWT\JWT;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class firebase_chat extends main
{

    private $size_folder = array('l', 's');
    private $size_image = array(320, 800);
    private $keyToken  = "StayInStyleBangkokMobileApplication";
    private $project_uri = 'https://stay-in-style-37596.firebaseio.com/';
    private $ref = 'chatroom';
    public $firebase;

    function __construct()
    {
        try {
            // $serviceAccount = ServiceAccount::fromJsonFile(__DIR__ . '/../../stay-in-style-37596-8773f1609caf.json');
            // $this->firebase = (new Factory)->withServiceAccount($serviceAccount)->withDatabaseUri($this->project_uri)->createDatabase();
        } catch (\Throwable $th) {
            // throw $th;
            echo "<p>construct Throwable : " . $th . "</p>";
        }
    }

    function create_user_login($user_id, $token, $notification_token, $platform, $type)
    {
        $user_type = 1; // ($type == 'exhibitor') ? 0 : 1;
        $ip = $_SERVER['REMOTE_ADDR'];

        $data_user_login = array(
            "login_user_id" => $user_id,
            "login_user_type" => $user_type,
            "login_token" => $token,
            "login_notification_token" => $notification_token,
            "login_platform" => $platform,
            "login_logintime" => "NOW()",
            "login_ip" => $ip,
            "login_status" => 1
        );

        $user_login = $this->insert("user_login", $data_user_login);

        if ($user_login) {
            unset($_SESSION['user_log']);
            return $user_login;
        } else {
            return false;
        }
    }

    function createToken($payload, $keyToken)
    {
        $jwt = JWT::encode($payload, $keyToken);
        return $jwt;
    }


    function sendnoti($token, $title, $message) // token = user_login
    {
      exit();
        $registrationIds = $token; //array
        $msg = array(
            'title' => $title,
            'body' => $message,
            'type' => 0,
            'vibrate' => 1,
            'sound' => 1,
            'largeIcon' => 'large_icon',
            'smallIcon' => 'small_icon',
            'content-available' => 1,
        );

        $data_type = array("title" => $title, 'message' => $message, 'content-available' => 1);
        $fields = array('registration_ids' => $registrationIds, 'notification' => $msg, 'data' => $msg, 'priority' => 'high');
        //'notification' => $msg for ios
        //'data' => $data_type for android
        // $headers = array(
        //   'Authorization: key= AAAA7OkHvwU:APA91bG6dZeI8oFMXBL2KteUr4Ct160PoJriSbP4SRoVtMiIKi_LKAz0dGSBkDizrVRvlUO5wPL0dctnkdsF0E6q8zET5-o6HogF5HXEpHQgVrO4LysGIigLdZlXA77YWJpR-3lChYZ0',
        //   'Content-Type: application/json',
        // );
        $headers = array(
            'Authorization: key= AAAA7OkHvwU:APA91bG6dZeI8oFMXBL2KteUr4Ct160PoJriSbP4SRoVtMiIKi_LKAz0dGSBkDizrVRvlUO5wPL0dctnkdsF0E6q8zET5-o6HogF5HXEpHQgVrO4LysGIigLdZlXA77YWJpR-3lChYZ0',
            'Content-Type: application/json',
        );
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        $result = curl_exec($ch);
        curl_close($ch);
    }


    function getAllMessage($key)
    {
        try {
            $data = $this->firebase->getReference("{$this->ref}/{$key}/chat")
                ->orderByKey()
                ->getSnapshot()
                ->getValue();
            return $data;
        } catch (\Throwable $th) {
            echo "getLastMessage Throwable : " . $th;
            // exit;
        }
    }

    function getLastMessage($key)
    {
        try {
            $data = $this->firebase->getReference("{$this->ref}/{$key}/chat")
                ->orderByKey()
                ->limitToLast(1)
                ->getSnapshot()
                ->getValue();
            return $data;
        } catch (\Throwable $th) {
            echo "getLastMessage Throwable : " . $th;
            // exit;
        }
    }

}
