<?php
$menu_name = "API TOKEN";
$save_link = "adfair_token";
$back_link = "adfair_list";


$id = $_GET["fair_id"];
$sql = "select * from tt_fair_list where fair_id = ? ";
$stmt = $mysqli->prepare($sql);
if($stmt) {
  $stmt->bind_param('i',$id);
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {
    $data = $result->fetch_assoc();
  } else {
    ?>
    <script type="text/javascript">
      top.window.location='home.php?show=<?=$back_link?>';
    </script>
    <?
    exit();
  }
} else {
    ?>
    <script type="text/javascript">
      top.window.location='home.php?show=<?=$back_link?>';
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
               <a href="home.php?show=<?=$back_link?>"><h3><i class="fa fa-angle-left backnav-size " aria-hidden="true"></i> <span class="backnav-size-txt">BACK</span></h3></a>
           </li>
       </ol>
       <div class="bottom-blue"></div>
    </div>

</div>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
        	<div class="ibox float-e-margins">

            <h3><?=$menu_name?></h3>
            <div class="ibox-content">


                <div class="row">
                  <div class="col-xs-6">
                    <div class="form-group">
                      <label class="col-lg-12 control-label">API TOKEN</label>
                      <div class="col-lg-12" >

                        <div class="input_copy_wrapper">
                          <div class="input_copy">
                            <span class="txt"><?=$data["fair_token"]?></span>
                            <span class="icon right">
                             <img src="../backoffice/asset/icon_clipboard.png"
                                  title="Click to Copy" height="20"
                              >
                            </span>
                          </div>

                        </div>


                      </div>
                    </div>
                  </div>
                </div>

                <br>
                <div class="row">
                  <div class="col-xs-12">
                    <div class="form-group">
                      <a href="api-document.pdf" target="_blank" class="btn btn-success" style="width:250px; margin-left:15px;">ดาวน์โหลดคู่มือการใช้งาน</a>
                    </div>
                  </div>
                </div>


            </div>


          </div>
        </div>
	</div>
</div>

<style media="screen">

.input_copy {
  padding: 5px;
  background: #eee;
  border: 1px solid #aaa;
}

.input_copy .icon {
  display: block;
  max-width: 25px;
  cursor: pointer;
  float: right;
}

.input_copy .icon img{
max-width: 25px;
}
.input_copy .txt {
  width: 80%;
  display: inline-block;
  overflow: hidden;
}



/* click animation */

.flashBG {
  animation-name: flash;
  animation-timing-function: ease-out;
  animation-duration: 1s;
}

@keyframes flash {
  0% {
      background: #28a745;
  }
  100% {
      background: transparent;
  }
}


</style>

<script type="text/javascript">
function copyToClipboard(element) {
    var $temp = $("<input>");
    $("body").append($temp);
    $temp.val($(element).text()).select();
    document.execCommand("copy");
    $temp.remove();
}

 var addrsField = $('.input_copy .txt');
$('.input_copy .icon').click(function() {
        copyToClipboard('.input_copy .txt');
        addrsField.addClass('flashBG')
          .delay('1000').queue(function(){
            addrsField.removeClass('flashBG').dequeue();
        });
    });
</script>
