<?php

    require_once('live-chat/confing.php');
    require_once('live-chat/class_main.php');
    require_once('live-chat/class_chat.php');
    require_once('live-chat/class_getdata.php');

    $class_chat = new firebase_chat;
    $classgetdata   = new getdata;

    $id = "";
    $name = "";
    $country_id = "";
    $email = "";
    $company_name = "";
    $country_name = "";

    if(isset($_COOKIE["ssoid"])){
      $id = $_COOKIE["ssoid"];
      $sql = "SELECT * FROM `tt_sso_login` WHERE sso_id = '$id'";
      $query = $conn->query($sql);
      while ($res = $query->fetch_assoc()) {
        $name = $res["sso_name_en"];
        $country_id = $res["sso_naturalId"];
        $email = $res["sso_email"];
        if($res["sso_company_en"] != ""){
          $company_name = $res["sso_company_en"];
        }else{
          $company_name = $res["sso_name_en"];
        }

        $country_name = $res["sso_country"];
      }
    }

    function time_elapsed_string($datetime, $full = false) {
        $now = new DateTime;
        // date('Y-m-d H:i:s', floor(1664035524667/1000))
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);

        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;

        $string = array(
            'y' => 'year',
            'm' => 'month',
            'w' => 'week',
            'd' => 'day',
            'h' => 'hour',
            'i' => 'minute',
            's' => 'second',
        );
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
            } else {
                unset($string[$k]);
            }
        }

        if (!$full) $string = array_slice($string, 0, 1);
        return $string ? implode(', ', $string) . ' ago' : 'just now';
    }


    $sql_time = "SELECT * FROM `tt_chat_log` WHERE log_end = '0000-00-00 00:00:00'";
    $query_time = $conn->query($sql_time);
    while ($res_time = $query_time->fetch_assoc()) {
        $sql_room = "SELECT * FROM `tt_chat_room` WHERE room_key = '".$res_time["log_room_key"]."'";
        $query_room = $conn->query($sql_room);
        while ($res_room = $query_room->fetch_assoc()) {
          $now = time();
          $from_time = strtotime($res_room["room_date"]);
          $diff_time = round(abs($now - $from_time));
          if((int)$diff_time > 1800){
            $update = "UPDATE `tt_chat_log` SET `log_end` = NOW() WHERE `tt_chat_log`.`log_id` = '".$res_time["log_id"]."'";
            $query_update = $conn->query($update);
          }
        }
    }


 ?>
<style>
    .box_chat {
        width: 120px;
        right: 0px;
        bottom: 0px;
        padding: 0px;
        z-index: 1;
        transition: bottom 0.5s ease 0s;
        cursor: pointer;
    }

    .box_chat #noti_chat {
        position: absolute;
        right: 40px;
        top: 35px;
        font-size: 15px;
        color: red;
    }

    .box_chat img {
        width: 100%;
    }

    .list_chat {
        display: none;
        position: fixed;
        right: 5px;
        bottom: 10px;
        box-shadow: rgb(51 51 51) 0px 2px 7px 0px;
        width: 350px;
        z-index: 1000;
        transition: bottom 0.5s;
        overflow: hidden;
        background: linear-gradient(50deg, rgb(20 100 192) 0%, rgb(87 179 238) 100%);
        color: #fff;
        border: 0px;
        border-radius: 5px;
        padding: 0px 15px 15px 15px;
    }

    .list_chat .header_chat {
        display: inline-block;
        width: 100%;
        position: relative;
    }

    .list_chat .header_chat .taget_chat {
        font-size: 24px;
        padding: 0px 5px;
        text-overflow: ellipsis;
        overflow: hidden;
        box-shadow: inset 0px 0px 10px 0px #3333;
    }
    .taget_chat{
      position: relative;
    }
    .taget_chat .txt{
      text-align: center;
      background: #EDEEEF;
      padding: 10px;
      color: #707070;
      font-size: 14px;
    }
    .taget_chat .backChat{
      position: absolute;
      top: 0;
      left: 15px;
      bottom: 0;
      margin: auto;
      height: 18px;
      color: #000;
    }

    .list_chat .header_chat .closeChat {
        /* position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 15px;
        padding: 5px 8px; */
        position: absolute;
        right: 10px;
        top: 50%;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 15px;
        padding: 5px 8px;
        font-style: normal;
    }

    .list_chat .header_chat .CloseMessageChat {
        display: none;
        position: absolute;
        left: 10px;
        top: 72%;
        transform: translateY(-50%);
        cursor: pointer;
        font-size: 15px;
        padding: 5px 8px;
        width: 28px;
        fill: #daa560;

    }

    .list_chat .section_search_chat {
        position: relative;
        padding: 5px;
        background: #fff;
        border-bottom: 1px solid;
        border-top-left-radius: 5px;
        border-top-right-radius: 5px;
        /* display: none; */
    }

    .list_chat .section_search_chat #search_company_chat {
        height: 35px;
        border: unset;
        padding-left: 20px;
        background: #fff;
        border-radius: 4px;
        border: 1px solid #d3d3d3;
        width: 85%;
        border: 0;
        vertical-align: middle;

    }

    .list_chat .section_search_chat .iconSearch {
        margin-left: 10px;
        position: absolute;
        margin-top: 9px;
        margin-right: 5px;
        cursor: pointer;
        color: #000;
    }

    .list_chat .list_exhibitor,
    .list_chat .message_chat {
        height: 440px;
        /* overflow: auto; */
        font-size: 18px;
        background-color: white;
        border-radius: 5px;
        border-top-left-radius: 0px;
        border-top-right-radius: 0px;
    }
    .list_exhibitor{
      /* display: none; */
    }

    .list_chat .list_exhibitor .box_exhibitor {
        cursor: pointer;
        display: inline-block;
        width: 100%;
        padding: 5px;
        border-bottom: 1px solid;
    }

    .list_chat .list_exhibitor .box_exhibitor.active {
        background: #FEE591;
    }

    .list_chat .list_exhibitor .box_exhibitor .box_exhibitor_img {
        width: 16%;
        float: left;
        padding: 0px 5px;
    }

    .list_chat .list_exhibitor .box_exhibitor .box_exhibitor_img img {
        border: 2px solid #5db9f1;
        border-radius: 50%;
        object-fit: cover;
        height: 40px;
        width: 40px;
    }

    .list_chat .list_exhibitor .box_exhibitor .box_exhibitor_name {
        width: 84%;
        float: left;
        /* font-size: 24px; */
        text-overflow: ellipsis;
        overflow: hidden;
        line-height: 1;
        padding: 0px 5px;
        margin-top: 5px;
    }

    .list_chat .list_exhibitor .box_exhibitor .box_company_send_chat {
        white-space: nowrap;
        display: block;
        overflow: hidden;
        text-overflow: ellipsis;
        font-size: 12px;
        padding-right: 20px;
        position: relative;
        color: #4a4a4a;
        line-height: 15px;
    }

    .list_chat .list_exhibitor .box_exhibitor .box_company_send_chat i {
        position: absolute;
        font-size: 8px;
        right: 0;
        top: 50%;
        transform: translateY(-50%);
        color: red;
    }

    .list_chat .message_chat {
        display: none;
    }

    .list_chat .message_chat .input_message_chat {
        /* border-top: 2px solid white; */
        height: 390px;
        background: #fff;
        overflow: auto;
        position: relative;
        border-radius: 5px;
    }

    .list_chat .message_chat .tool_chat {
        height: 50px;
        padding: 7px 10px;
        /* box-shadow: inset 0px 0px 10px 0px #3333;
        border-radius: 0px 0px 0px 25px; */
        border-top: 1px solid;
        background: rgb(199 199 199 / 31%);
    }

    .list_chat .message_chat .tool_chat .input_file_image {
        padding: 5px;
        display: inline-block;
        color: #9b9b9b;
        cursor: pointer;
    }

    .list_chat .message_chat .tool_chat .input_file_files {
        padding: 5px;
        display: inline-block;
        color: #9b9b9b;
        font-size: 15px;
        cursor: pointer;
    }

    .list_chat .message_chat .tool_chat .input_file_files i {
        transform: rotate(-45deg);
    }
    textarea:focus, input:focus{
        outline: none;
    }

    .list_chat .message_chat .tool_chat .input_type_message {
        width: 205px;
        border-radius: 5px;
        border: none;
        padding: 0 5px;
        color: #777777;
    }
    .input_type_message{
      vertical-align: middle;
    }

    .list_chat .message_chat .tool_chat .input_sendMessage {
        /* padding: 5px; */
        display: inline-block;
        color: #9b9b9b;
        font-size: 16px;
        cursor: pointer;
        padding-left: 7px;
    }


    #myModal_Zoom {
        display: none;
        position: fixed;
        z-index: 1;
        padding-top: 100px;
        left: 0px;
        top: 0px;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.9);
        z-index: 1001;
    }

    #myModal_Zoom .close {
        position: absolute;
        top: 15px;
        right: 35px;
        color: #f1f1f1;
        font-size: 40px;
        font-weight: bold;
        transition: 0.3s;
    }

    #myModal_Zoom #img01 {
        margin: auto;
        display: block;
        width: 80%;
        max-width: 700px;
        -webkit-animation-name: zoom;
        -webkit-animation-duration: 0.6s;
        animation-name: zoom;
        animation-duration: 0.6s;
    }

    @media screen and (max-width: 425px) {
        .list_chat {
            width: 400px;
        }

        .list_chat .message_chat .tool_chat .input_type_message {
            width: 200px;
        }
    }

    @media screen and (max-width: 375px) {
        .list_chat {
            width: 350px;
        }

        .list_chat .message_chat .tool_chat .input_type_message {
            width: 200px;
        }
    }

    @media screen and (max-width: 320px) {
        .list_chat {
            width: 270px;
        }

        .list_chat .message_chat .tool_chat .input_type_message {
            width: auto;
        }
    }

    @media screen and (max-height: 450px) {

        .list_chat .list_exhibitor,
        .list_chat .message_chat {
            height: 200px;
        }

        .list_chat .message_chat .input_message_chat {
            height: 150px
        }
    }

    .target_chat.show{
        text-align: center;
        background: #fff;
        padding: 10px;
        border: solid 1px #ceced4;
        padding-left: 40px;
        /* overflow: hidden; */
        white-space: nowrap;
        text-overflow: ellipsis;
    }

    .live_chat{
        padding: 10px;
    }
    .message_chat::-webkit-scrollbar {
      display: none;
    }
    .input_message_chat::-webkit-scrollbar {
      display: none;
    }

    /* Hide scrollbar for IE, Edge and Firefox */
    .message_chat, .input_message_chat {
      -ms-overflow-style: none;  /* IE and Edge */
      scrollbar-width: none;  /* Firefox */
    }
    .imgChat{
      border: 1px solid white;
      border-radius: 50%;
      object-fit: cover;
      height: 40px;
      width: 40px;
    }
    .user-text{
      background: #DAA560;
      color: #000;
      padding: 5px;
      display: inline-block;
      margin: 5px;
      max-width: 70%;
      border-radius: 5px;
      font-size: 14px;
    }
    .exhibitor-text{
      background: #96999C;
      color: #000;
      padding: 5px;
      display: inline-block;
      margin: 5px;
      max-width: 70%;
      border-radius: 5px;
      font-size: 14px;
    }
    .svgImg{
      width: 18px;
      fill: #c7c7c7;
    }
    .svgPaperclip{
      width: 15px;
      fill: #c7c7c7;
      transform: rotate(315deg);
    }
    .svgSendMessage{
      width: 15px;
      fill: #4ca6e7;
    }
    .FrameNoChat{
      text-align: center;
      margin: auto;
      left: 0;
      right: 0;
      top: 0;
      bottom: 0;
      position: absolute;
      height: 150px;
    }
    .btn-search-no{
      background: #daa560;
      padding: 5px 31px;
    }
    .chat-icon{
      width: 18px;
      fill: #fff;
      vertical-align: text-top;
    }
    .box_company_name_chat{
      color: #000;
      font-size: 15px;
      font-weight: bold;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
      width: 250px;
      display: inline-block;
    }
    .timeChat{
      float: right;
    }
    .btn-search{
      padding: 0;
      background: #fbac23;
      width: 35px;
      height: 35px;
      fill: #fff;
      border-radius: 5px;
      float: right;
    }
    .btn-search svg{
      width: 17px;
    }
    .box-search{
      border: 1px solid #00000030;
      border-radius: 5px;
    }
    .buyer{
      margin-bottom: 5px;
      padding: 10px;
      text-align: right;
      position: relative;
      word-break: break-word;
    }
    .buyer div{
      background: #398fd8;
      padding: 5px 15px;
      border-radius: 5px;
      font-size: 16px;
      display: inline-block;
      text-align: left;
      border-bottom-right-radius: 0;
      position: relative;
      max-width: 90%;
    }
    .buyer span{
      display: block;
      position: absolute;
      right: 10px;
      font-size: 10px;
      color: rgb(158 158 158);
    }
    .buyer .read::before{
      content: 'read';
      position: absolute;
      left: -25px;
      bottom: -2px;
      color: #9e9e9e;
      font-size: 10px;
    }

    .company{
      margin-bottom: 5px;
      padding: 10px;
      text-align: left;
      position: relative;
      word-break: break-word;
    }
    .company div{
      background: #b5b5b5;
      padding: 5px 15px;
      border-radius: 5px;
      font-size: 16px;
      display: inline-block;
      text-align: left;
      border-top-left-radius: 0;
      position: relative;
      max-width: 90%;
    }
    .company span{
      display: block;
      position: absolute;
      left: 10px;
      font-size: 10px;
      color: rgb(158 158 158);
    }
    .company .read::before{
      content: 'read';
      position: absolute;
      right: -25px;
      bottom: -2px;
      color: #9e9e9e;
      font-size: 10px;
    }
    .live_chat span{
      font-weight: bold;
      width: 245px;
      display: inline-block;
      vertical-align: middle;
      white-space: nowrap;
      overflow: hidden;
      text-overflow: ellipsis;
    }
    .float-btn{
      width: 60px;
      height: 60px;
      display: flex;
      text-align: center;
      position: fixed;
      z-index: 111;
      bottom: 180px;
      right: 90px;
      border-radius: 50%;
      background: linear-gradient(90deg, #68c6f8 0%, #004cb2 100%);
      cursor: pointer;
    }
    .float-insite{
      width: 54px;
      height: 54px;
      vertical-align: middle;
      display: flex;
      margin: auto;
      border-radius: 50%;
      background: #fff;
    }
</style>

<?php

  $chat_company = [];
  $get_chat_room = [];
  if(isset($_COOKIE["ssoid"])){
  $chat_company = $classgetdata->get_chat_log($_COOKIE["ssoid"]);
  // echo "<pre>";
  // print_r($chat_company);
  // echo "</pre>";
  // exit();
  $get_chat_room = $classgetdata->get_chat_room($_COOKIE["ssoid"]);
}


$date = new DateTime();
$timeZone = $date->getTimezone();

$page = '';
if(!empty($Company->company_id))
    $page = $Company->company_id;

?>
<?php if(isset($_COOKIE["ssoid"])): ?>


<div class="float-btn">
  <div class="float-insite">
    <!-- <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" style="width: 50%;margin: auto;fill: #3a90d9;">
      <path d="M64 0C28.7 0 0 28.7 0 64V352c0 35.3 28.7 64 64 64h96v80c0 6.1 3.4 11.6 8.8 14.3s11.9 2.1 16.8-1.5L309.3 416H448c35.3 0 64-28.7 64-64V64c0-35.3-28.7-64-64-64H64z"/>
    </svg> -->
    <img src="/live-chat/thaitrade logo-04.svg" style="width: 50%;margin: auto;fill: #3a90d9;">
  </div>
</div>
<?php endif; ?>

<input type="hidden" id="timezone" value="<?php echo $timeZone->getName(); ?>">

<div id="myModal_Zoom" class="modal">
    <span class="close">×</span>
    <img class="modal-content" id="img01">
</div>


<div class="list_chat">

    <div class="header_chat">
        <div class="live_chat">
          <img src="/live-chat/textsms-material.png" alt="">
            <span style="font-weight: bold;">LIVE CHAT </span>
        </div>

        <div class="target_chat">

        </div>

        <i class="closeChat" aria-hidden="true" onclick="closeChat()">X</i>
        <svg class="CloseMessageChat" aria-hidden="true" onclick="CloseMessageChat()" viewBox="0 0 320 512"><path d="M224 480c-8.188 0-16.38-3.125-22.62-9.375l-192-192c-12.5-12.5-12.5-32.75 0-45.25l192-192c12.5-12.5 32.75-12.5 45.25 0s12.5 32.75 0 45.25L77.25 256l169.4 169.4c12.5 12.5 12.5 32.75 0 45.25C240.4 476.9 232.2 480 224 480z"/></svg>
    </div>

    <div class="section_search_chat">
      <div class="box-search">
        <input type="text" id="search_company_chat" onkeyup="autoComplete_chat(this.value);">
        <button type="button" name="button" class="btn btn-search">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><!-- Font Awesome Pro 5.15.4 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) --><path d="M505 442.7L405.3 343c-4.5-4.5-10.6-7-17-7H372c27.6-35.3 44-79.7 44-128C416 93.1 322.9 0 208 0S0 93.1 0 208s93.1 208 208 208c48.3 0 92.7-16.4 128-44v16.3c0 6.4 2.5 12.5 7 17l99.7 99.7c9.4 9.4 24.6 9.4 33.9 0l28.3-28.3c9.4-9.4 9.4-24.6.1-34zM208 336c-70.7 0-128-57.2-128-128 0-70.7 57.2-128 128-128 70.7 0 128 57.2 128 128 0 70.7-57.2 128-128 128z"/></svg>
        </button>
      </div>

    </div>

    <div class="list_exhibitor">
      <?php foreach ($chat_company as $key => $value): ?>
      <div class="box_exhibitor" data-room_key="<?php echo $get_chat_room[$value['company_tax_id']]; ?>" data-chat_exhibitor_<?php echo $value['company_tax_id']; ?>="<?php echo $value['company_tax_id']; ?>"
        onclick='ShowMessageChat("<?=$value["company_tax_id"]?>", "<?php echo $value["company_name"]; ?>", this);'
        >
          <div class="box_exhibitor_img">
            <img id="image_company_chat" src="/live-chat/iconUser.png">
          </div>
          <div class="box_exhibitor_name">
              <span class="box_company_name_chat"><?php echo $value['company_name']; ?></span>
              <div class="box_company_send_chat"> <span class="timeChat"><?=time_elapsed_string($value['chat_time'])?></span> </div>
          </div>
      </div>
      <?php endforeach; ?>
    </div>

    <div class="message_chat">
    <!-- <div class="message_chat" style="display: block;"> -->
        <div class="taget_chat">
        </div>

        <div class="input_message_chat">
          <!-- <div class="item_chat buyer">
            <div class="read">ทดสอบ</div>
            <span>10 Jul 2022, 10:10</span>
          </div>
          <div class="item_chat company">
            <div class="read">ทดสอบ</div>
            <span>10 Jul 2022, 10:10</span>
          </div> -->
        </div>

        <div class="tool_chat">
            <div class="input_file_image">
                <svg viewBox="0 0 512 512" class="svgImg" onclick="$('#file_image_chat').click();"><path d="M447.1 32h-384C28.64 32-.0091 60.65-.0091 96v320c0 35.35 28.65 64 63.1 64h384c35.35 0 64-28.65 64-64V96C511.1 60.65 483.3 32 447.1 32zM111.1 96c26.51 0 48 21.49 48 48S138.5 192 111.1 192s-48-21.49-48-48S85.48 96 111.1 96zM446.1 407.6C443.3 412.8 437.9 416 432 416H82.01c-6.021 0-11.53-3.379-14.26-8.75c-2.73-5.367-2.215-11.81 1.334-16.68l70-96C142.1 290.4 146.9 288 152 288s9.916 2.441 12.93 6.574l32.46 44.51l93.3-139.1C293.7 194.7 298.7 192 304 192s10.35 2.672 13.31 7.125l128 192C448.6 396 448.9 402.3 446.1 407.6z"/></svg>
                <input type="file" name="file_image_chat" id="file_image_chat" accept="image/*" onchange="UploadFileOnChat('file_image_chat');" style="display: none;">
            </div>

            <div class="input_file_files">
              <svg viewBox="0 0 448 512" class="svgPaperclip" onclick="$('#file_all_chat').click();"><!--! Font Awesome Pro 6.0.0 by @fontawesome - https://fontawesome.com License - https://fontawesome.com/license (Commercial License) Copyright 2022 Fonticons, Inc. --><path d="M364.2 83.8C339.8 59.39 300.2 59.39 275.8 83.8L91.8 267.8C49.71 309.9 49.71 378.1 91.8 420.2C133.9 462.3 202.1 462.3 244.2 420.2L396.2 268.2C407.1 257.3 424.9 257.3 435.8 268.2C446.7 279.1 446.7 296.9 435.8 307.8L283.8 459.8C219.8 523.8 116.2 523.8 52.2 459.8C-11.75 395.8-11.75 292.2 52.2 228.2L236.2 44.2C282.5-2.08 357.5-2.08 403.8 44.2C450.1 90.48 450.1 165.5 403.8 211.8L227.8 387.8C199.2 416.4 152.8 416.4 124.2 387.8C95.59 359.2 95.59 312.8 124.2 284.2L268.2 140.2C279.1 129.3 296.9 129.3 307.8 140.2C318.7 151.1 318.7 168.9 307.8 179.8L163.8 323.8C157.1 330.5 157.1 341.5 163.8 348.2C170.5 354.9 181.5 354.9 188.2 348.2L364.2 172.2C388.6 147.8 388.6 108.2 364.2 83.8V83.8z"/></svg>
                <!-- <i class="fas fa-paperclip" onclick="$('#file_all_chat').click();"></i> -->
                <input type="file" name="file_all_chat" id="file_all_chat" accept="application/pdf" onchange="UploadFileOnChat('file_all_chat');" style="display: none;">
            </div>

            <input type="text" id="text_message_chat" name="text_message_chat" class="input_type_message" placeholder="Message..." autocomplete="off">

            <div class="input_sendMessage">
              <img src="/live-chat/thaitrade logo-05.svg" class="svgSendMessage" onclick="sendMessage($('#text_message_chat').val());">
              <!-- <svg viewBox="0 0 512 512" class="svgSendMessage" onclick="sendMessage($('#text_message_chat').val());">
                <path d="M511.6 36.86l-64 415.1c-1.5 9.734-7.375 18.22-15.97 23.05c-4.844 2.719-10.27 4.097-15.68 4.097c-4.188 0-8.319-.8154-12.29-2.472l-122.6-51.1l-50.86 76.29C226.3 508.5 219.8 512 212.8 512C201.3 512 192 502.7 192 491.2v-96.18c0-7.115 2.372-14.03 6.742-19.64L416 96l-293.7 264.3L19.69 317.5C8.438 312.8 .8125 302.2 .0625 289.1s5.469-23.72 16.06-29.77l448-255.1c10.69-6.109 23.88-5.547 34 1.406S513.5 24.72 511.6 36.86z"/></svg> -->
                <!-- <i class="fas fa-paper-plane" onclick="sendMessage($('#text_message_chat').val());"></i> -->
            </div>
        </div>
    </div>

</div>

<script src="https://www.gstatic.com/firebasejs/7.17.2/firebase-app.js"></script>

<script src="https://www.gstatic.com/firebasejs/7.17.2/firebase-analytics.js"></script>

<script src="https://www.gstatic.com/firebasejs/7.17.2/firebase-database.js"></script>
<script>
    $(document).ready(function() {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
      });
    });
    $("body").on("click", ".chatBtn_all", function () {
      $(".list_chat").show();
    })

    $("body").on("click", "#chatBtn", function () {
      console.log('chatBtn');
      $(".list_chat").show();
    })
    var monthNames = ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"];
    var month_names_short = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
    var list_room_key = [];
    var availableTags = [];

    var keyRoom = '';
    var exhibitor_id = $('#com_taxno').val();
    var room_type = $('#room_type').val();
    var company_name = '';
    var count = 0;
    var firebaseConfig = {
        apiKey: "AIzaSyDAIpYtUl0lGVNgjGp8SwUbbuxPE81YeM0",
        authDomain: "thaitradefair.firebaseapp.com",
        databaseURL: "https://thaitradefair-default-rtdb.firebaseio.com",
        projectId: "thaitradefair",
        storageBucket: "thaitradefair.appspot.com",
        messagingSenderId: "302523589217",
        appId: "1:302523589217:web:17a6973ec64ea4d9d2c020",
        measurementId: "G-6H3FH6FX70"
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
    firebase.analytics();

    <?php foreach ($get_chat_room as $key => $value) : ?>
        list_room_key.push("<?php echo $value; ?>");
    <?php endforeach; ?>

    <?php foreach ($chat_company as $key => $value) : ?>
        availableTags.push("<?php echo $value['company_name']; ?>");
    <?php endforeach; ?>

    $('body').on('click', '.float-btn', function () {
      CloseMessageChat()
      $('.live_chat span').text('LIVE CHAT')
      $(".list_chat").fadeIn();
    })

    // setting_autocomplete();

    function setting_autocomplete() {
        $("#search_company_chat").autocomplete({
            minChars: 2,
            minLength: 5,
            maxChars: 4,
            source: availableTags,
            select: function(event, ui) {
                autoComplete_chat(ui.item.value);
            }
        });
    }


    function autoComplete_chat(value) {
        if (value != "") {
            $(".list_chat .list_exhibitor .box_exhibitor").removeClass("active");
            var companylist = $(".list_chat .list_exhibitor .box_exhibitor .box_exhibitor_name .box_company_name_chat");
            $.each(companylist, function(index, item) {

                if (item.innerHTML.toLowerCase().search(value.toLowerCase()) >= 0) {
                    $(item).parent().parent().addClass("active");
                    return false;
                }
            });
            var top = 0;
            if ($(".list_chat .list_exhibitor .box_exhibitor.active").length > 0) {
                top = $(".list_chat .list_exhibitor .box_exhibitor.active").offset().top;
            }
            var first_top = $($(".list_chat .list_exhibitor .box_exhibitor")[0]).offset().top;
            $(".list_chat .list_exhibitor").animate({
                scrollTop: (top - first_top)
            }, 300);
        } else {
            $(".list_chat .list_exhibitor .box_exhibitor").removeClass("active");
            $('.list_chat .list_exhibitor').animate({
                scrollTop: 0
            }, 0);
        }
    }

    function openChat(company_id, type) {
        console.log("openChat");
        // var uidx = $('.uidx').val();
        // if(uidx == ""){
        //   $('.SIGN_IN').click();
        //   return false;
        // }
        console.log(company_id);
        if (company_id) {
            var div = $("div.box_exhibitor[data-chat_exhibitor_" + company_id + "]");
            console.log(div);
            
            if (div.length == 0) {

                $.ajax({
                    url: location.origin + "/live-chat/chat.php",
                    type: "post",
                    dataType: "json",
                    async: false,
                    data: {
                        method: 'GetCompanyById',
                        company_id: company_id,
                        room_type: type
                    },
                    success: function(res) {
                        console.log(res);
                        
                        if (res.result) {
                            $('.live_chat span').text(res.data[0].company_name)
                            company_name = res.data[0].company_name
                            $(".list_chat .list_exhibitor").hide();
                            $(".list_chat .message_chat").show();
                            $(".list_chat .list_exhibitor .box_exhibitor").removeClass("active");
                            $(".list_chat .section_search_chat").hide();
                            $(".list_chat .section_search_chat #search_company_chat").val("");
                            /* let html = '';
                            html += '<div class="box_exhibitor" data-room_key="" data-chat_exhibitor_' + company_id + '="' + company_id + '" onclick="ShowMessageChat(' + "'"+company_id+"'" + ',\'' + res.data[0].company_name + '\', this);">';
                            html += '<div class="box_exhibitor_img">';
                            html += `<img id="image_company_chat" src="/live-chat/iconUser.png">`;
                            html += '</div>';
                            html += '<div class="box_exhibitor_name">';
                            html += '<span class="box_company_name_chat">' + res.data[0].company_name + '</span>';
                            html += '<span class="box_company_send_chat"></span>';
                            html += '</div>';
                            html += '</div>';
                            $(".list_exhibitor").html('');
                            $(".input_message_chat").html(''); */
                            console.log($(".list_exhibitor"));
                            availableTags.push(res.data[0].company_name);
                            console.log(availableTags);
                            // setting_autocomplete();
                        } else {
                            return;
                        }
                    }
                });
            }
            $("div.box_exhibitor[data-chat_exhibitor_" + company_id + "]").click();
        }

        $(".box_chat").fadeOut();
        $(".list_chat").fadeIn();
    }
    // setTimeout(function () {
    //   openChat('0105537041030', 1)
    // }, 1000)

    function closeChat() {
        $(".box_chat").fadeIn();
        $(".list_chat").fadeOut();
    }

    function ShowMessageChat(company_id, name, input) {
         
        $(".list_chat .list_exhibitor").hide();
        $(".list_chat .message_chat").show();
        $(".list_chat .list_exhibitor .box_exhibitor").removeClass("active");
        $(".list_chat .section_search_chat").hide();
        $(".list_chat .section_search_chat #search_company_chat").val("");

        $('.live_chat span').text(name)
        company_name = name;


        exhibitor_id = company_id;
        let room_key = $(input).attr("data-room_key");
        keyRoom = room_key;
        
        updateRead(keyRoom);
        console.log("chatroom/" + room_key + "/chat");
        firebase.database().ref("chatroom/" + room_key + "/chat").on("value", function(snapshot) {
            let result = snapshot.val();
            let html = '';
            let message = '';
            console.log(result);
            
            if (keyRoom != room_key) {
                return;
            }

            $.each(result, function(index, item) {
                message = '';
                if (item.message_type == "text") {
                    message = item.message;
                } else if (item.message_type == "photo") {
                    message = '<img src= "' + item.path_resize + '" style="width: 100%; cursor: pointer;" onclick="ZoomImage(\'' + item.path_original + '\')">';
                } else if (item.message_type == "file") {
                    message = '<a href="' + item.path_file + '" target="_blank">' + item.original_name + '</a>';
                }

                let date = new Date(item.time);

                let Y = date.getFullYear();
                let M = date.getMonth();
                let D = date.getDate();
                let h = date.getHours();
                let m = date.getMinutes();
                let date_chat = D + ' ' + month_names_short[M] + ' ' + Y + ', ' + h + ':' + m;
                let read_s = "";
                if (message) {
                    // count++;
                    if ( item.user_id == '<?=$id?>') {
                        if (item.isRead) {
                          read_s = 'read';
                        }
                        html += '<div class="item_chat buyer" >';
                        html += '<div class="'+read_s+'">' + message + '</div>';
                        html += '<span>' + date_chat + '</span>'
                        // if (item.isRead) {
                        //     html += ;
                        // } else {
                        //     html += '<span>' + date_chat + '</span>';
                        // }
                        html += '</div>';
                    } else {
                        html += '<div class="item_chat company">';
                        html += '<div class="" >' + message + '</div>';
                        html += '<span>' + date_chat + '</span>';
                        html += '</div>';
                    }
                }
            });

            $(".input_message_chat").html(html);
            setTimeout(() => {
                $(".input_message_chat").animate({
                    scrollTop: $('.input_message_chat').prop("scrollHeight")
                }, 0);
            }, 100);
        });
    }

    function updateRead(key) {

        firebase.database().ref("chatroom/" + key + "/chat").on("child_added", function(snapshot) {
            if (keyRoom != key) {
                return;
            }
            let result = snapshot.val();
            if (result.user_id != '<?=$id?>') {
                let postChat = firebase.database().ref('/chatroom/' + keyRoom + '/chat/' + snapshot.key);
                postChat.update({
                    "isRead": true
                }).then(res => {
                    $("div[data-chat_exhibitor_" + result.user_id + "] > div.box_exhibitor_name > span.box_company_send_chat").html("");
                });
            }
        });
        if ($(".icon_noti_chat").length == 0) {
            $(".noti_chat").each(function () {
              $(this).hide();
            })
        }

    }


    function CloseMessageChat() {
        $(".list_chat .list_exhibitor").show();
        $(".list_chat .message_chat").hide();
        $(".list_chat .header_chat .closeChat").css("top", "50%");
        $(".list_chat .header_chat .CloseMessageChat").css("display", "none");

        $(".list_chat .header_chat .live_chat").show();
        $(".list_chat .header_chat .target_chat").html("");
        $('.target_chat').removeClass('show');

        $(".list_chat .section_search_chat").show();

        keyRoom = '';
    }

    getChatFirebase();

    function getChatFirebase() {
        count = 0;
        $.each(list_room_key, function(index, item) {
            count++;
            firebase.database().ref("chatroom/" + item + "/chat").on("value", function(snapshot) {
                let result = snapshot.val();
                //
                $.each(result, function(index_r, item_r) {
                    let icon = '';
                    if (item_r.isRead == false && item_r.user_id != '<?=$id?>') {
                        icon = '<div class="icon_noti_chat"></div>';
                        if ($(".noti_chat").css("display") == "none") {
                            $(".noti_chat").each(function () {
                              $(this).css("display" , "block");
                            })
                        }
                    }
                    let date_chat = new Date(item_r.time).toISOString().slice(0, 19).replace('T', ' ');
                    if (item_r.message_type == 'text') {
                        $('div[data-room_key="'+ item +'"] > div.box_exhibitor_name > div.box_company_send_chat').html(item_r.message + '<span class="timeChat">' + timeSince(new Date(item_r.time)) + '</span>');
                    } else if (item_r.message_type == 'photo' || item_r.message_type == 'file') {
                        $('div[data-room_key="'+ item +'"] > div.box_exhibitor_name > span.box_company_send_chat').html('<span>' + 'Send file' + '</span>' + icon);
                    }

                });

            });
        });

        // if(count == 0){
        //   let ht = '<div class="FrameNoChat">'+
        //               '<h6>สนใจติดต่อกับผู้ประกอบการ</h6>'+
        //               '<h6>ค้นหาได้ที่นี่</h6>'+
        //               '<a href="/exhibitor-list-search" class="btn btn-search-no">ค้นหา</a>'+
        //             '</div>';
        //   $('.list_exhibitor').append(ht);
        //   $('.section_search_chat').hide();
        // }

    }
    function timeSince(date) {
      var seconds = Math.floor((new Date() - date) / 1000);
      var interval = seconds / 31536000;
      if (interval > 1) {
        return Math.floor(interval) + " years";
      }
      interval = seconds / 2592000;
      if (interval > 1) {
        return Math.floor(interval) + " months";
      }
      interval = seconds / 86400;
      if (interval > 1) {
        return Math.floor(interval) + " days";
      }
      interval = seconds / 3600;
      if (interval > 1) {
        return Math.floor(interval) + " hours";
      }
      interval = seconds / 60;
      if (interval > 1) {
        return Math.floor(interval) + " minutes";
      }
      return Math.floor(seconds) + " seconds";
    }


    $(".input_type_message").on('keypress', function(event) {
        if (event.keyCode == 13) {
            if ($("#text_message_chat").val().trim() == "") {
                $("#text_message_chat").val("");
                return;
            }
            sendMessage($("#text_message_chat").val());
        }
    });

    function CreateChatRoom(key, company_id, room_type) {
        $.ajax({
            url: location.origin + "/live-chat/chat.php",
            type: "post",
            dataType: "json",
            async: false,
            data: {
                method: 'CreateMessageRoom',
                user_id: '<?=$id?>',
                company_id: company_id,
                room_key: key,
                room_type: room_type
            },
            success: function(res) {
                list_room_key.push(key);
                // getChatFirebase(key);
                // getChatFirebase();
            }
        });
    }

    function sendMessage(message) {
        let html = '';
        html += '<div class="item_chat buyer">';
        html += '<div class="read">' + message + '</div>';
        html += '<span>10 Jul 2022, 10:10</span>';
        html += '</div>';

        if ($("#text_message_chat").val("")) {

            setTimeout(() => {
                if (message == "") {
                    $("#text_message_chat").val("");
                    return;
                }
                
                if (!keyRoom) {

                    $(".input_message_chat").html(html);
                    $(".input_message_chat").animate({
                        scrollTop: $('.input_message_chat').prop("scrollHeight")
                    }, 0);

                    let postRef = firebase.database().ref('/chatroom');
                    postRef.push({
                        "offset": "+07:00",
                        "room_update": new Date().getTime(),
                        "time_zone": $("#timezone").val() || "Asia/Bangkok",
                    }).then(res => {
                        keyRoom = res.key;
                        $("div[data-chat_exhibitor_" + exhibitor_id + "]").attr("data-room_key", keyRoom);
                        CreateChatRoom(keyRoom, exhibitor_id, room_type);
                        $(".input_message_chat").html("");
                        sendMessage(message);
                        getChatFirebase();
                        $("div[data-chat_exhibitor_" + exhibitor_id + "]").click();
                    });
                } else {
                    $(".input_message_chat").append(html);
                    $(".input_message_chat").animate({
                        scrollTop: $('.input_message_chat').prop("scrollHeight")
                    }, 0);

                    let postChat = firebase.database().ref('/chatroom/' + keyRoom + '/chat');
                    postChat.push({
                        "message": message,
                        "message_type": "text",
                        "isRead": false,
                        "status": 1,
                        "time": new Date().getTime(),
                        "time_zone": $("#timezone").val(),
                        "user_id": '<?=$id?>'
                    }).then(res => {
                        $(".input_message_chat").animate({
                            scrollTop: $('.input_message_chat').prop("scrollHeight")
                        }, 0);
                    });

                    // $.ajax({
                    //     url: location.origin + "/backoffice/web/Model/method.php",
                    //     type: "post",
                    //     dataType: "json",
                    //     async: false,
                    //     data: {
                    //         method: 'noti_chat',
                    //         user_id: '<?=$id?>',
                    //         company_id: exhibitor_id,
                    //         chat_room: keyRoom
                    //     },
                    //     success: function(res) {}
                    // });

                    $.ajax({
                        url: location.origin + "/live-chat/method.php",
                        type: "post",
                        dataType: "json",
                        async: false,
                        data: {
                            method: 'update_chatroom',
                            keyRoom: keyRoom,
                            user_id: '<?=$id?>',
                            company_id: exhibitor_id,
                            room_type: room_type
                        },
                        success: function(res) {

                        }
                    });
                }
            }, 100);
            send_one("1",keyRoom,exhibitor_id,message);
        }
    }

    function send_one(room_id,room_key,user_ditpone,message) {
      var company = company_name;
        $.ajax({
        url: "https://one.ditp.go.th/api/getDataChat",
        type: "post",
        dataType: "json",
        async: false,
        data: {
              room_id : room_id,
              room_key : room_key,
              user_thaitrade: "<?=$id?>",
              company: "<?=$company_name?>",
              fullname: "<?=$name?>",
              country: "<?=$country_name?>",
              email: "<?=$email?>",
              user_ditpone: user_ditpone,
              message: message,
              isRead:"1"
            },
        success: function(res) {
          // console.log(res);
        }
      });
    }

    function ZoomImage(url) {
        $("#myModal_Zoom").css('display', 'block');
        $("#myModal_Zoom #img01").attr("src", url);
    }
    $("#myModal_Zoom .close").click(function() {
        $("#myModal_Zoom").css("display", "none");
    });

    function UploadFileOnChat(id_input_file) {

        message = '<i class="fas fa-circle-notch fa-spin" style="font-size: 24px;"></i>';

        let html = '';
        html += '<div class="item_chat buyer">';
        html += '<div >' + message + '</div>';
        html += '</div>';
        
        
        if (!keyRoom) {

            $(".input_message_chat").html(html);
            $(".input_message_chat").animate({
                scrollTop: $('.input_message_chat').prop("scrollHeight")
            }, 0);

            let postRef = firebase.database().ref('/chatroom');
            postRef.push({
                "offset": "+07:00",
                "room_update": new Date().getTime(),
                "time_zone": $("#timezone").val(),
            }).then(res => {
                keyRoom = res.key;
                $("div[data-chat_exhibitor_" + exhibitor_id + "]").attr("data-room_key", keyRoom);
                CreateChatRoom(keyRoom, exhibitor_id, room_type);
                $(".input_message_chat").html("");
                UploadFileOnChat(id_input_file);
                getChatFirebase();
                $("div[data-chat_exhibitor_" + exhibitor_id + "]").click();
                $.ajax({
                    url: location.origin + "/live-chat/method.php",
                    type: "post",
                    dataType: "json",
                    async: false,
                    data: {
                        method: 'update_chatroom',
                        keyRoom: keyRoom,
                        user_id: '<?=$id?>',
                        room_type: room_type
                    },
                    success: function(res) {

                    }
                });
            });
        } else {
            $(".input_message_chat").append(html);
            $(".input_message_chat").animate({
                scrollTop: $('.input_message_chat').prop("scrollHeight")
            }, 0);

            var data = new FormData();
            data.append('method', 'upload_file_chat');
            data.append('file', $("#" + id_input_file).get(0).files[0]);
            data.append('company_id', exhibitor_id);

            if (id_input_file == "file_image_chat") {
                data.append('type', 'photo');
                let message_type = "photo";
            } else {
                data.append('type', 'file');
                let message_type = "file";
            }

            $("#" + id_input_file).val("");

            $.ajax({
                url: location.origin + '/live-chat/method.php',
                dataType: "json",
                type: 'POST',
                data: data,
                success: function(res) {

                    if (res.result) {

                        if (res.type == "photo") {
                            send_one("1",keyRoom,exhibitor_id,res.data.path_original);
                            let postChat = firebase.database().ref('/chatroom/' + keyRoom + '/chat');
                            postChat.push({
                                "path_original": res.data.path_original,
                                "path_resize": res.data.path_resize,
                                "message_type": 'photo',
                                "isRead": false,
                                "status": 1,
                                "time": new Date().getTime(),
                                "time_zone": $("#timezone").val(),
                                "user_id": '<?=$id?>'
                            }).then(res => {
                                $(".input_message_chat").animate({
                                    scrollTop: $('.input_message_chat').prop("scrollHeight")
                                }, 0);

                            });

                        } else if (res.type == "file") {
                          send_one("1",keyRoom,exhibitor_id,res.data.path_file);
                            let postChat = firebase.database().ref('/chatroom/' + keyRoom + '/chat');
                            postChat.push({
                                "path_file": res.data.path_file,
                                "size_file": res.data.size_file,
                                "original_name": res.data.original_name,
                                "message_type": 'file',
                                "isRead": false,
                                "status": 1,
                                "time": new Date().getTime(),
                                "time_zone": $("#timezone").val(),
                                "user_id": '<?=$id?>'
                            }).then(res => {
                                $(".input_message_chat").animate({
                                    scrollTop: $('.input_message_chat').prop("scrollHeight")
                                }, 0);

                            });

                        }

                        // $.ajax({
                        //     url: location.origin + "/live_chat/method.php",
                        //     type: "post",
                        //     dataType: "json",
                        //     async: false,
                        //     data: {
                        //         method: 'noti_chat',
                        //         user_id: '<?=$id?>',
                        //         company_id: exhibitor_id,
                        //         chat_room: keyRoom
                        //     },
                        //     success: function(res) {
                        //
                        //     }
                        // });
                        $.ajax({
                            url: location.origin + "/live-chat/method.php",
                            type: "post",
                            dataType: "json",
                            async: false,
                            data: {
                                method: 'update_chatroom',
                                keyRoom: keyRoom,
                                user_id: '<?=$id?>',
                                company_id: exhibitor_id,
                                room_type: room_type
                            },
                            success: function(res) {

                            }
                        });
                    }
                },
                error: function(res) {

                },
                processData: false,
                contentType: false
            });
        }
    }

</script>
