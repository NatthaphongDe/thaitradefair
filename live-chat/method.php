<?php
include 'confing.php';
include 'class_main.php';
include 'class_getdata.php';
// include '../../assest/libiry/PHPMailer-5.2.5/class.phpmailer.php';
ini_set('display_errors', 0);
ini_set('display_startup_errors', 0);
error_reporting(E_ALL);

$classmain = new main;
$classgetdata = new getdata;


function hash_pwd($pwd)
{
  return password_hash($pwd, PASSWORD_BCRYPT, ["cost" => 8]);
}


function verify_passeword($pwd, $hash)
{
  //$hash get from DB
  //$pwd is password that's send from user
  if (password_verify($pwd, $hash)) {
    echo 'Password is valid!';
  } else {
    echo 'Invalid password.';
  }
}



if (isset($_REQUEST['method']) && $_REQUEST['method'] == "blog") {
  $DATA_POST = $classmain->escape_string($_REQUEST);
  $blog = $classgetdata->Get_blog($DATA_POST['Limlt'], $DATA_POST['text-search']);
  $num = $classgetdata->Get_blog_num($DATA_POST['text-search']);
  $arr = array(
    'data' => $blog, 'num' => $num
  );
  echo json_encode($arr);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "verifyemail") {

  $result = array();
  $result['result'] = true;

  $email = $_POST['email'];

  $data_user = $classgetdata->get_user_account(base64_decode($email));

  if (!$data_user['user_id']) {
    $result['result'] = true;
  } else {
    $verify_email = $classgetdata->verify_email(base64_decode($email));
    if ($verify_email) {
      $result['result'] = true;
    }
  }

  echo json_encode($result);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "loginSignup") {

  $result = array();
  $result['result'] = true;
  $result['email'] = $_REQUEST['email'];
  $result['message'] = '';

  $data_profile = array(
    "email" => trim($_REQUEST['email']),
    "password" => hash_pwd(trim($_REQUEST['password'])),
    "re_password" => trim($_REQUEST['re_password']),
    "status_user" => '0',
    "company_name" => $_REQUEST['company'],
    "fullname" => $_REQUEST['fullname'],
    "country_id" => $_REQUEST['country'],
    // "telephone" => $_REQUEST['telephone'],
  );
  $table = "user_account";

  // check fill
  foreach ($data_profile as $key => $value) {
    if ($key != "company_name" && trim($value) == "") {
      $result['result'] = false;
      $result['message'] = "Please fill in information.";
      echo json_encode($result);
      exit();
    }
  }

  $new_password = trim($_REQUEST['password']);
  $confirm_new_password = trim($_REQUEST['re_password']);

  if ($new_password == "" || $confirm_new_password == "") {
    $result['result'] = false;
    $result['message'] = "Please fill in information.";
    echo json_encode($result);
    exit();
  }

  if (strlen($new_password) < 8) {
    $result['result'] = false;
    $result['message'] = "You have to enter at least 8 digit!";
    echo json_encode($result);
    exit();
  }

  if ($new_password != $confirm_new_password) {
    $result['result'] = false;
    $result['message'] = "These passwords don't match.";
    echo json_encode($result);
    exit();
  }

  // Check Email
  $email = $classgetdata->get_user_account(trim($_REQUEST['email']));
  if ($email['email']) {
    $result['result'] = false;
    $result['message'] = "Someone already has this email address.";
    echo json_encode($result);
    exit();
  }

  $success = $classgetdata->insert_profile($table, $data_profile);

  $params = new stdClass();
  $params->email = $_POST['email'];
  $params->fullname = $_POST['fullname'];
  $message = getMessageSignin($params);
  $mail = sendEmail($params->email, $params->fullname, $params->email, $params->fullname, $message['subject'], $message['body'], 0);
  echo json_encode($result);
  exit();
} else if (isset($_POST['method']) && $_POST['method'] == "resendmailSignin") {
  $result = array();
  $result['result'] = true;

  $params = new stdClass();
  $params->email = $_POST['email'];

  $profile = $classmain->select(array(), "user_account", array("email" => $params->email));
  if ($profile->num_rows > 0) {
    $params->fullname = $profile->data[0]['fullname'];
    $message = getMessageSignin($params);

    $mail = sendEmail($params->email, $params->fullname, $params->email, $params->fullname, $message['subject'], $message['body']);
  }

  echo json_encode($mail);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "loginForgotpassword") {
  $result = array();
  $result['result'] = true;
  $result['message'] = '';

  $email = $_POST['email'];
  // print_r($email);
  // exit;
  $profile = $classgetdata->get_user_account($email);
  // if ($email == "wannapa-milk@hotmail.com") {
  //  $profile['email'] = "wannapa-milk@hotmail.com";
  //  $profile['fullname'] = "ทดสอบ";
  // }

  if (!isset($profile['email'])) {
    $result['result'] = false;
    echo json_encode($result);
    exit;
  }

  $subject = "Sign Up : Stay in Style Bangkok";

  $message = "<div style='/*padding: 30px 150px 80px;*/ padding-bottom: 30px; width: 100%; background-color: #F9F9F9;'>";
  $message .= "<div style='padding-top: 10px; background-color: #ffdd00; border-radius: 5px;'>";

  // Body
  $message .= "<div style='background-color: rgb(255, 255, 255, 1); padding: 10px 30px;'>";

  $message .= "<div style='margin: 30px 0px; text-align: center;'>";
  $message .= "<img src='../../assest/img/Logo-Stay-in-Style-New-2020-02.png' alt='STAY IN STYLE BANGKOK' style='width: 250px;'>";
  $message .= "</div>";

  $message .= "<div style='font-size: 18px; margin-bottom: 30px;'><label>Hi <span style='font-weight: bold;'>" . $profile['fullname'] . "</span></div>";
  $message .= "<div style='font-size: 18px; margin-bottom: 5px;'><label>เราได้รับคำขอการเปลี่ยนรหัสผ่านใหม่ของคุณเรียบร้อยแล้ว</label></div>";
  $message .= "<div style='font-size: 18px; margin-bottom: 5px;'><label>เปลี่ยนรหัสผ่านใหม่ <a href='https://www.stayinstylebangkok.com?changepassword=" . base64_encode($email) . "' target='_blank' style='color: #ffdd00;'>ที่นี่</a> หรือคัดลอก Link ข้างล่างนี้ เพื่อนำไปเปิดบนเว็บเบราว์เซอร์</label></div>";

  $message .= "<div style='margin-bottom: 30px;'>";
  $message .= "<a href='https://www.stayinstylebangkok.com?changepassword=" . base64_encode($email) . "' target='_blank'>https://www.stayinstylebangkok.com?changepassword=" . base64_encode($email) . "</a>";
  $message .= "</div>";

  $message .= "<div style='margin-bottom 10px;'>";
  $message .= "<label style='font-size: 18px;'>Regards,</label>";
  $message .= "</div>";

  $message .= "<div style='margin-bottom 10px;'>";
  $message .= "<label style='font-size: 18px;'>Stay in Style Bangkok Team</label>";
  $message .= "</div>";

  $message .= "<hr style='margin:30px 0px;'>";

  $message .= "<div style='text-align: center; padding-bottom: 30px;'>";
  $message .= "<img src='../../assest/img/sty_04.png' alt='sty_04' style='height: 25px; margin-right: 15px;'>";
  $message .= "<img src='../../assest/img/sty_01.png' alt='sty_01' style='height: 25px; margin-right: 15px;'>";
  $message .= "<img src='../../assest/img/sty_02.png' alt='sty_02' style='height: 25px; margin-right: 15px;'>";
  $message .= "<img src='../../assest/img/sty_03.png' alt='sty_03' style='height: 25px; margin-right: 15px;'>";
  $message .= "</div>";

  $message .= "</div>";
  $message .= "</div>";
  // End Body

  // Footer
  $message .= "<div style='margin-top: 30px; text-align: center; color: #9B9B9B;'>";

  $message .= "<label style='display: block;'>For further information Department of International Trade Promotion.</label>";
  $message .= "<label style='display: block;'>Ministry of Commerce. Office of Lifestyle Trade Promotion</label>";
  $message .= "<label style='display: block; font-weight: bold;'>DITP Call Center 1669</label>";

  $message .= "<div style='margin-top: 10px;'>";
  $message .= "<img src='../../assest/img/facebook.png' style='margin-right: 5px; width: 20px;'>";
  $message .= "<img src='../../assest/img/pinterest.png' style='margin-right: 5px; width: 20px;'>";
  $message .= "<img src='../../assest/img/twitter.png' style='margin-right: 5px; width: 20px;'>";
  $message .= "<img src='../../assest/img/instagram.png' style='margin-right: 5px; width: 20px;'>";

  $message .= "</div>";
  // End Footer

  $message .= "</div>";
  $message .= "</div>";
  // file_exits();
  // var_dump();


  // var_dump(file_exists("../../assest"));
  // echo __DIR__;
  // exit;
  // echo $message; exit;

  $body = $message;
  $from_email = $email;
  $form_name = $email;
  $to_email = $email;
  $to_name = $email;

  $mail = sendEmail($from_email, $profile['fullname'], $to_email, $profile['fullname'], $subject, $message);

  if ($mail == "00") {

    // update reset_password
    $data_update = array(
      "reset_password" => 1
    );
    $where_update = array(
      "email" => $email
    );
    // $update = $classmain->update("user_account", $data_update, $where_update);

    $result['result'] = true;
    $result['message'] = '';
  } else {
    $result['result'] = false;
    $result['message'] = '';
  }

  echo json_encode($result);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == 'check_resetpassowrd') {
  $result = array();
  $result['result'] = true;

  $email = base64_decode($_REQUEST['email']);
  $email = $classmain->select(array("reset_password"), "user_account", array("email" => $email));

  if ($email->data[0]['reset_password']) {
    $result['result'] = true;
  } else {
    $result['result'] = false;
  }

  echo json_encode($result);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == 'resetpassword') {
  $result = array();
  $result['result'] = true;
  $result['message'] = '';

  $params = new stdClass();
  $new_password = trim($_POST['new_password']);
  $confirm_new_password = trim($_POST['confirm_new_password']);

  if ($new_password == "" || $confirm_new_password == "") {
    $result['result'] = false;
    $result['message'] = "Please fill in information.";
    echo json_encode($result);
    exit();
  }

  if (strlen($new_password) < 8) {
    $result['result'] = false;
    $result['message'] = "You have to enter at least 8 digit!";
    echo json_encode($result);
    exit();
  }

  if ($new_password != $confirm_new_password) {
    $result['result'] = false;
    $result['message'] = "These passwords don't match.";
    echo json_encode($result);
    exit();
  }

  $params->email = base64_decode($_POST['email']);
  $params->password = hash_pwd($new_password);
  $params->re_password = $new_password;

  $data_update = array(
    "password" => $params->password,
    "re_password" => $params->re_password,
    "reset_password" => ""
  );
  $where_update = array(
    "email" => $params->email
  );

  $update = $classmain->update("user_account", $data_update, $where_update);

  echo json_encode($result);
  exit();
  // TODO LOGIN
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "loginSignin") {
  $email = trim($_POST['email']);
  $re_password = trim($_POST['password']);
  $password = hash_pwd($_POST['password']);

  $result = array();
  $result['result'] = true;
  $result['email'] = $email;
  $result['message'] = 'login successful';

  if ($email == "" || $re_password == "") {
    $result['result'] = false;
    $result['message'] = "Please fill in information.";
    echo json_encode($result);
    exit();
  }

  $data_user = $classgetdata->get_user_account($email, $re_password);
  if (!$data_user['email']) {
    $result['result'] = false;
    $result['message'] = 'Sorry, wrong username or password.';
    echo json_encode($result);
    exit();
  }

  if ($data_user['status_user'] == 0) {
    $result['result'] = false;
    $result['message'] = 'Please confirm your e-mail!';
    echo json_encode($result);
    exit();
  }

  $verify_password = verify_passeword($re_password, $data_user['password']);

  if ($verify_password == "Invalid password.") {
    ob_end_clean();
    $result['result'] = false;
    $result['message'] = 'Sorry, wrong username or password.';
    echo json_encode($result);
    exit();
  } else {
    ob_end_clean();
  }

  $user_image = $classgetdata->get_profile_image($data_user['user_id']);

  $_SESSION['user_log'] = true;
  $_SESSION['user_id'] = $data_user['user_id'];
  $_SESSION['user_email'] = $data_user['email'];
  $_SESSION['user_fullname'] = $data_user['fullname'];
  $_SESSION['user_company_name'] = $data_user['company_name'];
  $_SESSION['user_image'] = $user_image['user_img_name'];

  echo json_encode($result);
  exit();
}
// TODO LOGOUT
else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "logout") {
  $result = array();
  $result['result']  = true;

  unset($_SESSION['user_id']);
  unset($_SESSION['user_email']);
  unset($_SESSION['user_fullname']);
  unset($_SESSION['user_company_name']);

  echo json_encode($result);
  exit();
  // TODO EXHIBITOR
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "exhibitor") {
  $DATA_POST = $classmain->escape_string($_REQUEST);

  if ($DATA_POST['page'] != 1) {
    $lim = ($DATA_POST['page'] - 1) * $DATA_POST['limit_page'];
  } else {
  }

  // if($DATA_POST['type_search']==1 || $DATA_POST['type_search']==2){
  $datasearch  = array(
    'year'         => trim($DATA_POST['year']),
    'search_text'  => trim($DATA_POST['search-text']),
    'Booth_No'     => trim($DATA_POST['Booth-No']),
    'Product'      => trim($DATA_POST['Product']),
    'Brands'       => trim($DATA_POST['Brands']),
    'Company'      => trim($DATA_POST['Company']),
    'type_search'  => trim($DATA_POST['type_search']),
    'limit_page'   => trim($DATA_POST['limit_page'])
  );

  // }

  $blog = $classgetdata->Get_exhibitor($lim, $datasearch);
  $num  = $classgetdata->Get_exhibitor_num($datasearch);
  $arr = array(
    'data' => $blog, 'num' => $num
  );


  echo json_encode($arr);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "addcompany") {
  $DATA_POST = $classmain->escape_string($_REQUEST);
  $zone = $classgetdata->addcompany($DATA_POST['company']);
  echo json_encode($zone);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "product") {
  $DATA_POST  = $classmain->escape_string($_REQUEST);
  $img        =  $DATA_POST['img'];
  $search     =  $DATA_POST['Product-input'];
  $zone       = $classgetdata->Get_product($img, $search, array(), '', '1');
  echo json_encode($zone);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "zone") {
  $DATA_POST  = $classmain->escape_string($_REQUEST);
  if ($DATA_POST['check'] == '1') {
    $page = 'Showcase';
  } else if ($DATA_POST['check'] == '2') {
    $page = 'DitpServices';
  }


  $zone = $classgetdata->Get_zone($page);
  echo json_encode($zone);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "Exhibitors-img-company") {
  $DATA_POST = $classmain->escape_string($_REQUEST);

  $zone = $classgetdata->get_company_img($DATA_POST['id']);
  echo json_encode($zone);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "all_search") {
  $all_search = $classgetdata->all_search();
  echo json_encode($all_search);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "qrcode") {
  // $all_search = $classgetdata->all_search();
  $DATA_POST = $classmain->escape_string($_REQUEST);

  // print_r($_POSTp);
  $event = 'STYLE BANGKOK OCTOBER 2019';
  $postfields = array('email' => $DATA_POST['text'], 'event' => $event);
  $url = 'http://ditpall.ibusiness.co.th/v5/GetQrCode';
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('application/x-www-form-urlencoded; charset=UTF-8'));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

  $result = curl_exec($ch);
  $err = curl_error($ch);
  curl_close($ch);
  if ($err) {
    // echo "Error  ".$err;
  } else {
    // echo $result;
  }

  echo ($result);
  exit();
}
// TODO ALL SEARCH
else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "all_all_search") {
  $DATA_POST = $classmain->escape_string($_REQUEST);

  $all_search = $classgetdata->all_all_search($DATA_POST['text']);
  echo json_encode($all_search);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "get_company") {
  $DATA_POST = $classmain->escape_string($_REQUEST);

  $Get_company = $classgetdata->Get_company('', $DATA_POST['search_text']);

  echo json_encode($Get_company);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "product_img") {
  $DATA_POST = $classmain->escape_string($_REQUEST);

  if ($DATA_POST['page'] != 1) {
    $lim = ($DATA_POST['page'] - 1) * $DATA_POST['limit_page'];
  } else {
  }
  $datasearch  = array(
    'cat_id'        => trim($DATA_POST['cat_id']), 'search_text'  => trim($DATA_POST['search_text']), 'order'        => trim($DATA_POST['order']), 'limit_page'   => trim($DATA_POST['limit_page'])

  );


  $category_img = $classgetdata->get_product_category_img($lim, $datasearch);
  $img_num = $classgetdata->get_product_category_img_num($datasearch);
  $arr = array(
    'data' => $category_img, 'num' => $img_num
  );

  echo json_encode($arr);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "ContactUs") {


  // if ($_POST['g-recaptcha-response'] == '') {
  //   alert_text('Please verify your Captcha', 'error');
  //   exit();
  // }

  // print_r($_POST);
  // exit();
  // $url = "https://www.google.com/recaptcha/api/siteverify";
  // $data = [
  //   'secret' => "6LdWh9UUAAAAAKY2S7ChC3vwnrgYWfbW8MNfn3sD",
  //   'response' => $_POST['g-recaptcha-response'],
  // ];

  // $options = array(
  //   'http' => array(
  //     'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
  //     'method'  => 'POST',
  //     'content' => http_build_query($data)
  //   )
  // );

  // $context  = stream_context_create($options);
  // $response = file_get_contents($url, false, $context);

  // $res = json_decode($response, true);
  // // print_r($res);
  // if ($res['success'] != true) {
  //   alert_text('Error! You are not a human.', 'error');
  //   exit();
  // }
  // exit();

  $DATA_POST = $classmain->escape_string($_REQUEST);



  if ($DATA_POST['General'] == 1) {
    $spam_contact = $classgetdata->get_spam_contact($DATA_POST['Email-Contact']);
    if ($spam_contact > 0) {
      $deprive = 1;
    } else {
      $deprive = 0;
    }
  } else {
    $deprive = 0;
  }

  // exit();

  // if ($DATA_POST['session_id'] != session_id()) {
  //   alert_text('You have not permission.', 'error');
  //   exit();
  // }


  $Get_office = $classgetdata->Get_office();
  if ($DATA_POST['page-name'] == '') {
    $contact_type = 1;
    $company_id   = 0;
  } else {
    $contact_type = 2;
    $company_id   = $DATA_POST['company-id'];
    $Tel_Contact = "<b>Tel : </b>" . $DATA_POST['Tel-Contact'];
  }
  if ($DATA_POST['General'] == '') {
    $DATA_POST['General'] = 0;
  } else {
    if ($DATA_POST['General'] == 1) {
      $General = '<b>Contact Type : </b> General contact';
    } else {
      $General = '<b>Contact Type : </b> Contact as an exhibitor';
    }
  }
  if ($DATA_POST['Country'] == '') {
    $DATA_POST['Country'] = 0;
  } else {
    $country  = $classgetdata->Get_country($DATA_POST['Country']);
    $textcountry =  '<b>Country : </b>' . $country['country_name'];
  }
  if ($DATA_POST['businesstype'] == '') {
    $DATA_POST['businesstype'] = 0;
  } else {
    $businesstype  = $classgetdata->Get_businesstype($DATA_POST['businesstype']);
    $textbusinesstype =  '<b>Business type : </b>' . $businesstype['businesstype_name'];
  }
  if ($DATA_POST['InterestedProducts'] == '') {
    $DATA_POST['InterestedProducts'] = 0;
  } else {
    $InterestedProducts  = $classgetdata->Get_InterestedProducts($DATA_POST['InterestedProducts']);
    $textInterested =  '<b>Interested Products : </b>' . $InterestedProducts['name'];
  }
  $array = array(
    'contact_type'      => $contact_type, 'company_id'       => $company_id, 'contact_name'     => $DATA_POST['Name-Contact'], 'country_id'       => $DATA_POST['Country'], 'businesstype_id'  => $DATA_POST['businesstype'], 'Interesting_id'   => $DATA_POST['InterestedProducts'], 'contact_email'    => $DATA_POST['Email-Contact'], 'contact_message'  => $DATA_POST['Message-Contact'], 'contact_phone'    => $DATA_POST['Tel-Contact'], 'contact_title'    => $DATA_POST['General'], 'contact_deprive'  => $deprive
  );

  if ($classmain->insert('contact', $array)) {
    $from_email = $DATA_POST['Email-Contact'];
    $from_name  = $DATA_POST['Name-Contact'];
    $to_name    = 'ผู้ดูแลระบบ';
    if ($contact_type == 1) {
      $subject    = 'Stay in Style Bangkok';
      $to_email   = trim($Get_office['email']);
    } else {
      $subject  = "มีคนสนใจสินค้าของคุณจาก www.stayinstylebangkok.com";
      $to_email = $DATA_POST['company-email'];
    }
    $to_email = 'anuchai.t@ibusiness.co.th';

    $DATA_POST['Message-Contact'] = str_replace('\r\n', '<br>', $DATA_POST['Message-Contact']);
    $DATA_POST['Message-Contact'] = '<b>Message : </b>' . $DATA_POST['Message-Contact'];

    $message    = '<link href="https://fonts.googleapis.com/css?family=Kanit:300,400,500&display=swap" rel="stylesheet">
                    <div class="" style="padding: 10px;font-family: "Kanit", sans-serif; font-weight: 300;">
                    <div style="border: 1px solid #ffefbb;">
                      <div style="background: #ffefbb;padding: 10px;">
                        <div>
                          <b>' . $from_name . '</b>
                        </div>
                        <div>
                          <b>' . $from_email . '</b>
                        </div>
                      </div>
                      <div style="padding: 20px;">
                        <div  style="padding-bottom: 20px;">
                        <div>' . $General . '</div>
                        <div>' . $textcountry . '</div>
                        <div>' . $textbusinesstype . '</div>
                        <div>' . $textInterested . '</div>
                        <div>' . $DATA_POST['Message-Contact'] . '</div>
                        <div>' . $Tel_Contact . '</div>
                        </div>
                        <div style="text-align: right;">
                        <a href="' . $_SERVER['HTTP_HOST'] . '" target="_blank"><img src="' . $_SERVER['HTTP_HOST'] . '/assest/img/Logo-Stay-in-Style-New-2020-02.png" style="width: 100px;"></a>
                        <a href="' . $_SERVER['HTTP_HOST'] . '" target="_blank"><img src="' . $_SERVER['HTTP_HOST'] . '/assest/img/footer-2-1.png" style="margin-left: 5px;width: 100px;"></a>
                        </div>
                      </div>
                    </div>
                  </div>';

    if ($to_email != '') {
      if (sendEmail($from_email, $from_name, $to_email, $to_name, $subject, $message) == 00) {
        alert_text('The email has already been sent', 'success', 'load');
      } else {
        alert_text('The email has already been not sent', 'error');
      }
    } else {
      alert_text('The email has already been sent', 'success', 'load');
    }
  } else {
    alert_text('Failed to send email', 'error');
  }


  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == "update_profile") {
  $result = array();
  $result['result'] = true;
  $result['message'] =  '';

  if ($_FILES['file_cont'] && $_POST['hdf_file_count']) {

    // Check file size
    if ($_FILES["file_cont"]["size"] > 10485760) {
      $uploadOk = 0;
    }

    $target_dir = "../../data/profile";
    if (!file_exists($target_dir)) {
      mkdir($target_dir, 0777, true);
    }

    $file_name = $_POST['hdf_user_id'] . "_" .  basename($_FILES["file_cont"]["name"]);

    $target_file = $target_dir . "/" . $file_name;

    if (move_uploaded_file($_FILES["file_cont"]["tmp_name"], $target_file)) {



      // delete
      $where_delete = array(
        "user_id" => $_SESSION['user_id']
      );
      $delete_img = $classmain->delete("user_img", $where_delete);

      // insert DB
      $data_img = array(
        "user_id" => $_SESSION['user_id'],
        "user_img_name" => $file_name,
      );
      $_SESSION['user_image'] = $file_name;
      // insert
      $insert_img = $classmain->insert("user_img", $data_img);
    }
  }

  $params = array();
  $params['address'] = $_POST['address_p'];

  $where = array();
  $where['user_id'] = $_POST['hdf_user_id'];

  $data = $classmain->update("user_account", $params, $where);

  if ($data) {
    $result['result'] = true;
    $result['message'] =  '';
  } else {
    $result['result'] = false;
    $result['message'] =  '';
  }

  echo '<script>';
  echo 'location.href="/profile"';
  echo '</script>';
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == 'setting_time') {

  $params = new stdClass();
  $params->start_time = $_POST['start_time'];
  $params->end_time = $_POST['end_time'];

  $time_tmp = array();
  $result = array();
  $result['time_zone'] = '';
  $result['GMT'] = '';
  $result['time'] = array();

  $time = Plus30Min($params->start_time, $params->end_time);

  $array_time = array();
  $array_time_tmp = array();


  foreach ($time_tmp as $key => $value) {

    array_push($array_time_tmp, $value);

    // $timezone = 'America/New_York';
    // date_default_timezone_set($timezone);
    $stored_time = date($value);
    $timestamp = strtotime($stored_time);
    $date = new DateTime($stored_time);
    $tz = $date->getTimezone();

    $local_time = $timestamp + date('Z');

    if ($tz->getName() == 'Asia/Bangkok') {

      array_push($array_time, date('H:i', $timestamp));
    } else {

      if (date('H', $local_time) > 12) {
        $h = (date('H', $local_time) - 12) . date(':ia', $local_time);
      } else {
        $h = (date('H', $local_time)) . date(':ia', $local_time);
      }

      array_push($array_time, $h);
    }

    if ($result['GMT'] == '') {
      $result['GMT'] = substr(date_format($date, "O"), 0, 1) . ((int)substr(date_format($date, "O"), 1, 2));
    }
  }


  $result['time_zone_full'] = $tz->getName();
  $result['time_zone'] = str_replace("_", " ", explode("/", $tz->getName())[1]);
  $result['time'] = $array_time;
  $result['time_old'] = $array_time_tmp;

  echo json_encode($result);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == 'transfer_meeting') {

  $result = array();
  $result['result'] = true;
  $result['message'] = '';

  $params = new stdClass();
  $params->date_time = $_POST['date_time'];
  $params->user_id = $_POST['user_id'];
  $params->user_company = $_POST['company_id'];
  $timezone = $_POST['time_zone'];

  date_default_timezone_set($timezone);
  $timestamp = strtotime($params->date_time);
  $date = new DateTime($params->date_time);
  $tz = $date->getTimezone()->getName();
  $local_time = $timestamp + date('Z');
  if ($tz == 'Asia/Bangkok') {
    $local_datetime = date('H:i', $timestamp);
  } else {
    if (date('H', $local_time) > 12) {
      $h = (date('H', $local_time) - 12) . date(':ia', $local_time);
    } else {
      $h = (date('H', $local_time)) . date(':ia', $local_time);
    }
    $local_datetime = $h;
  }

  $params->time_zone = $tz;

  $params->gmt = substr(date_format($date, "O"), 0, 1) . ((int)substr(date_format($date, "O"), 1, 2));

  $where = array(
    // "request_datetime" => $params->date_time,
    "user_id" => $params->user_id,
    "user_company" => $params->user_company,
  );

  $result['data'] = array();
  $result['data']['id_meet'] = date_format(new DateTime(), "mdHis");
  $result['data']['time_zone'] = explode("/", $params->time_zone)[1];
  $result['data']['local_time'] = $local_datetime;
  $result['data']['gmt'] = $params->gmt;

  $date_format = date_format(new DateTime($params->date_time), "M d, Y");
  $result['data']['local_date'] = $date_format;

  $thai_format = date_format(new DateTime($params->date_time), "H:i:s");
  $result['data']['thai_date'] = $date_format . ' ' . $thai_format;

  $request_datetitme = $params->date_time;

  if ($tz != "Asia/Bangkok") {

    $tmp_stored_time = date($params->date_time);
    $tmp_timestamp = strtotime($tmp_stored_time);
    $tmp_date = new DateTime($tmp_stored_time);

    $timezone = 'Asia/Bangkok';
    $tmp_date->setTimezone(new DateTimeZone($timezone));

    $thai_format = date_format(new DateTime($tmp_date->format("Y-m-d H:i:s")), "H:i:s");
    $result['data']['thai_date'] = $date_format . ' ' . $thai_format;

    $request_datetitme = $tmp_date->format("Y-m-d H:i:s");

    $tmp_stored_time = date($params->date_time);
    $tmp_timestamp = strtotime($tmp_stored_time);
    $tmp_date = new DateTime($tmp_stored_time);
    $timezone = 'Asia/Bangkok';
    $tmp_date->setTimezone(new DateTimeZone($timezone));
  }

  $data = array(
    "id_meet" => date_format(new DateTime(), "mdHis"),
    "request_datetime" => $request_datetitme,
    "user_id" => $params->user_id,
    "user_company" => $params->user_company,
    "create_date" => "NOW()",
    "time_zone" => $params->time_zone,
    "GMT" => $params->gmt,
    "status_meet" => 0
  );
  $result['insert'] = $data;

  echo json_encode($result);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == 'getarray_time') {
  // echo json_encode($_REQUEST['method']);
  // exit();
  $result = array();
  $result['result'] = true;
  $result['data'] = array();

  $start_date = $_POST['start_date'];
  $end_date = $_POST['end_date'];
  $timezone = $_POST['time_zone'];
  $id_m = $_POST['id_m'];
  $status_slot = $_POST['status_slot'];


  if ($id_m && isset($status_slot) & $status_slot == 0) {
    $sup_time_slot = $classgetdata->Get_sup_time_slot($id_m);
  }

  if (count($sup_time_slot) == 0) {
    for ($i = 0; $i <= 6; $i++) {
      $sup_time_slot[$i]['date_type'] = $i + 1;
      $sup_time_slot[$i]['start_time'] = "07:00";
      $sup_time_slot[$i]['end_time'] = "23:00";
    }
  } else {
    $array_date_type = array_column($sup_time_slot, "date_type");
    for ($i = 1; $i <= 7; $i++) {
      if (!in_array($i, $array_date_type)) {
        $array = array();
        $array['date_type'] = $i;
        $array['start_time'] = "07:00";
        $array['end_time'] = "23:00";
        array_push($sup_time_slot, $array);
      }
    }
  }

  $start_stored_time = date($start_date);
  $start_timestamp = strtotime($start_stored_time);
  $start_date = new DateTime($start_stored_time);
  $start_date_tmp = new DateTime($start_stored_time);

  if (!$end_date) {
    $end_date = date('Y-m-d', strtotime('+1 month', time()));
  }

  $end_stored_time = date($end_date);
  $end_timestamp = strtotime($end_stored_time);
  $end_date = new DateTime($end_stored_time);

  $diff = date_diff($start_date, $end_date);

  $array_datetime = array();
  for ($i = 0; $i < ($diff->format("%a") + 2); $i++) {
    if ($i != 0) {
      $start_date->add(new DateInterval('P1D'));
    }
    foreach ($sup_time_slot as $key_time => $array_time) {
      if ($start_date->format('N') == $array_time['date_type']) {

        $time_tmp = array();
        array_push($time_tmp, $start_date->format('Y-m-d') . ' ' . $array_time['start_time']);

        Plus30Min($start_date->format('Y-m-d') . " " . $array_time['start_time'], $start_date->format('Y-m-d') . " " . $array_time['end_time']);

        foreach ($time_tmp as $key_tmp => $value_tmp) {
          $tmp_stored_time = date($value_tmp);
          $tmp_timestamp = strtotime($tmp_stored_time);
          $tmp_date = new DateTime($tmp_stored_time);
          $tmp_date->setTimezone(new DateTimeZone($timezone));

          if (!is_array($array_datetime[$tmp_date->format("Y-m-d")])) {
            $array_datetime[$tmp_date->format("Y-m-d")] = array();
          }

          if ($timezone == 'Asia/Bangkok') {
            array_push($array_datetime[$tmp_date->format("Y-m-d")], $tmp_date->format("H:i"));
          } else {
            $h = $tmp_date->format("H");
            if ($h > 12) {
              $hh = ($h - 12) . $tmp_date->format(":i A");
            } else {
              $hh = ($h) . $tmp_date->format(":i A");
            }
            array_push($array_datetime[$tmp_date->format("Y-m-d")], $hh);
          }
        }
      }
    }
  }

  $result['start_date'] = array_key_first($array_datetime);
  $result['end_date'] = array_key_last($array_datetime);
  $result['diff'] = $diff->format("%a");
  $result['data'] = $array_datetime;
  echo json_encode($result);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == 'delete_meeting') {
  $result = array();
  $result['result'] = true;
  $result['message'] = '';

  $delete = $classmain->delete("request_meeting", array("id_meet" => $_REQUEST['id_meet']));

  echo json_encode($result);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == 'get_request_meeting') {

  $params = new StdClass();
  $params->user_id = $_POST['user_id'];
  $params->user_company = $_POST['company_id'];

  $result = array();
  $result['result'] = true;
  $result['data'] = array();
  $result['data_insert'] = array();

  $where = array(
    "user_id" => $params->user_id,
    "user_company" => $params->user_company,
  );

  $RequestMeeting = $classgetdata->getRequest_meeting($where);

  // echo $RequestMeeting;
  // exit;

  foreach ($RequestMeeting as $key => $value) {
    $tmp = array();
    $timezone = $value['time_zone'];
    $timestamp = strtotime($value['request_datetime']);
    $date = new DateTime($value['request_datetime']);
    $date->setTimezone(new DateTimeZone($timezone));
    $tz = $date->getTimezone()->getName();

    if ($tz == 'Asia/Bangkok') {
      $local_datetime = date('H:i', $timestamp);
    } else {
      $h = $date->format("H");
      if ($h > 12) {
        $hh = ($h - 12) . $date->format(":i A");
      } else {
        $hh = ($h) . $date->format(":i A");
      }
      $local_datetime = $hh;
    }

    $tmp['id_meet'] = $value['id_meet'];
    $tmp['time_zone'] = explode("/", $value['time_zone'])[1];
    $tmp['local_time'] = $local_datetime;
    $tmp['gmt'] = $value['GMT'];

    $date_format = date_format(new DateTime($value['request_datetime']), "M d, Y");

    $tmp['local_date'] = $date_format;

    $thai_format = date_format(new DateTime($value['request_datetime']), "H:i:s");
    $tmp['thai_date'] = $date_format . ' ' . $thai_format;
    $tmp['status_meet'] = $value['status_meet'];

    array_push($result['data'], $tmp);

    // Insert
    $data = array(
      "request_datetime" => $value['request_datetime'],
      "user_id" => $value['user_id'],
      "user_company" => $value['user_company'],
      "create_date" => "NOW()",
      "time_zone" => $value['time_zone'],
      "GMT" => $value['GMT'],
      "status_meet" => 0
    );
    array_push($result['data_insert'], $data);
  }

  echo json_encode($result);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == 'update_meeting') {
  $result = array();
  $result['result'] = true;

  $data_insert    = $_POST['data_insert'];
  $user_id        = $_POST['user_id'];
  $user_company   = $_POST['user_company'];
  $email_company  = $_POST['email_company'];
  $massage        = trim($_POST['message']);

  // DELETE
  $where_delete = array(
    "user_id" => $user_id,
    "user_company" => $user_company,
  );

  // $delete = $classmain->delete("request_meeting", $where_delete);

  $roomNumber = 1;
  $data = $classmain->select(array("room_index"), "request_meeting", array("user_id" => $user_id,  "user_company" => $user_company), $sort = "room_index", $sort_type = "desc", $limit = 1, false);
  $roomNumber = $data->data[0]['room_index'] + 1;

  foreach ($data_insert as $key => $value) {
    $where = array(
      "request_datetime" => $value['request_datetime'],
      "user_id" => $value['user_id'],
      "user_company" => $value['user_company'],
    );

    $data = array(
      "request_datetime" => $value['request_datetime'],
      "user_id" => $value['user_id'],
      "user_company" => $value['user_company'],
      "room_index" => $roomNumber,
      // "create_date" => $value['create_date'],
      "time_zone" => $value['time_zone'],
      "GMT" => $value['GMT'],
      "status_meet" => $value['status_meet']
    );

    // INSERT
    $insert_lastid = $classmain->insert("request_meeting", $data);
  }

  if ($massage) {

    // Delete
    // $where_delete_message_meeting = array(
    //   "user_id" => $user_id,
    //   "user_company" => $user_company,
    // );
    // $delete = $classmain->delete("message_meeting", $where_delete_message_meeting);

    // Insert
    $data_massage_meeting = array(
      "id_meet" => $insert_lastid,
      "massage" => $massage,
      "user_company" => $user_company,
      "user_id" => $user_id
    );
    $insert_massage_meeting = $classmain->insert("message_meeting", $data_massage_meeting);
  }

  $token_key = $classgetdata->getToken($user_company);
  if (count($token_key) > 0) {
    // send noti
    $data_noti = array(
      "notification_type"         => 1, // 0 = chat, 1 = request meeting, 2 = confirm meeting
      "notification_dataId"       => $insert_lastid,
      "notification_title"        => 'Online Meeting Request',
      "notification_messageTh"    => 'คุณได้รับ Request Online Meeting จาก ' . $_SESSION['user_fullname'] . ' กรุณาตรวจสอบและยืนยันเวลานัด',
      "notification_messageEn"    => 'คุณได้รับ Request Online Meeting จาก ' . $_SESSION['user_fullname'] . ' กรุณาตรวจสอบและยืนยันเวลานัด',
      "notification_senderId"     => $user_id,
      "notification_senderType"   => 1,
      "notification_receiverId"   => $user_company,
      "notification_receiverType" => 0,
      "notification_status"       => 1,
      "notification_isRead"       => 0,
    );
    $table_noti = "notification";
    $insert_noti = $classmain->insert($table_noti, $data_noti);


    $noti_title = "Online Meeting Request";
    $noti_message = "คุณได้รับ Request Online Meeting จาก " . $_SESSION['user_fullname'] . " กรุณาตรวจสอบและยืนยันเวลานัด";
    sendnoti($token_key, $noti_title, $noti_message);
  }

  echo json_encode($result);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == 'send_mail_update_meeting') {

// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
  $result['result'] = true;
  $result['message'] = '';
  $email_company  = $_POST['email_company'];

  // TODO send mail to Buyer
  $buyer_from_email     = $email_company;
  $buyer_from_name      = explode("@", $email_company)[0];
  $buyer_to_email       = $_POST['user_email'];
  $buyer_to_name        = $_POST['user_fullname'];
  $buyer_subject        = "Make an online meeting appointment complete";

  $buyer_body = "<div style='/*padding: 30px 150px 80px;*/ padding-bottom: 30px; width: 100%; background-color: #F9F9F9;'>";
  $buyer_body .= "<div style='padding-top: 10px; background-color: #ffdd00; border-radius: 5px;'>";

  // TODO Body to Buyer
  $buyer_body .= "<div style='background-color: white; padding: 10px 30px;'>";

  $buyer_body .= "<div style='margin: 30px 0px; text-align: center;'>";
  $buyer_body .= "<img src='../../assest/img/Logo-Stay-in-Style-New-2020-02.png' alt='STAY IN STYLE BANGKOK' style='width: 250px;'>";
  $buyer_body .= "</div>";

  $buyer_body .= "<div style='font-size: 18px; margin-bottom: 5px;'><label>Hi <span style='font-weight: bold;'>" . $_POST['user_fullname'] . ",</span></div>";
  $buyer_body .= "<div style='font-size: 18px; margin-bottom: 5px;'><label>The exhibitor has received your request for Online Meeting. </label></div>";
  $buyer_body .= "<div style='font-size: 18px; margin-bottom: 5px;'><label>Please wait for appointment confirmation from the exhibitor.</label></div>";
  $buyer_body .= "<div style='font-size: 18px; margin-bottom: 5px;'><label>Thank you for your business.</label></div>";


  $buyer_body .= "<div style='margin-bottom: 30px;'>";
  $buyer_body .= "</div>";

  $buyer_body .= "<div style='margin-bottom: 30px;'>";
  $buyer_body .= "<a href='https://www.stayinstylebangkok.com' target='_blank'>https://www.stayinstylebangkok.com</a>";
  $buyer_body .= "</div>";

  $buyer_body .= "<div style='margin-bottom 10px;'>";
  $buyer_body .= "<label style='font-size: 18px;'>Regards,</label>";
  $buyer_body .= "</div>";

  $buyer_body .= "<div style='margin-bottom 10px;'>";
  $buyer_body .= "<label style='font-size: 18px;'>Stay in Style Bangkok Team</label>";
  $buyer_body .= "</div>";

  $buyer_body .= "<hr style='margin:30px 0px;'>";

  $buyer_body .= "<div style='text-align: center; padding-bottom: 30px;'>";
  $buyer_body .= "<img src='../../assest/img/sty_04.png' alt='sty_04' style='height: 25px; margin-right: 15px;'>";
  $buyer_body .= "<img src='../../assest/img/sty_01.png' alt='sty_01' style='height: 25px; margin-right: 15px;'>";
  $buyer_body .= "<img src='../../assest/img/sty_02.png' alt='sty_02' style='height: 25px; margin-right: 15px;'>";
  $buyer_body .= "<img src='../../assest/img/sty_03.png' alt='sty_03' style='height: 25px; margin-right: 15px;'>";
  $buyer_body .= "</div>";

  $buyer_body .= "</div>";
  $buyer_body .= "</div>";
  // TODO End Body

  // TODO Footer to Buyer
  $buyer_body .= "<div style='margin-top: 30px; text-align: center; color: #9B9B9B;'>";

  $buyer_body .= "<label style='display: block;'>For further information Department of International Trade Promotion.</label>";
  $buyer_body .= "<label style='display: block;'>Ministry of Commerce. Office of Lifestyle Trade Promotion</label>";
  $buyer_body .= "<label style='display: block; font-weight: bold;'>DITP Call Center 1669</label>";

  $buyer_body .= "<div style='margin-top: 10px;'>";
  $buyer_body .= "<img src='../../assest/img/facebook.png' style='margin-right: 5px; width: 20px;'>";
  $buyer_body .= "<img src='../../assest/img/pinterest.png' style='margin-right: 5px; width: 20px;'>";
  $buyer_body .= "<img src='../../assest/img/twitter.png' style='margin-right: 5px; width: 20px;'>";
  $buyer_body .= "<img src='../../assest/img/instagram.png' style='margin-right: 5px; width: 20px;'>";

  $buyer_body .= "</div>";
  // End Footer

  $buyer_body .= "</div>";
  $buyer_body .= "</div>";

  $buyer_mail = sendEmail($buyer_from_email, $buyer_from_name, $buyer_to_email, $buyer_to_name, $buyer_subject, $buyer_body);

  // TODO send mail Exhibitor
  $exhibitor_from_email     = $_POST['user_email'];
  $exhibitor_from_name      = $_POST['user_fullname'];
  $exhibitor_to_email       = $email_company;
  $exhibitor_to_name        = explode("@", $email_company)[0];
  $exhibitor_subject        = "Make an online meeting appointment complete";


  $exhibitor_body = "<div style='/*padding: 30px 150px 80px;*/ padding-bottom: 30px; width: 100%; background-color: #F9F9F9;'>";
  $exhibitor_body .= "<div style='padding-top: 10px; background-color: #ffdd00; border-radius: 5px;'>";

  // TODO Body to Exhibitor
  $exhibitor_body .= "<div style='background-color: white; padding: 10px 30px;'>";

  $exhibitor_body .= "<div style='margin: 30px 0px; text-align: center;'>";
  $exhibitor_body .= "<img src='../../assest/img/Logo-Stay-in-Style-New-2020-02.png' alt='STAY IN STYLE BANGKOK' style='width: 250px;'>";
  $exhibitor_body .= "</div>";

  $exhibitor_body .= "<div style='font-size: 18px; margin-bottom: 5px;'><label>Hi <span style='font-weight: bold;'>" . $exhibitor_to_name . ",</span></div>";
  $exhibitor_body .= "<div style='font-size: 18px; margin-bottom: 5px;'><label>คุณได้รับคำขอเรื่อง Online Meeting จาก " . $_POST['user_fullname'] . "</label></div>";
  $exhibitor_body .= "<div style='font-size: 18px; margin-bottom: 5px;'><label>กรุณาตรวจสอบและยืนยันเวลานัดเพื่อไม่ให้พลาดทุกโอกาสทางธุรกิจ โดย Sign in ผ่าน Link ด้านล่างนี้</label></div>";


  $exhibitor_body .= "<div style='margin-bottom: 30px;'></div>";

  $exhibitor_body .= "<div style='margin-bottom: 30px;'>";
  $exhibitor_body .= "<a href='https://stayinstylebangkok.com/backoffice/Login-Exhibitor/index.php' target='_blank'>https://stayinstylebangkok.com</a>";
  $exhibitor_body .= "</div>";

  $exhibitor_body .= "<div style='font-size: 18px; margin-bottom: 5px;'><label>หรือยืนยันผ่านแอปพลิเคชั่น Stay in Style บนสมาร์ทโฟนของคุณ</label></div>";

  $exhibitor_body .= "<div style='margin-top: 10px;'>";
  $exhibitor_body .= "<img src='../../assest/img/app_store.png'  alt='App Store' style='margin-right: 15px;'>";
  $exhibitor_body .= "<img src='../../assest/img/google_play.png'  alt='Google Play' style='margin-right: 15px;'>";
  $exhibitor_body .= "</div>";

  $exhibitor_body .= "<div style='margin-bottom: 30px;'></div>";

  $exhibitor_body .= "<div style='margin-bottom 10px;'>";
  $exhibitor_body .= "<label style='font-size: 18px;'>Best Regards,</label>";
  $exhibitor_body .= "</div>";

  $exhibitor_body .= "<div style='margin-bottom 10px;'>";
  $exhibitor_body .= "<label style='font-size: 18px;'>Stay in Style Bangkok Team</label>";
  $exhibitor_body .= "</div>";

  $exhibitor_body .= "<hr style='margin:30px 0px;'>";

  $exhibitor_body .= "<div style='text-align: center; padding-bottom: 30px;'>";
  $exhibitor_body .= "<img src='../../assest/img/sty_04.png' alt='sty_04' style='height: 25px; margin-right: 15px;'>";
  $exhibitor_body .= "<img src='../../assest/img/sty_01.png' alt='sty_01' style='height: 25px; margin-right: 15px;'>";
  $exhibitor_body .= "<img src='../../assest/img/sty_02.png' alt='sty_02' style='height: 25px; margin-right: 15px;'>";
  $exhibitor_body .= "<img src='../../assest/img/sty_03.png' alt='sty_03' style='height: 25px; margin-right: 15px;'>";
  $exhibitor_body .= "</div>";

  $exhibitor_body .= "</div>";
  $exhibitor_body .= "</div>";
  // TODO End Body

  // TODO Footer to Exhibitor
  $exhibitor_body .= "<div style='margin-top: 30px; text-align: center; color: #9B9B9B;'>";

  $exhibitor_body .= "<label style='display: block;'>For further information Department of International Trade Promotion.</label>";
  $exhibitor_body .= "<label style='display: block;'>Ministry of Commerce. Office of Lifestyle Trade Promotion</label>";
  $exhibitor_body .= "<label style='display: block; font-weight: bold;'>DITP Call Center 1669</label>";

  $exhibitor_body .= "<div style='margin-top: 10px;'>";
  $exhibitor_body .= "<img src='../../assest/img/facebook.png' style='margin-right: 5px; width: 20px;'>";
  $exhibitor_body .= "<img src='../../assest/img/pinterest.png' style='margin-right: 5px; width: 20px;'>";
  $exhibitor_body .= "<img src='../../assest/img/twitter.png' style='margin-right: 5px; width: 20px;'>";
  $exhibitor_body .= "<img src='../../assest/img/instagram.png' style='margin-right: 5px; width: 20px;'>";

  $exhibitor_body .= "</div>";
  // TODO End Footer

  $exhibitor_body .= "</div>";
  $exhibitor_body .= "</div>";
  // echo $exhibitor_body;exit;
  $exhibitor_mail = sendEmail($exhibitor_from_email, $exhibitor_from_name, $exhibitor_to_email, $exhibitor_to_name, $exhibitor_subject, $exhibitor_body);

  echo json_encode($result);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == 'update_chatroom') {
  $result = array();
  $result['result'] = true;
  $result['message'] = '';

  $keyRoom = $_REQUEST['keyRoom'];

  $date = new DateTime();
  $timeZone = $date->getTimezone();
  $zonename = $timeZone->getName();
  $offset = date('P');
  $now = date("Y-m-d H:i:s");

  $data = array(
    "room_date" => $now,
    "room_timezone" => $zonename,
    "room_offset" => $offset
  );
  $where = array(
    "room_key" => $keyRoom
  );

  $classmain->update("tt_chat_room", $data, $where);

  $room = $classmain->select(array(), "tt_chat_log", array("log_room_key" => $keyRoom), $sort = "log_id", $sort_type = "desc", $limit = 1, false);
  if($room->num_rows == 0){
    $data_noti = array(
      "log_room_key"   => $_REQUEST['keyRoom'],
      "log_user"       => $_REQUEST['user_id'],
      "log_company"    => $_REQUEST['company_id'],
      "log_type"       => $_REQUEST['room_type'],
    );
    $table_noti = "tt_chat_log";
    $insert_noti = $classmain->insert($table_noti, $data_noti);
  }else{
    foreach ($room->data as $key => $value) {
      if($value["log_end"] != '0000-00-00 00:00:00'){
        $data_noti = array(
          "log_room_key"   => $_REQUEST['keyRoom'],
          "log_user"       => $_REQUEST['user_id'],
          "log_company"    => $_REQUEST['company_id'],
          "log_type"       => $_REQUEST['room_type'],
        );
        $table_noti = "tt_chat_log";
        $insert_noti = $classmain->insert($table_noti, $data_noti);
      }
    }
  }
  echo json_encode($result);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == 'noti_chat') {

  $result = array();
  $result['result'] = true;
  $result['message'] = '';

  $user_id = $_REQUEST['user_id'];
  $user_company = $_REQUEST['company_id'];
  $chat_room  = $_REQUEST['chat_room'];

  $chat = $classmain->select(array(), "chat_room", array("room_key" => $chat_room));
  $key = $chat->data[0]['room_id'];

  $get_user = $classmain->select(array(), "user_account", array("user_id" => $user_id));
  $fullname = $get_user->data[0]['fullname'];


  $token_key = $classgetdata->getToken($_REQUEST['company_id']);

  if (count($token_key) > 0) {

    // send noti
    $data_noti = array(
      "notification_type"         => 0, // 0 = chat, 1 = request meeting, 2 = confirm meeting
      "notification_dataId"       => $key,
      "notification_title"        => 'Live Chat',
      "notification_messageTh"    => $fullname . 'ส่งข้อความหาคุณ',
      "notification_messageEn"    => $fullname . 'ส่งข้อความหาคุณ',
      "notification_senderId"     => $user_id,
      "notification_senderType"   => 1,
      "notification_receiverId"   => $user_company,
      "notification_receiverType" => 0,
      "notification_status"       => 1,
      "notification_isRead"       => 0,
    );
    $table_noti = "notification";
    $insert_noti = $classmain->insert($table_noti, $data_noti);
    $noti_title = "Live Chat";
    $noti_message = $fullname . "ส่งข้อความหาคุณ";
    sendnoti($token_key, $noti_title, $noti_message);

    if ($insert_noti) {
      $result['result'] = true;
    } else {
      $result['result'] = false;
    }
  }

  echo json_encode($result);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == 'upload_file_chat') {

  $result = array();
  $result['result'] = true;
  $result['message'] = '';
  $result['data'] = array();

  $user_id = $_COOKIE['ssoid'];
  $upload_dirname_log = $classmain->select(array("dir_name"), "upload_dirname_log", array("dir_user_id" => $user_id, "dir_user_type" => 1));

  $dir_name = '';
  if (empty($upload_dirname_log->data[0]["dir_name"])) {
    $dir_name = preg_replace('/[^A-Za-z0-9\-]/', '', hash_pwd($user_id));
    $insert_upload_dirname_log = $classmain->insert("upload_dirname_log", array("dir_name" => $dir_name, "dir_user_id" => $user_id, "dir_user_type" => 1));
  } else {
    $dir_name = $upload_dirname_log->data[0]["dir_name"];
  }

  $target_path = $_SERVER['DOCUMENT_ROOT'] . "/data/chat/" . $dir_name;

  if (!file_exists($target_path)) {
    mkdir($target_path, 0777, true);
  }

  if ($_REQUEST['type'] == 'photo') {

    $tmp_path = $target_path;
    $target_path = $tmp_path . "/original/";
    $target_path_resize = $tmp_path . "/resize/";

    if (!file_exists($target_path)) {
      mkdir($target_path, 0777, true);
    }
    if (!file_exists($target_path_resize)) {
      mkdir($target_path_resize, 0777, true);
    }

    $type = "." . end(explode(".", $_FILES['file']['name']));

    $target_path_original = $target_path . base64_encode(basename($_FILES["file"]["name"])) . $type;
    $target_path_resize = $target_path_resize . base64_encode(basename($_FILES["file"]["name"])) . $type;

    if (move_uploaded_file($_FILES['file']['tmp_name'], $target_path_original)) {
      resize(320, $target_path_resize, $target_path_original);
    }

    $data = array(
      "path_original" => 'https://'.$_SERVER['HTTP_HOST']."/data/chat/" . $dir_name . "/original/" . base64_encode(basename($_FILES["file"]["name"])) . $type,
      "path_resize" => 'https://'.$_SERVER['HTTP_HOST']."/data/chat/" . $dir_name . "/resize/" . base64_encode(basename($_FILES["file"]["name"])) . $type
    );

    $result['data'] = $data;
    $result['type'] = "photo";
  } else if ($_REQUEST['type'] == 'file') {

    $bytes = $_FILES['file']['size'];
    $mb = number_format($bytes / 1048576, 2) . ' MB';

    $tmp_path = $target_path;
    $target_path = $tmp_path . "/files/";
    if (!file_exists($target_path)) {
      mkdir($target_path, 0777, true);
    }

    $type = ".pdf";
    $target_path_original = $target_path . base64_encode(basename($_FILES["file"]["name"])) . $type;
    move_uploaded_file($_FILES['file']['tmp_name'], $target_path_original);

    $data = array(
      "original_name" => basename($_FILES["file"]["name"]),
      "path_file" => 'https://'.$_SERVER['HTTP_HOST']."/data/chat/" . $dir_name . "/files/" . base64_encode(basename($_FILES["file"]["name"])) . $type,
      "size_file" => $mb
    );
    $result['data'] = $data;
    $result['type'] = "file";
  }

  echo json_encode($result);
  exit();
} else if (isset($_REQUEST['method']) && $_REQUEST['method'] == 'Get_Set_Time_Slot') {
    $result = $classgetdata->Get_Set_Time_Slot($_REQUEST['company_id']);
    echo json_encode($result);
}

function resize($newWidth, $targetFile, $originalFile)
{

  $info = getimagesize($originalFile);
  $mime = $info['mime'];

  switch ($mime) {
    case 'image/jpeg':
      $image_create_func = 'imagecreatefromjpeg';
      $image_save_func = 'imagejpeg';
      break;

    case 'image/png':
      $image_create_func = 'imagecreatefrompng';
      $image_save_func = 'imagepng';
      break;

    case 'image/gif':
      $image_create_func = 'imagecreatefromgif';
      $image_save_func = 'imagegif';
      break;

    default:
      throw new Exception('Unknown image type.');
  }

  $img = $image_create_func($originalFile);
  imagejpeg($img, $targetFile, 65);
  //ImageDestroy($image);
  list($width, $height) = getimagesize($targetFile);


  $newHeight = ($height / $width) * $newWidth;
  $tmp = imagecreatetruecolor($newWidth, $newHeight);
  imagecopyresampled($tmp, $img, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
  if (file_exists($targetFile)) {
    unlink($targetFile);
  }
  $image_save_func($tmp, "$targetFile");
}

// function sendEmail($from_email, $from_name, $to_email, $to_name, $subject = '', $message = '', $debug = 0)
// {

//   $output = "";

//     $body = $message;

//     $mail = new PHPMailer(true);
//     $mail->CharSet = "utf-8";
//     $mail->IsSMTP();
//     $mail->SMTPDebug = 2;
//     // $mail->Timeout = 10000;
//     $mail->SMTPAuth = true;
//     // $mail->SMTPSecure = 'ssl';  // sets the prefix to the servier
//     // $mail->Host = "outgoing.mail.go.th"; // SMTP server
//     // $mail->Port = "465"; // พอร์ท
//     // $mail->Username = "lifestyleunit@ditp.go.th"; // account SMTP
//     // $mail->Password = "Lifestyleunit2556"; // รหัสผ่าน SMTP
//   //   $mail->SMTPOptions = array(
//   //     'ssl' => array(
//   //         'verify_peer' => false,
//   //         'verify_peer_name' => false,
//   //         'allow_self_signed' => true
//   //     )
//   // );
//     $mail->SMTPSecure = 'tls';  // sets the prefix to the servier
//     $mail->Host = "smtp.gmail.com"; // SMTP server
//     $mail->Port = "587"; // พอร์ท
//     // $mail->Username = "stayinstyle.ditp@gmail.com"; // account SMTP
//     // $mail->Password = "style2019"; // รหัสผ่าน SMTP
//     $mail->Username = "stayinstyle2021.ditp@gmail.com"; // account SMTP
//     $mail->Password = "stayinstyle2021"; // รหัสผ่าน SMTP

//     // $mail->SetFrom($from_email, $from_name);
//     $mail->SetFrom("stayinstyle.ditp@gmail.com", "Stay In Style");
//     // $mail->AddReplyTo($from_email, $from_name);
//     $mail->Subject = $subject;
//     $mail->MsgHTML($body);
//     $mail->AddAddress($to_email, $to_name);



//     if (!$mail->Send()) {

//       $output = "01";
//       $status_response = "02";
//       $status_response_text = "Mailer Error!: " . $mail->ErrorInfo;
//     } else {
//       $output = "00";
//       $status_response_text = "Message sent ;)";
//     }

//   return $output;
// }

function sendEmail($from_email, $from_name, $to_email, $to_name, $subject = '', $message = '', $debug = 0)
{
//   $output = "";
//   try {
//
//     $body = $message;
//
//     $mail = new PHPMailer(true);
//     $mail->CharSet = "utf-8";
//     $mail->IsSMTP();
//     $mail->SMTPDebug = $debug;
//     // $mail->Timeout = 10000;
//     $mail->SMTPAuth = true;
//     // $mail->SMTPSecure = 'ssl';  // sets the prefix to the servier
//     // $mail->Host = "outgoing.mail.go.th"; // SMTP server
//     // $mail->Port = "465"; // พอร์ท
//     // $mail->Username = "lifestyleunit@ditp.go.th"; // account SMTP
//     // $mail->Password = "Lifestyleunit2556"; // รหัสผ่าน SMTP
//
//     $mail->SMTPSecure = 'tls';  // sets the prefix to the servier
//     $mail->Host = "smtp.gmail.com"; // SMTP server
//     $mail->Port = "587"; // พอร์ท
//     // $mail->Username = "stayinstyle.ditp@gmail.com"; // account SMTP
//     // $mail->Password = "style2019"; // รหัสผ่าน SMTP
//     $mail->Username = "stayinstyle2021.ditp@gmail.com"; // account SMTP
//     $mail->Password = "stayinstyle2021"; // รหัสผ่าน SMTP
//
//     // $mail->SetFrom($from_email, $from_name);
//     $mail->SetFrom("stayinstyle.ditp@gmail.com", "Stay In Style");
//     // $mail->AddReplyTo($from_email, $from_name);
//     $mail->Subject = $subject;
//     $mail->MsgHTML($body);
//     $mail->AddAddress($to_email, $to_name);
//
//
//
//     if (!$mail->Send()) {
//       $output = "01";
//       $status_response = "02";
//       $status_response_text = "Mailer Error!: " . $mail->ErrorInfo;
//     } else {
//       $output = "00";
//       $status_response_text = "Message sent ;)";
//     }
//   } catch (phpmailerException $e) {
//   } catch (Exception $e) {
//   }
//   return $output;
}




function alert_text($text = '', $type = '', $load = '')
{
?>
  <script type="text/javascript">
    // parent.swal("",,);

    parent.$('.Exhibitor-Contact').modal('hide');
    parent.swal({
      title: "",
      text: "<?php echo $text; ?>",
      type: "<?php echo $type; ?>",
    }, function() {
      <?php
      if ($load == 'load') {
      ?>parent.location.reload();
    <?php
      }
    ?>
    });
  </script>
<?php
}



function getMessageSignin($params)
{

  $fullname = $params->fullname;
  $email = $params->email;

  $result = array();

  $result['subject'] = 'Sign Up : Stay in Style Bangkok';
  $result['body'] = '';

  $message = "<div style='/*padding: 30px 150px 80px;*/ padding-bottom: 30px; width: 100%; background-color: #F9F9F9;'>";
  $message .= "<div style='padding-top: 10px; background-color: #ffdd00; border-radius: 5px;'>";

  // Body
  $message .= "<div style='background-color: white; padding: 10px 30px;'>";

  $message .= "<div style='margin: 30px 0px; text-align: center;'>";
  $message .= "<img src='../../assest/img/Logo-Stay-in-Style-New-2020-02.png' alt='STAY IN STYLE BANGKOK' style='width: 250px;'>";
  $message .= "</div>";

  $message .= "<div style='font-size: 18px; margin-bottom: 5px;'><label>Hi <span style='font-weight: bold;'>" . $fullname . ",</span></div>";
  $message .= "<div style='font-size: 18px; margin-bottom: 5px;'><label>Thank you for creating a Stay in Style Bangkok account.</label></div>";
  $message .= "<div style='font-size: 18px; margin-bottom: 5px;'><label>To continue, please confirm your e-mail address by</label></div>";
  $message .= "<div style='font-size: 18px; margin-bottom: 5px;'><label>clicking the button below.</label></div>";

  $message .= "<a href='https://www.stayinstylebangkok.com?verifyyouremail=" . base64_encode($email) . "'  style='display: block; margin-top: 30px; margin-bottom: 30px; border-radius: 10px; padding: 15px; text-align: center; background-color: #ffdd00; color: black; cursor: pointer;'>Confirm E-mail Address</a>";

  $message .= "<div>";
  $message .= "<label>Or paste this link into your browser:</label>";
  $message .= "</div>";

  $message .= "<div style='margin-bottom: 30px;'>";
  $message .= "<a href='https://www.stayinstylebangkok.com?verifyyouremail=" . base64_encode($email) . "' target='_blank'>https://www.stayinstylebangkok.com?verifyyouremail=" . base64_encode($email) . "</a>";
  $message .= "</div>";

  $message .= "<div style='margin-bottom 10px;'>";
  $message .= "<label style='font-size: 18px;'>Regards,</label>";
  $message .= "</div>";

  $message .= "<div style='margin-bottom 10px;'>";
  $message .= "<label style='font-size: 18px;'>Stay in Style Bangkok Team</label>";
  $message .= "</div>";

  $message .= "<hr style='margin:30px 0px;'>";

  $message .= "<div style='text-align: center; padding-bottom: 30px;'>";
  $message .= "<img src='../../assest/img/sty_04.png' alt='sty_04' style='height: 25px; margin-right: 15px;'>";
  $message .= "<img src='../../assest/img/sty_01.png' alt='sty_01' style='height: 25px; margin-right: 15px;'>";
  $message .= "<img src='../../assest/img/sty_02.png' alt='sty_02' style='height: 25px; margin-right: 15px;'>";
  $message .= "<img src='../../assest/img/sty_03.png' alt='sty_03' style='height: 25px; margin-right: 15px;'>";
  $message .= "</div>";

  $message .= "</div>";
  $message .= "</div>";
  // End Body

  // Footer
  $message .= "<div style='margin-top: 30px; text-align: center; color: #9B9B9B;'>";

  $message .= "<label style='display: block;'>For further information Department of International Trade Promotion.</label>";
  $message .= "<label style='display: block;'>Ministry of Commerce. Office of Lifestyle Trade Promotion</label>";
  $message .= "<label style='display: block; font-weight: bold;'>DITP Call Center 1669</label>";

  $message .= "<div style='margin-top: 10px;'>";
  $message .= "<img src='../../assest/img/facebook.png' style='margin-right: 5px; width: 20px;'>";
  $message .= "<img src='../../assest/img/pinterest.png' style='margin-right: 5px; width: 20px;'>";
  $message .= "<img src='../../assest/img/twitter.png' style='margin-right: 5px; width: 20px;'>";
  $message .= "<img src='../../assest/img/instagram.png' style='margin-right: 5px; width: 20px;'>";

  $message .= "</div>";
  // End Footer

  $message .= "</div>";
  $message .= "</div>";

  $result['body'] = $message;

  return $result;
}


function Plus30Min($start, $end)
{
  global $time_tmp;

  if (new DateTime($start) < new DateTime($end)) {
    $time = strtotime($start);
    $startTime = date("Y-m-d H:i:s", strtotime('+30 minutes', $time));
    array_push($time_tmp, $startTime);
    if (new DateTime($startTime) < new DateTime($end)) {
      Plus30Min($startTime, $end);
    }
  } else {
    array_push($time_tmp, $start);
  }
}

function sendnoti($token, $title, $message)
{
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
?>
