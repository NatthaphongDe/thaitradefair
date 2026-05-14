<?php

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);

include 'confing.php';
include 'class_main.php';
include 'class_getdata.php';

$classmain = new main;
$classgetdata = new getdata;

require  'vendor/autoload.php';

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
            $serviceAccount = ServiceAccount::fromJsonFile(__DIR__ . '/../../stay-in-style-37596-8773f1609caf.json');
            $this->firebase = (new Factory)->withServiceAccount($serviceAccount)->withDatabaseUri($this->project_uri)->createDatabase();
        } catch (\Throwable $th) {
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
            "login_status" => $type
        );

        $user_login = $this->insert("user_login", $data_user_login, true);

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
        }
    }
}

if (isset($_POST['method']) && $_POST['method'] == "CreateUserLogin") {
    $result = array();
    $result['result'] = true;

    $user_id = $_POST['user_id'];
    $user_type = 1; // ($type == 'exhibitor') ? 0 : 1;
    $token = $_POST['token'];
    $notification_token = $_POST['notification_token'];
    $platform = $_POST['platform'];
    $ip = $_SERVER['REMOTE_ADDR'];
    $type = 1;

    $data_user_login = array(
        "login_user_id" => $user_id,
        "login_user_type" => $user_type,
        "login_token" => $token,
        "login_notification_token" => $notification_token,
        "login_platform" => $platform,
        "login_logintime" => "NOW()",
        "login_ip" => $ip,
        "login_status" => $type
    );

    $user_login = $classmain->insert("user_login", $data_user_login);

    if ($user_login) {
        unset($_SESSION['user_log']);
        $result['result'] = true;
    } else {
        $result['result'] = false;
    }

    echo json_encode($result);
    exit();
}
// TODO
else if (isset($_POST['method']) && $_POST['method'] == "GetMessageRoom") {
    $result = array();
    $result["result"] = true;

    $params = new stdClass();
    $params->user_id = $_POST["user_id"];
    $params->company_id = $_POST["company_id"];

    $select_user = array();
    $where_user = array(
        "user_id" => $params->user_id
    );
    $chatroom_user = $classmain->select($select_user, "chat_log", $where_user);

    $select_company = array();
    $where_company = array(
        "user_id" => $params->company_id
    );
    $chatroom_company = $classmain->select($select_company, "chat_log", $where_company);

    $room_id = '';
    foreach ($chatroom_company->data as $key_company => $value_company) {
        $arr_index = array_search($value_company["room_id"],  array_column($chatroom_user->data, "room_id"));
        if ($value_company["room_id"] == $chatroom_user->data[$arr_index]["room_id"]) {
            $room_id = $chatroom_user->data[$arr_index]["room_id"];
        }
    }

    $select = array();
    $where = array(
        "room_id" => $room_id
    );
    $chatroom = $classmain->select($select, "chat_room", $where);

    $room_key = "";
    if ($chatroom->num_rows > 0) {
        $room_key = $chatroom->data[0]["room_key"];
    }
    $result["room_key"] = $room_key;

    // if (!$room_key) {
    //     $result['result'] = false;
    // }

    echo json_encode($result);
    exit();
}
// TODO
else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "CreateMessageRoom") {

    $result = array();
    $result['result'] = true;

    $params = new stdClass();
    $params->user_id = $_POST['user_id'];
    $params->company_id = $_POST['company_id'];
    $params->room_key = $_POST['room_key'];
    $params->room_type = $_POST['room_type'];

    // chat_room
    $data_chat_room = array(
        "room_name" => $params->company_id . "/" . $params->user_id,
        "room_key" => $params->room_key,
        "room_date" => date("Y-m-d H:i:s"),
        "room_timezone" => "Asia/Bangkok",
        "room_offset" => "+07:00",
        "room_type" => $params->room_type
    );
    $chat_room = $classmain->insert("tt_chat_room", $data_chat_room);

    $data_chat_room_company = array(
        "room_id" => $chat_room,
        "user_id" => $params->company_id,
        "user_type" => 0,
        "isRead" => 0
    );
    $chat_room_company = $classmain->insert("tt_chat_history", $data_chat_room_company);

    $data_chat_room_user = array(
        "room_id" => $chat_room,
        "user_id" => $params->user_id,
        "user_type" => 1,
        "isRead" => 1
    );
    $chat_room_user = $classmain->insert("tt_chat_history", $data_chat_room_user);

    echo json_encode($result);
    exit();
}
// TODO
else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "GetCompanyById") {
    $result = array();
    $result['result'] = true;
    $result['data'] = array();

    $company = $classgetdata->Get_company_by_id($_POST['company_id'], $_POST['room_type']);
    if (count($company) > 0) {

        // $image = $classgetdata->get_company_img($company[0]['company_id']);

        $company[0]['path_img'] = "";
        $company[0]['company_img_name'] = "";
        // if (count($image) >0) {
        //     $company[0]['path_img'] = $image[0]['path_img'];
        //     $company[0]['company_img_name'] = $image[0]['company_img_name'];
        // }

        $result['result'] = true;
        $result['data'] = $company;
    } else {
        $result['result'] = false;
    }
    echo json_encode($result);
    exit();
}
