<?php
include "connect.php";


if($_POST["username"]!="" and $_POST["password"]!="" and $_POST["method"]=="1"){

	$ip = get_real_ip();
	$username = $_POST["username"];
	$password = $_POST["password"];

	$sql = "select * from tt_admin where username = ? and password = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('ss',$username,$password);
    $stmt->execute();
    $result = $stmt->get_result();
    $numrow = $result->num_rows;
    if($numrow>0) {
      $data = $result->fetch_assoc();

			if($data["enable"]!=1) {
				header('Location: login.php?msg=1');
				exit();
			}

			if($data["user_type"]<=0) {
				header('Location: login.php?msg=4');
				exit();
			}

			$sqlr = " select * from tt_admin_role where role_id = ? ";
			$stmtr = $mysqli->prepare($sqlr);
		  if($stmtr) {
		    $stmtr->bind_param('i',$data["user_type"]);
		    $stmtr->execute();
				$resultr = $stmtr->get_result();
		    $numrowr = $resultr->num_rows;
		    if($numrowr>0) {
		      $datar = $resultr->fetch_assoc();
					if($datar["role_status"]!=1) {
						header('Location: login.php?msg=4');
						exit();
					}

					$sqlup = "update tt_admin set last_login_date = now(), last_login_ip = ? where id = ? ";
					$stmtup = $mysqli->prepare($sqlup);
					if($stmtup) {
						$stmtup->bind_param('si',$ip,$data["id"]);
						$stmtup->execute();
					}

					saveLogActivity(1,$data["id"],0,"Sign In");

					$_SESSION["id"] = $data["id"];
					$_SESSION["name"] = $data["username"];
					$_SESSION["user_type"] = $data["user_type"];
					$_SESSION["admin_name"] = $data["fullname"];

					if($datar["role_lv"]==1) {
						header('Location: home.php');
						exit();
					}

					if($datar["role_lv"]==2) {
						header('Location: admin_role.php');
						exit();
					}

					if($datar["role_lv"]==3) {
						header('Location: fair_admin_role.php');
						exit();
					}


				} else {
					header('Location: login.php?msg=4');
					exit();
				}
		  } else {
				header('Location: login.php?msg=4');
				exit();
			}

    } else {
			header('Location: login.php?msg=2');
		}
  } else {
		header('Location: login.php?msg=2');
	}
	exit();
}
?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title><?php echo $site_name;?> | Login</title>

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
</head>

<body class="gray-bg bg_backoffice">

    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div class="boxlogin">
            <div>
							<img src="asset/logo.png" >

            </div>
						<br>
            <h3>Welcome to <?php echo $site_name;?></h3>
            <p>Login in</p>

            <form class="m-t fwhite" role="form" action="login.php" method="post">
            <input type="hidden" name="method" id="method" value="1">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Username" name="username" id="username" autocomplete="off" required>
                </div>
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Password" name="password" id="password" required>
                </div>
                <button type="submit" class="btn btn-success block full-width m-b">Login</button>
            </form>
            <!-- <p class="m-t"> <small><?php echo $site_name;?> &copy; <?php echo date("Y");?></small> </p> -->
        </div>
    </div>

    <!-- Mainly scripts -->
    <script src="js/jquery-2.1.1.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <!-- Toastr script -->
    <script src="js/plugins/toastr/toastr.min.js"></script>

    <script type="text/javascript">
	$(document).ready(function() {
	<?php
		if($_REQUEST["msg"]=="1"){
		?>
		swal({
			title: "ไม่สามารถเข้าสู่ระบบได้",
			text: "เนื่องจากถูกระงับการใช้งาน",
			type: "error",
			showCancelButton: false,
			confirmButtonColor: "#F27474",
			confirmButtonText: "close",
			closeOnConfirm: false
		});
		<?
		}else if($_REQUEST["msg"]=="2"){
		?>
		swal({
			title: "ไม่สามารถเข้าสู่ระบบได้",
			text: "เนื่องจากไม่พบผู้ใช้งานในระบบ",
			type: "error",
			showCancelButton: false,
			confirmButtonColor: "#F27474",
			confirmButtonText: "close",
			closeOnConfirm: false
		});
		<?
		}else if($_REQUEST["msg"]=="3"){
		?>
		swal({
			title: "ไม่สามารถเข้าสู่ระบบได้",
			text: "เนื่องจาก ผู้ใช้งานนี้ หมดอายุการใช้งาน",
			type: "error",
			showCancelButton: false,
			confirmButtonColor: "#F27474",
			confirmButtonText: "close",
			closeOnConfirm: false
		});
		<?
		}else if($_REQUEST["msg"]=="4"){
		?>
		swal({
			title: "ไม่สามารถเข้าสู่ระบบได้",
			text: "เนื่องจาก ไม่พบ Role ผู้ใช้งาน",
			type: "error",
			showCancelButton: false,
			confirmButtonColor: "#F27474",
			confirmButtonText: "close",
			closeOnConfirm: false
		});
		<?
		} else {
		}
	?>
	});
    </script>
</body>
</html>
