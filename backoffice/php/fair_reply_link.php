<?php
$menu_name = "Message";
$save_link = "fair_reply_link";
$back_link = "fair_contact_list";
$reply_link = "fair_reply_link";

require_once __DIR__ . '/../../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../../../');
$dotenv->load();

if ($_REQUEST["method"] == "add") {
  include_once("../connect.php");

  $cont_id = $_POST["cont_id"];
  $reply_subject = $_POST["reply_subject"];
  $reply_name = $_POST["reply_name"];
  $reply_mesage = $_POST["reply_mesage"];
  $reply_by = $_SESSION["id"];
  $reply_name_a = $_POST["reply_name_a"];

  $reply_mails = $_POST["reply_mails"];

  if ($reply_mesage != "") {

    $sql = "select phone,email from tt_admin where id = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param('i', $reply_by);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    $reply_tel = $data['phone'];
    $reply_email = $data['email'];

    $sql_reply = "insert into tt_contact_list_reply (cont_id,reply_subject,reply_name,reply_email,reply_tel,reply_mesage,reply_date,reply_by) values (?,?,?,?,?,?,now(),?) ";
    $stmt_reply = $mysqli->prepare($sql_reply);
    $stmt_reply->bind_param('isssssi', $cont_id, $reply_subject, $reply_name, $reply_email, $reply_tel, $reply_mesage, $reply_by);
    $stmt_reply->execute();
    $file_id = $stmt_reply->insert_id;
    $path_parts = pathinfo($_FILES['reply_file']['name']);
    $extension = $path_parts['extension'];
    $filenamedata = pathinfo($_FILES['reply_file']['name'], PATHINFO_FILENAME);
    $fillfullname = $filenamedata . "." . $extension;
    $path_namepic = alphanumeric_random_wms(10);
    $path_mini = ROOTPATH . "/data/mail_fairattachfile/$file_id/$path_namepic.$extension";
    $pathdb = "/data/mail_fairattachfile/$file_id/$path_namepic.$extension";
    if (!is_dir(ROOTPATH . "/data")) {
      @mkdir(ROOTPATH . "/data");
    }
    if (!is_dir(ROOTPATH . "/data/mail_fairattachfile")) {
      @mkdir(ROOTPATH . "/data/mail_fairattachfile");
    }
    if (!is_dir(ROOTPATH . "/data/mail_fairattachfile/" . $file_id)) {
      @mkdir(ROOTPATH . "/data/mail_fairattachfile/" . $file_id);
    }
    if (move_uploaded_file($_FILES["reply_file"]["tmp_name"], $path_mini)) {
      $sqlup = "update tt_contact_list_reply set reply_file = ? where reply_id = ? ";
      $stmtup = $mysqli->prepare($sqlup);
      $stmtup->bind_param('si', $fillfullname, $file_id);
      $stmtup->execute();
    }
  }

  $sql_update = "update tt_contact_list set cont_status = 2 where cont_id = ? ";
  $stmt_update = $mysqli->prepare($sql_update);
  $stmt_update->bind_param('i', $cont_id);
  $stmt_update->execute();

  $arr_email = [
    "cont_id" => $cont_id,              //id
    "reply_subject" => $reply_subject,  //หัวข้อ
    "reply_name" => $reply_name,        //ชื่อผู้ส่ง
    "reply_name_a" => $reply_name_a,    //ชื่อผู้รับ
    "reply_mesage" => $reply_mesage,    //รายละเอียด ข้อความการตอบกลับ
    "reply_tel" => $reply_tel,          //เบอร์โทรผู้ส่ง
    "reply_email" => $reply_email,      //เมลผู้ส่ง
    "reply_by" => $reply_by,            //ตอบกลับโดย
    "reply_mails" => $reply_mails,      //ส่งไปยัง เมลไหน reply_file
    "reply_file" => $fillfullname,
  ];

  $sql_user = "select * from tt_contact_list as a LEFT JOIN tt_contact_list_reply as b on a.cont_id = b.cont_id where a.cont_id  = ?";
  $stmt_user = $mysqli->prepare($sql_user);
  $stmt_user->bind_param('i', $cont_id);
  $stmt_user->execute();
  $result_user = $stmt_user->get_result();
  $data_user = $result_user->fetch_assoc();

  $pathmail = "data/mail_fairattachfile/$file_id/$path_namepic.$extension";
  $file_mail = ROOTPATHDOMAIN . $pathmail;

  // echo $fillfullname;
  // echo $path_mini;

  $mail_send_to = $arr_email["reply_mails"];
  // $mail_send_to = "Thanet.w@ibusiness.co.th";

  // https://thaitrade.ibusiness.co.th/backoffice/home.php?show=preview_mail&cont_id=
  // echo file_get_contents("https://thaitrade.ibusiness.co.th/backoffice/preview_mail.php");
  // <div style=\"text-align:left;\">" . $arr_email["reply_file"] . "</div></div><br><br>
  $message = "
        <div style=\"background: #1C5FA1;padding: 5px 10px 40px 10px;border-radius: 10px;\">
            <div class=\"wrapper\" style=\"background: #fff;padding: 20px;\"><br>
              <div style=\"line-height: 41px;border-radius: 5px;color: #1C5FA1;text-align: left;\"><h3>Send To " . $data_user['cont_name'] . "</h3></div>
              <div style=\"text-align:left;color:#000;\">Mail" . $data_user['cont_email'] . " | " . $data_user['cont_tel'] . " | " . $data_user['cont_create_date'] . "</div><br>
              <div style=\"border:2px dashed #ccc;padding: 10px;\">
              <div style=\"text-align:left;\"><h3><span style=\"color: #1C5FA1;\">" . $data_user['cont_subject'] . "</h3></span></div><br>
              <div style=\"text-align:left;\">" . $data_user["cont_message"] . "<br><br>
              Best Regards," . $data_user["cont_name"] . "</div>
              </div><br><br>
              <div style=\"text-align:left;color:#000;\">Mail" . $arr_email['reply_email'] . " | " . $arr_email['reply_tel'] . " | " . $data_user['reply_date'] . "</div><br><br>
              <div style=\"border:2px dashed #ccc;padding: 10px;\">
              <div style=\"text-align:left;\"><h3><span style=\"color: #1C5FA1;\">" . $arr_email['reply_subject'] . "</h3></span></div><br>
              <div style=\"text-align:left;\">" . $arr_email["reply_mesage"] . "<br><br>
              Best Regards," . $arr_email["reply_name"] . "<br><br>
              แนบไฟล์ : <a href=" . $file_mail . ">" . $fillfullname . "</a>
              </div>
              </div><br><br>

            </div>
          </center>
        </div>";

  // echo $message;
  // exit;

  /* $body = [
    'Messages' => [
      [
        'From' => [
          'Email' => "No-Reply@thaitradefair.com",
          'Name' => "Thaitrade Mail"
        ],
        'To' => [
          [
            'Email' => $mail_send_to,
            'Name' => $arr_email["reply_name_a"]
          ],
        ],

        'Subject' => "Thaitrade Mail",
        'HTMLPart' => $message,
      ]
    ]
  ]; */

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
    echo "Email sent successfully.";
  } else {
    echo "Errro sent email";
  }
  /* $body = [
    "sender" => [
      "name"  => "Thaitrade Mail",
      "email" => "No-Reply@thaitradefair.com"
    ],
    "to" => [
      [
        "email" => trim($mail_send_to),    // ตรวจสอบว่า $value คือ email string
        "name"  => $arr_email["reply_name_a"]   // ชื่อผู้รับ
      ]
    ],
    "subject"     => "Thaitrade Mail",
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
    echo "Email sent successfully.";
  } else {
    echo "Error sending email: " . $server_output;
  } */

?>
  <script type="text/javascript">
    top.pc_overlay(2);
    top.alertpopup("1", "บันทึกรายการเรียบร้อย");
    setTimeout(function() {
      top.window.location = "../home.php?show=<?= $back_link ?>";
    }, 500);
  </script>

  <?php
  exit();
}


$cont_id = $_GET["cont_id"];
$sql = "select * from  tt_contact_list a left join tt_fair_list b on a.fair_id=b.fair_id where a.cont_id = ? and a.fair_id > 0 and a.cont_status != 99  ";
$stmt = $mysqli->prepare($sql);
if ($stmt) {
  $stmt->bind_param('i', $cont_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if ($numrow > 0) {
    $data = $result->fetch_assoc();
  } else {
  ?>
    <script type="text/javascript">
      top.window.location = 'home.php?show=<?= $back_link ?>';
    </script>
  <?
    exit();
  }
} else {
  ?>
  <script type="text/javascript">
    top.window.location = 'home.php?show=<?= $back_link ?>';
  </script>
<?
  exit();
}
?>

<div class="row wrapper page-heading">
  <div class="col-xs-12">
    <br>
    <ol class="breadcrumb">
      <li>
        <a onclick="history.back(1)">
          <h3><i class="fa fa-angle-left backnav-size " aria-hidden="true"></i> <span class="backnav-size-txt">BACK</span></h3>
        </a>
      </li>
    </ol>
    <div class="bottom-blue"></div>
  </div>

</div>

<div class="wrapper wrapper-content animated fadeInRight">
  <div class="row">
    <div class="col-lg-12">
      <div class="ibox float-e-margins">
        <form class="form-horizontal" method="post" name="form_Suppliers_add" name="form_Suppliers_add" enctype="multipart/form-data" action="php/<?= $save_link ?>.php?method=add" target="com_m" onSubmit="pc_overlay(1);">

          <div class="row">
            <div class="col-xs-12">
              <h3><?= $menu_name ?></h3>
            </div>
          </div>

          <div class="ibox-content">

            <!--  -->
            <div class="container">
              <div class="row">
                <div class="col-sm-12">
                  <h3 style="color:#1C5FA1;"><?= $data["cont_name"] ?></h3>
                  <input type="hidden" name="reply_name" value="<?= $_SESSION["name"] ?>">
                  <input type="hidden" name="reply_mails" value="<?= $data["cont_email"] ?>">
                  <input type="hidden" name="reply_name_a" value="<?= $data["cont_name"] ?>">
                  <div class="row">
                    <div class="col-sm-4">
                      <font color="#1C5FA1"><?= $data["cont_email"] ?> | <?= $data["cont_tel"] ?></font>
                    </div>
                    <div class="col-sm-4"></div>
                    <div class="col-sm-4" style="text-align:right">
                      <font color="#1C5FA1"><?= $data["cont_create_date"] ?></font>
                    </div>
                  </div>
                  <hr>
                </div>
                <!--  -->

                <!--  -->
                <div class="col-sm-12">
                  <div class="list-group-item">
                    <h3><?= $data["cont_subject"] ?></h3>
                    <?= $data["cont_message"] ?><br><br>
                    <span>Best Regards,</span><br>
                    <?= $data["cont_name"] ?>
                    </li>
                  </div>
                  <!--  -->

                </div>
              </div>
            </div>

            <br> <br>
            <!--  -->
            <div class="container">
              <div class="row">
                <div class="col-sm-12">
                  <font color="#1C5FA1">Send To</font><br>
                  <h3 style="color:#1C5FA1;"><?= $data["cont_name"] ?></h3>
                  <font color="#1C5FA1"><?= $data["cont_email"] ?></font><br><br>
                </div>
                <!--  -->

                <!--  -->
                <div class="col-sm-12">
                  <div class="list-group-item">
                    <div class="row">
                      <div class="col-sm-4">
                        <div class="input-group">
                          <span class="input-group-addon">Subject</span>
                          <input id="msg" name="reply_subject" type="text" class="form-control" name="msg" placeholder="Input Subject?">
                        </div>
                      </div>
                      <div class="col-sm-4">
                        <input type="file" name="reply_file" class="form-control">
                      </div>
                      <div class="col-sm-4" style="text-align:right">
                        <button type="reset" class="btn btn-default"><span class="glyphicon glyphicon-trash"></span> Delete</button>
                        <!-- <button type="file" class="btn btn-default"><span class="glyphicon glyphicon-paperclip"></span> Attachment</button>   -->
                        <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-send"></span> Send</button>
                      </div>
                    </div><br>
                    <textarea name="reply_mesage" class="form-control" cols="10" rows="10" placeholder="Place Input mesage?"></textarea>
                  </div>
                  <!--  -->

                </div>
              </div>
            </div>

          </div>



          <input type="hidden" name="cont_id" value="<?= $data["cont_id"] ?>">

        </form>
      </div>
    </div>
  </div>
</div>