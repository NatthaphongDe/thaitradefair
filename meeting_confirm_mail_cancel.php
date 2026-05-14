<?php
include 'backoffice/connect.php';

require_once __DIR__ . '/vendor/autoload.php';

// 2. เรียกใช้งาน Dotenv
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();
// echo $meeting_id = 8;
// echo $meeting_id ;
// exit();

$sqleld = " select * from tt_meeting LEFT JOIN tt_sso_login on (tt_sso_login.sso_id = tt_meeting.meeting_ssoid ) where meeting_id = ?  ";
$stmteld = $mysqli->prepare($sqleld);
$stmteld->bind_param('i', $meeting_id);
$stmteld->execute();
$resulteld = $stmteld->get_result();
$numroweld = $resulteld->num_rows;
$dataeld = $resulteld->fetch_assoc();

$meeting_token  = $dataeld['meeting_token'];
$meeting_status = $dataeld['meeting_status'];
$com_name       = $dataeld['sso_name_en'];
$mail_all[]     = $dataeld['sso_email'];
// echo "<pre>";
// print_r($dataeld );
// echo "</pre>";
// echo "<pre>";
// print_r($mail_all );
// echo "</pre>";


// print_r($mail_all);
// exit();


$bx_text = '<div style="padding: 10px 15px;border-radius: 10px;border: solid 1px #b2b2b2;background-color: #fff;font-weight: bold;margin-bottom: 10px;text-align:center;color: red;">
                Cancel Meeting
              </div>';



// echo $bx_text;
// exit();

ob_start();


?>
<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8">
  <title></title>
  <link rel="stylesheet" type="text/css" href="<?php echo ROOTPATHDOMAIN; ?>assets/css/fonts.css" rel="stylesheet">
</head>

<body style="font-family:'SukhumvitSet';font-size: 16px;">
  <div class="" style="background-color: #f9f9f9; width: 100%; text-align: center;padding: 15px;color:#4a4a4a;">
    <div style="border-radius: 8px;max-width: 550px; text-align: center; background: #fcfbfb;min-width: 400px;max-width: 550px; margin: 0 auto;">
      <div class="" style="text-align: center;background: #fcfbfb;">
        <div style="height: 8px; object-fit: contain; background-image: linear-gradient(to right, #68c6f8 0%, #004cb2 99%);border-top-left-radius: 15px;border-top-right-radius: 15px;position: relative;bottom: -8px;">
        </div>
        <div class="b1" style="background-image: url(<?php echo ROOTPATHDOMAIN; ?>assets/images/mail-b-1.png);background-repeat: no-repeat;background-size: contain;">
          <div style="background-image: url(<?php echo ROOTPATHDOMAIN; ?>assets/images/mail-b-2.png);background-repeat: no-repeat;background-size: 25%;">
            <div class="b3" style="background-image: url(<?php echo ROOTPATHDOMAIN; ?>assets/images/mail-b-banner-bg.png);background-repeat: no-repeat; background-position: bottom;background-size: contain;">
              <div class="b4" style="background-image: url(<?php echo ROOTPATHDOMAIN; ?>assets/images/mail-b-3.png);background-repeat: no-repeat;background-position: right bottom;background-size: auto 400px;">
                <div class="" style="padding:40px 50px 15px 50px;text-align: left;color:#000">
                  <div style="text-align: center">
                    <img src="<?php echo ROOTPATHDOMAIN; ?>assets/images/mail_logo_index.png" alt="">
                  </div>
                  <div class="" style="text-align: left; margin-top: 35px;color:#4a4a4a;">
                    Hi <b style="color:#378dd7"><?php echo $com_name; ?></b>
                    <div style="margin-top: 15px;">
                      The exhibitor has received your reguest for Online Meeting. Please wait foe appointment confirmation form the exhibitor.
                    </div>
                    <div style="margin-top: 15px;">
                      Thank you for your business.
                    </div>
                  </div>
                  <div style="margin-top: 15px;margin-bottom: 15px;">
                    <?php echo   $bx_text; ?>
                  </div>
                  <div style="margin-top: 30px;color:#4a4a4a;">
                    Regards,<br>
                    THAITRADE FAIR Team
                  </div>
                  <div style="margin-top: 30px;color:#4a4a4a;">
                    For additional assistance, please contact our staff at
                  </div>
                  <div style="color: #0d8aff!important;margin-bottom: 30px;padding-bottom: 15px;border-bottom: solid 1px #a8a8a8;">
                    <a href="<?php echo ROOTPATHDOMAIN; ?>help"><?php echo ROOTPATHDOMAIN; ?>help</a>
                  </div>
                  <div class="" style=" ">
                    <img src="<?php echo ROOTPATHDOMAIN; ?>assets/images/mail-b-banner.png" style="width: -webkit-fill-available;margin-left: 15px;margin-right: 15px;">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div style="font-size: 14px;line-height: 1.2;margin-top: 15px;color:#9b9b9b">
      <div class="">
        For further information Department of International Trade Promotion,
      </div>
      <div class="">
        Ministry of Commerce. Office of Lifestyle Trade Promotion
      </div>
      <div class="">
        <b>DITP Call Center 1169</b>
      </div>
      <div style="margin-top: 15px;">
        <a href="https://www.facebook.com/ThaiTradedotcom/" style="text-decoration: unset;">
          <img src="<?php echo ROOTPATHDOMAIN; ?>assets/images/mail-facebook.png" alt="">
        </a>
        <a href="#" style="text-decoration: unset;">
          <img src="<?php echo ROOTPATHDOMAIN; ?>assets/images/mail-pinterest.png" alt="">
        </a>
        <a href="https://twitter.com/ThaiTradedotcom" style="text-decoration: unset;">
          <img src="<?php echo ROOTPATHDOMAIN; ?>assets/images/mail-twitter.png" alt="">
        </a>
        <a href="https://www.instagram.com/thaitradedotcom/" style="text-decoration: unset;">
          <img src="<?php echo ROOTPATHDOMAIN; ?>assets/images/mail-instagram.png" alt="">
        </a>
      </div>
    </div>
  </div>
</body>

</html>


<style>
  @font-face {
    font-family: 'SukhumvitSet';
    src: url('<?php echo ROOTPATHDOMAIN; ?>assets/fonts/SukhumvitSet-SemiBold.woff2') format('woff2'),
      url('<?php echo ROOTPATHDOMAIN; ?>assets/fonts/SukhumvitSet-SemiBold.woff') format('woff');
    font-weight: 600;
    font-style: normal;
    font-display: swap;
  }
</style>
<?php



$message = ob_get_contents();
ob_end_clean();

// $To_Email = 'Santisook.tee@gmail.com';
$To_Name  = $com_name;
// echo $message;





// require 'assets/dist/PHPMailer-6.6.4/src/Exception.php';
// require 'assets/dist/PHPMailer-6.6.4/src/PHPMailer.php';
// require 'assets/dist/PHPMailer-6.6.4/src/SMTP.php';
//
// use PHPMailer\PHPMailer\PHPMailer;
// use PHPMailer\PHPMailer\SMTP;
// use PHPMailer\PHPMailer\Exception;
//
// $mail = new PHPMailer(true);
//
//   try {
//
//     $mail = new PHPMailer(true);
//     $mail->CharSet = "utf-8";
//     $mail->IsSMTP();
//     $mail->SMTPDebug = false;
//     // $mail->Timeout = 10000;
//     $mail->SMTPAuth = true;
//     $mail->SMTPSecure = "tls"; // sets the prefix to the servier
//     // $mail->Host = "outgoing.mail.go.th"; // SMTP server
//     $mail->Host = "smtp.gmail.com"; // SMTP server
//     $mail->Port = 587; // พอร์ท
//     $mail->Username = "Santisook.tee@gmail.com"; // account SMTP
//     $mail->Password = "rycvwnzssbtdkemh"; // รหัสผ่าน SMTP
//     $mail->SetFrom("Santisook.tee@gmail.com", 'from name');
//     $mail->Subject = 'Thaitrade Online Meeting';
//     $mail->MsgHTML($message);
//     $mail->AddAddress($To_Email, $To_Name);
//
//     $mail->send();
//       // echo 'Message has been sent';
//   } catch (Exception $e) {
//       // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
//   }


// $mail_all   = [];
// $mail_all[] = 'Santisook.tee@gmail.com';
//
//
//   echo "<pre>";
//   print_r($mail_all);
//   echo "</pre>";

foreach ($mail_all as $key => $value) {
  $body = [
    'Messages' => [
      [
        'From' => [
          'Email' => "No-Reply@thaitradefair.com",
          'Name' => "Thaitrade Mail"
        ],
        'To' => [
          [
            'Email' => $value,
            'Name' => $To_Name
          ],
        ],

        'Subject' => "Thaitrade Online Meeting",
        'HTMLPart' => $message,
      ]
    ]
  ];

  $apiKey = $_ENV['MAILJET_API_KEY'];
  $apiSecret = $_ENV['MAILJET_API_SECRET'];

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://api.mailjet.com/v3.1/send");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER,
    array(
      'Content-Type: application/json'
    )
  );
  curl_setopt($ch, CURLOPT_USERPWD, "$apiKey:$apiSecret");
  $server_output = curl_exec($ch);
  curl_close($ch);
  $response = json_decode($server_output);

  if ($response->Messages[0]->Status == 'success') {
    // echo "Email sent successfully.";
  } else {
    // echo "Errro sent email";
  }

  /*  $body = [
    "sender" => [
      "name"  => "Thaitrade Mail",
      "email" => "No-Reply@thaitradefair.com"
    ],
    "to" => [
      [
        "email" => trim($value),    // ตรวจสอบว่า $value คือ email string
        "name"  => $To_Name   // ชื่อผู้รับ
      ]
    ],
    "subject"     => "Thaitrade Online Meeting",
    "htmlContent" => $message   // เปลี่ยนจาก HTMLPart เป็น htmlContent
  ];

  $apiKey = $_ENV['BREVO_API_KEY']; // ใส่ API Key ของ Brevo ที่นี่

  $ch = curl_init();
  curl_setopt($ch, CURLOPT_URL, "https://api.brevo.com/v3/smtp/email");
  curl_setopt($ch, CURLOPT_POST, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'api-key: ' . $apiKey,
    'Content-Type: application/json',
    'Accept: application/json'
  ]);

  $server_output = curl_exec($ch);
  $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
  curl_close($ch);

  $response = json_decode($server_output);

  // เช็คสถานะการส่ง (Brevo จะคืนค่า 201 Created ถ้าสำเร็จ)
  if ($httpCode == 201) {
    // echo "Email sent successfully.";
  } else {
    // echo "Error sending email: " . $server_output;
  } */
}

?>