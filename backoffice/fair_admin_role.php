<?php
include "connect.php";

if($_REQUEST["method"]=="flist"){
  $gid = $_GET["gid"];
  $fid = $_GET["id"];
  $_SESSION["frgid"] = $gid;
	$_SESSION["frlid"] = $fid;
  header('Location: home.php');
  exit();
}
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $site_name;?> | Fair Admin Role</title>

    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <!-- Toastr style -->
    <link href="css/plugins/toastr/toastr.min.css" rel="stylesheet">


    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/wms_login.css" rel="stylesheet">

		<link href="js/sweetalert/sweetalert.css" rel="stylesheet">
		<script type="text/javascript" src="js/sweetalert/sweetalert.min.js"></script>
		<script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <style media="screen">
    .boxlogin_f {
            max-height: 550px;
            overflow: hidden;
            overflow-y: scroll; /* showing scrollbars */

      }

    ::-webkit-scrollbar {
        width: 5px;
        color: #f00;
    }
    </style>
</head>

<body class="gray-bg bg_backoffice">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div class="boxlogin_f">
            <div>
							<img src="asset/logo.png" >

            </div>
						<br>
            <h3>Choose your Fair</h3>

            <div class="row">
              <?
              $sqlr = " select * from tt_fair_list_admin a left join tt_fair_list b on a.fair_id=b.fair_id where a.admin_id = ? and b.fair_status != '9' ";
        			$stmtr = $mysqli->prepare($sqlr);
        		  if($stmtr) {
        		    $stmtr->bind_param('i',$_SESSION["id"]);
        		    $stmtr->execute();
                $resultr = $stmtr->get_result();
        		    $numrowr = $resultr->num_rows;
        		    if($numrowr>0) {
        		      while($datar = $resultr->fetch_assoc()) {

                    $sqlrl = " select * from tt_fair_group_list a left join tt_fair_group b on a.fair_group_id=b.fair_group_id where a.fair_id = ? and a.fair_flag != '9' and b.fair_group_status != '9' ";
              			$stmtrl = $mysqli->prepare($sqlrl);
            		    $stmtrl->bind_param('i',$datar["fair_id"]);
            		    $stmtrl->execute();
                    $resultrl = $stmtrl->get_result();
            		    $numrowrl = $resultrl->num_rows;
            		    if($numrowrl>0) {
            		      $datarl = $resultrl->fetch_assoc();
                    ?>
                    <div class="col-xs-12" onclick="window.location='fair_admin_role.php?method=flist&id=<?=$datar["fair_id"]?>&gid=<?=$datarl["fair_group_id"]?>';">
                      <div class="fblog">
                        <div class="fblog_inner">
                          <div class="row">
                            <div class="col-xs-3 text-center">
                              <img class="flogo-img" src="<?=ROOTPATHDOMAIN?><?=$datarl["fair_group_logo_path"]?>" width="100%">
                            </div>
                            <div class="col-xs-9 text-left">
                              <span class="fname-size"><?=$datar["fair_name"]?></span>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?
                    }
                  }
                }
              }
              ?>
            </div>

        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- Toastr script -->
    <script src="js/plugins/toastr/toastr.min.js"></script>
</body>
</html>
