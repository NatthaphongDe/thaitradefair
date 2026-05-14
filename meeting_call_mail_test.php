<?php
  include 'backoffice/connect.php';
// exit();

  $meeting_id = 24;

  $sqleld = " select * from tt_meeting where meeting_id = ? ";
  $stmteld = $mysqli->prepare($sqleld);
  $stmteld->bind_param('i',$meeting_id);
  $stmteld->execute();
  $resulteld = $stmteld->get_result();
  $numroweld = $resulteld->num_rows;
  $dataeld = $resulteld->fetch_assoc();

  $meeting_token =  $dataeld['meeting_token'];
  // echo "<pre>";
  // print_r($dataeld );
  // echo "</pre>";

  if($dataeld['meeting_exp_id']!=0 && $dataeld['meeting_exl_id']==0){
    $meeting_exp_id = $dataeld['meeting_exp_id'];
    $sqlelc = " SELECT * FROM `tt_exportor_list` WHERE `exp_id` = $meeting_exp_id ORDER BY `exp_id` DESC  ";
    $stmtelc_list = $mysqli->prepare($sqlelc);
    $stmtelc_list->execute();
    $res_exhibitor_list = $stmtelc_list->get_result();
    $res_exhibitor_list = $res_exhibitor_list->fetch_assoc();

    $com_name = $res_exhibitor_list['Corporate_Name_EN'];
    $Mail = $res_exhibitor_list['Mail'];


  }else if($dataeld['meeting_exp_id']==0 && $dataeld['meeting_exl_id']!=0){
    $meeting_exl_id = $dataeld['meeting_exl_id'];
    $sqlelc = " SELECT * FROM `tt_exhibitor_list` WHERE `exl_id` = $meeting_exl_id ORDER BY `exl_id` ASC ";
    $stmtelc_list = $mysqli->prepare($sqlelc);
    $stmtelc_list->execute();
    $res_exhibitor_list = $stmtelc_list->get_result();
    $res_exhibitor_list = $res_exhibitor_list->fetch_assoc();

    $com_name = $res_exhibitor_list['com_name'];
    $Mail = $res_exhibitor_list['com_email'];

  }


  $mail_all =   explode(',',  $Mail );
  // print_r($mail_all);
  // exit();

  $sqlelc = " SELECT * FROM `tt_meeting_list` WHERE `meeting_list_mid` = $meeting_id ORDER BY `tt_meeting_list`.`meeting_list_id` ASC ";
  $stmtelc = $mysqli->prepare($sqlelc);
  $stmtelc->execute();
  $resultelc = $stmtelc->get_result();
  $numrowelc = $resultelc->num_rows;
    $bx_text = '';
  if($numrowelc>0) {
    while($dataelc = $resultelc->fetch_assoc()) {
      // echo "<pre>";
      // print_r($dataelc);
      // echo "</pre>";

      $pat1 = ROOTPATHDOMAIN.'assets/images/mail-time-1.png';
      $pat2 = ROOTPATHDOMAIN.'assets/images/mail-time-2.png';
        //
      $datetime1_1 =  date('F d, Y', strtotime($dataelc['meeting_list_datetime']));
      $datetime1_2 =  date('H:i', strtotime($dataelc['meeting_list_datetime']));

      $meeting_list_gmt = $dataelc["meeting_list_gmt"];

      if($meeting_list_gmt==7){
        $datetime2_1 = date('F d, Y',strtotime($dataelc['meeting_list_datetime']));
        $datetime2_2 = date('H:i',strtotime($dataelc["meeting_list_datetime"]));
        $new = '+7';
      }else{
        $new = $meeting_list_gmt - 7;
        if($new>=0){
          $new= '+'.$new;
        }
        $datetime2_1 = date('F d, Y',strtotime(  $new.' hour',strtotime($dataelc['meeting_list_datetime'])));
        $datetime2_2 = date('H:i',strtotime(  $new.' hour',strtotime($dataelc['meeting_list_datetime'])));
      }

      $bx_text .= '<div style="padding: 10px 15px;border-radius: 10px;border: solid 1px #b2b2b2;background-color: #fff;font-weight: bold;margin-bottom: 10px;">
                    <div style="color: #378dd7;">
                      '.$dataelc["meeting_list_title"].'
                    </div>
                    <div style="color: #2f2f2f;">
                      '.$datetime1_1.'<img src="'.$pat1.'" style="width: 12px;margin-left: 5px; margin-right: 5px;">'.$datetime1_2.'
                    </div>
                    <div style="color: #666666;font-size: 16px;">
                      Time Zone: (GMT'.$new.')
                    </div>
                    <div style="color: #378dd7;">
                      '.$dataelc["meeting_list_timezone"].' Time (GMT'.$new.') : '.$datetime2_1.'<img src="'.$pat2.'" style="margin-left: 5px; margin-right: 5px;">'.$datetime2_2.'
                    </div>
                    <div style="text-align: center;margin-top: 10px;margin-bottom: 5px;">
                      <a style=" width: 222px; padding: 7px 21px 6px 22px; border-radius: 8px;  background-color: #378dd7;color: #fff; text-decoration: inherit;" href="'.ROOTPATHDOMAIN.'meeting_confirm.php?token='.$meeting_token.'&check='.$dataelc["meeting_list_id"].'">Confirm Meeting</a>
                    </div>
                  </div>';


    }
  }

$message = '<link href="'.ROOTPATHDOMAIN.'assets/css/fonts.css" rel="stylesheet">
              <div class="" style="background-color: #f9f9f9; width: 100%; text-align: center;padding: 15px;color:#4a4a4a;font-family:SukhumvitSet;">
              <div style="border-radius: 8px;max-width: 550px; text-align: center; background: #fcfbfb;min-width: 400px;max-width: 550px; margin: 0 auto;">
                <div class="" style="text-align: center;background: #fcfbfb;">
                  <div style="height: 8px; object-fit: contain; background-image: linear-gradient(to right, #68c6f8 0%, #004cb2 99%);border-top-left-radius: 15px;border-top-right-radius: 15px;position: relative;bottom: -8px;">
                  </div>
                    <div class="b1" style="background-image: url('.ROOTPATHDOMAIN.'assets/images/mail-b-1.png);background-repeat: no-repeat;background-size: contain;">
                      <div style="background-image: url('.ROOTPATHDOMAIN.'assets/images/mail-b-2.png);background-repeat: no-repeat;background-size: 25%;">
                        <div class="b3" style="background-image: url('.ROOTPATHDOMAIN.'assets/images/mail-b-banner-bg.png);background-repeat: no-repeat; background-position: bottom;background-size: contain;">
                          <div class="b4" style="background-image: url('.ROOTPATHDOMAIN.'assets/images/mail-b-3.png);background-repeat: no-repeat;background-position: right bottom;background-size: auto 400px;">
                            <div class="" style="padding:40px 50px 15px 50px;text-align: left">
                              <div style="text-align: center">
                                <img src="'.ROOTPATHDOMAIN.'assets/images/mail_logo_index.png" alt="">
                              </div>
                              <div class="" style="text-align: left; margin-top: 35px;">
                                Hi <b style="color:#378dd7">'.$com_name.'</b>
                                <div style="margin-top: 15px;">
                                  The exhibitor has received your reguest for Online Meeting. Please wait foe appointment confirmation form the exhibitor.
                                </div>
                                <div style="margin-top: 15px;">
                                  Thank you for your business.
                                </div>
                              </div>
                              <div style="margin-top: 15px;margin-bottom: 15px;">
                                '.  $bx_text .'
                              </div>
                              <div style="margin-bottom: 15px;">
                                  Message : '.$dataeld['meeting_message'].'
                              </div>
                                <div style="text-align: center">
                                <a style=" width: 222px; padding: 7px 21px 6px 22px; border-radius: 8px;  background-color: #e91a1a;color: #fff; text-decoration: inherit;" href="'.ROOTPATHDOMAIN."meeting_confirm.php?token=".$meeting_token.'&check=cancel">Cancel Meeting</a>
                                </div>
                              <div style="margin-top: 30px;">
                                Regards,<br>
                                THAITRADE FAIR Team
                              </div>
                              <div style="margin-top: 30px;">
                                For additional assistance, please contact our staff at
                              </div>
                              <div style="color: #0d8aff!important;margin-bottom: 30px;padding-bottom: 15px;border-bottom: solid 1px #a8a8a8;">
                                <a href="'.ROOTPATHDOMAIN.'help">'.ROOTPATHDOMAIN.'help</a>
                              </div>
                              <div class="" style=" ">
                                <img src="'.ROOTPATHDOMAIN.'assets/images/mail-b-banner.png" style="width: -webkit-fill-available;margin-left: 15px;margin-right: 15px;">
                              </div>
                            </div>
                        </div>
                        </div>
                      </div>
                    </div>
                </div>
              </div>
              <div style="font-size: 14px;line-height: 1.2;margin-top: 15px;color:#9b9b9b">
                <div style="">
                  For further information Department of International Trade Promotion,
                </div>
                <div style="">
                  Ministry of Commerce. Office of Lifestyle Trade Promotion
                </div>
                <div class="">
                  <b>DITP Call Center 1169</b>
                </div>
                <div style="margin-top: 15px;">
                  <a href="https://www.facebook.com/ThaiTradedotcom/" style="text-decoration: unset;">
                    <img src="'.ROOTPATHDOMAIN.'assets/images/mail-facebook.png" alt="">
                  </a>
                  <a href="#" style="text-decoration: unset;">
                    <img src="'.ROOTPATHDOMAIN.'assets/images/mail-pinterest.png" alt="">
                  </a>
                  <a href="https://twitter.com/ThaiTradedotcom" style="text-decoration: unset;">
                    <img src="'.ROOTPATHDOMAIN.'assets/images/mail-twitter.png" alt="">
                  </a>
                  <a href="https://www.instagram.com/thaitradedotcom/" style="text-decoration: unset;">
                    <img src="'.ROOTPATHDOMAIN.'assets/images/mail-instagram.png" alt="">
                  </a>
                </div>
              </div>
            </div>';



$To_Email = 'Santisook.tee@gmail.com';
$To_Name  = $com_name;
echo $message;





require 'assets/dist/PHPMailer-6.6.4/src/Exception.php';
require 'assets/dist/PHPMailer-6.6.4/src/PHPMailer.php';
require 'assets/dist/PHPMailer-6.6.4/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

  try {

    $mail = new PHPMailer(true);
    $mail->CharSet = "utf-8";
    $mail->IsSMTP();
    $mail->SMTPDebug = false;
    // $mail->Timeout = 10000;
    $mail->SMTPAuth = true;
    $mail->SMTPSecure = "tls"; // sets the prefix to the servier
    // $mail->Host = "outgoing.mail.go.th"; // SMTP server
    $mail->Host = "smtp.gmail.com"; // SMTP server
    $mail->Port = 587; // พอร์ท
    $mail->Username = "Santisook.tee@gmail.com"; // account SMTP
    $mail->Password = "rycvwnzssbtdkemh"; // รหัสผ่าน SMTP
    $mail->SetFrom("Santisook.tee@gmail.com", 'from name');
    $mail->Subject = 'Thaitrade Online Meeting';
    $mail->MsgHTML($message);
    $mail->AddAddress($To_Email, $To_Name);

    $mail->send();
      // echo 'Message has been sent';
  } catch (Exception $e) {
      // echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
  }





    // echo "<pre>";
    // print_r($body);
    // echo "</pre>";



  // foreach ($mail_all as $key => $value) {
  //     $body = [
  //        'Messages' => [
  //            [
  //            'From' => [
  //                'Email' => "No-Reply@thaitradefair.com",
  //                'Name' => "Thaitrade Mail"
  //            ],
  //            'To' => [
  //                [
  //                    'Email' => $value,
  //                    'Name' => $To_Name
  //                ],
  //            ],
  //
  //            'Subject' => "Thaitrade Online Meeting",
  //            'HTMLPart' => $message,
  //            ]
  //        ]
  //    ];
  //
  //   $ch = curl_init();
  //   curl_setopt($ch, CURLOPT_URL, "https://api.mailjet.com/v3.1/send");
  //   curl_setopt($ch, CURLOPT_POST, 1);
  //   curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($body));
  //   curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  //   curl_setopt($ch, CURLOPT_HTTPHEADER, array(
  //       'Content-Type: application/json')
  //   );
  //   curl_setopt($ch, CURLOPT_USERPWD, ":");
  //   $server_output = curl_exec($ch);
  //   curl_close ($ch);
  //   $responsex = json_decode($server_output);
  //
  //   if ($responsex->Messages[0]->Status == 'success' )  {
  //       // echo "Email sent successfully.";
  //   }  else {
  //       // echo "Errro sent email";
  //   }
  // }

?>
