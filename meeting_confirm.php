<?php
  include 'backoffice/connect.php';

  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

  function genRoom($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>
  <!DOCTYPE html>
  <html lang="en" dir="ltr">
    <head>
      <meta charset="utf-8">
      <title></title>
      <link rel="stylesheet" type="text/css"  href="<?php echo ROOTPATHDOMAIN; ?>assets/css/fonts.css" rel="stylesheet">
    </head>
    <body style="font-family:'SukhumvitSet';font-size: 16px;">
      <div class="box-mix" style="background-color: #f9f9f9; width: 100%; text-align: center;color:#4a4a4a;">
        <div style="border-radius: 8px;max-width: 550px; text-align: center; background: #fcfbfb;min-width: 400px;max-width: 550px; margin: 0 auto;">
          <div class="" style="text-align: center;background: #fcfbfb;">
            <div style="height: 8px; object-fit: contain; background-image: linear-gradient(to right, #68c6f8 0%, #004cb2 99%);border-top-left-radius: 15px;border-top-right-radius: 15px;position: relative;bottom: -8px;">
            </div>
              <div class="b1" style="background-image: url(<?php echo ROOTPATHDOMAIN; ?>assets/images/mail-b-1.png);background-repeat: no-repeat;background-size: contain;">
                <div style="background-image: url(<?php echo ROOTPATHDOMAIN; ?>assets/images/mail-b-2.png);background-repeat: no-repeat;background-size: 25%;">
                  <div class="b3" style="background-image: url(<?php echo ROOTPATHDOMAIN; ?>assets/images/mail-b-banner-bg.png);background-repeat: no-repeat; background-position: bottom;background-size: contain;">
                    <div class="b4" style="background-image: url(<?php echo ROOTPATHDOMAIN; ?>assets/images/mail-b-3.png);background-repeat: no-repeat;background-position: right bottom;background-size: auto 400px;">
                      <div class="" style="padding:40px 50px 15px 50px;text-align: left">
                        <div style="text-align: center">
                          <img src="<?php echo ROOTPATHDOMAIN; ?>assets/images/mail_logo_index.png" alt="">
                        </div>

                        <div style="margin-top: 15px;margin-bottom: 15px;">

                          <div class="card">
                            <div class="card-body">
                              <?php if(!isset($_GET['token']) || !isset($_GET['check'])){ ?>
                                <div class="box-error-url">
                                  <div class="txt-box-error-url">
                                    URL Error !
                                  </div>
                                  <a href="/"><button type="button" name="button" class="bth-x">Back To Home</button></a>
                                </div>
                              <?php }else {

                                  $sql = "SELECT * FROM `tt_meeting` WHERE meeting_token = '".$_GET['token']."' AND meeting_status = 0 ";
                                  $stmt = $mysqli->prepare($sql);
                                  $stmt->execute();
                                  $result = $stmt->get_result();
                                  if($result->num_rows > 0){
                                    $res = $result->fetch_assoc();
                                    if($_GET['check'] == "cancel"){
                                      $d = date('Y-m-d H:i:s');
                                      $update = "UPDATE `tt_meeting` SET meeting_status = 2 , meeting_update = '$d' WHERE meeting_id = '".$res['meeting_id']."' ";
                                      $stmt_update = $mysqli->prepare($update);
                                      if($stmt_update->execute()){
                                        // cancel success send mail
                                        ?>
                                        <div class="box-error-url">
                                          <div class="txt-box-error-url">
                                            Cancel Success
                                          </div>
                                          <a href="/"><button type="button" name="button" class="bth-x">Back To Home</button></a>
                                        </div>
                                        <?php
                                        $meeting_id = $res['meeting_id'];
                                        include 'meeting_confirm_mail_cancel.php';
                                      }else {
                                        ?>
                                        <div class="box-error-url">
                                          <div class="txt-box-error-url">
                                            Cancel Error !
                                          </div>
                                          <a href="/"><button type="button" name="button" class="bth-x">Back To Home</button></a>
                                        </div>
                                        <?php
                                      }
                                    }else {
                                      $d = date('Y-m-d H:i:s');
                                      $update = "UPDATE `tt_meeting` SET meeting_status = 1 , meeting_update = '$d' WHERE meeting_id = '".$res['meeting_id']."' ";
                                      $stmt_update = $mysqli->prepare($update);
                                      if($stmt_update->execute()){
                                        $room = genRoom();
                                        $update_list = "UPDATE `tt_meeting_list` SET meeting_list_status = 1 , meeting_list_room = '$room', meeting_list_update = '$d' WHERE meeting_list_id = '".$_GET['check']."' ";
                                        $stmt_update_list = $mysqli->prepare($update_list);
                                        $stmt_update_list->execute();

                                        // confirm success send mail

                                        ?>
                                        <div class="box-error-url">
                                          <div class="txt-box-error-url">
                                            Confirm Success
                                          </div>
                                          <a href="/"><button type="button" name="button" class="bth-x">Back To Home</button></a>
                                        </div>
                                        <?php
                                        // echo ">>";
                                        $meeting_id = $res['meeting_id'];
                                        // echo "<<";
                                        // echo "333";
                                        include 'meeting_confirm_mail_success.php';
                                        // echo "333";
                                      }else {
                                        ?>
                                        <div class="box-error-url">
                                          <div class="txt-box-error-url">
                                            Confirm Error !
                                          </div>
                                          <a href="/"><button type="button" name="button" class="bth-x">Back To Home</button></a>
                                        </div>
                                        <?php
                                      }
                                    }
                                  }else {
                                    ?>
                                    <div class="box-error-url">
                                      <div class="txt-box-error-url">
                                        URL Error !
                                      </div>
                                      <a href="/"><button type="button" name="button" class="bth-x">Back To Home</button></a>
                                    </div>
                                    <?php
                                  }
                                  ?>
                              <?php } ?>
                            </div>
                          </div>
                        </div>
                        <div style="color: #0d8aff!important;margin-bottom: 30px;padding-bottom: 15px;border-bottom: solid 1px #a8a8a8;">
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
    .card-body{
      min-height: 300px;
      background: #fff;
      margin-top: 50px;
      margin-bottom: 50px;
      border-radius: 5px;
    }
    body,html{
      height: 100%;
      padding: 0;
      margin: 0;
    }
    .box-mix{
      height: 100%;
    }
    .box-error-url{
      text-align: center;
    }
    .txt-box-error-url{
      font-size: 20px;
      padding-top: 50px;
      padding-bottom: 20px;
    }
    .bth-x{
      border: 0;
      font-size: 18px;
      background: #378dd7;
      color: #fff;
      padding: 10px 20px;
      border-radius: 5px;
      cursor: pointer;
    }
    </style>
