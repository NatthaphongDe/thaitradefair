<?php include("../connect.php"); ?>

<?

// $rmdirs =ROOTPATH."/data/cmsphoto";
// deleteDirectory($rmdirs);

if($_REQUEST["method"]=="delete") {
  $path = $_GET["p"];
  @unlink($path);
  exit();
}

if($_REQUEST["method"]=="add") {

  $pid = $_POST["pid"];
  $countfile = count(array_filter($_FILES['images_upload']['name']));
  if($countfile>0) {
    for($i=0;$i<$countfile;$i++ ) {

        $filedata = file_get_contents($_FILES["images_upload"]["tmp_name"][$i]);

        $path_parts = pathinfo($_FILES['images_upload']['name'][$i]);
        $extension = $path_parts['extension'];
        $path_namepic = $pid."-".alphanumeric_random_wms(10);
        $path_mini = ROOTPATH."/data/cmsphoto/$pid/$path_namepic.$extension";
        if (!is_dir(ROOTPATH."/data")){
          @mkdir(ROOTPATH."/data");
        }
        if (!is_dir(ROOTPATH."/data/cmsphoto")){
          @mkdir(ROOTPATH."/data/cmsphoto");
        }
        if (!is_dir(ROOTPATH."/data/cmsphoto/".$pid)){
          @mkdir(ROOTPATH."/data/cmsphoto/".$pid);
        }

        $wid = 1600;
        imageresize($filedata,$path_mini,$wid,0);

    }
  }

  exit();
}
?>

<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?=$site_name?> | Home</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.form/4.3.0/jquery.form.min.js"  crossorigin="anonymous"></script>
    <link href="<?=ROOTPATHDOMAIN?>backoffice/js/sweetalert/sweetalert.css" rel="stylesheet">
    <script type="text/javascript" src="<?=ROOTPATHDOMAIN?>backoffice/js/sweetalert/sweetalert.min.js"></script>

    <style media="screen">
    .file_uploading {
        margin-top: 15px;
    }
    .hidden {
        display: none;
    }
    .gallery {
      width:100%;
      float:left;
    }
    .gallery ul{
      margin:0;
      padding:0;
      list-style-type:none;
    }
    .gallery ul li{
      padding:7px;
      border:2px solid #ccc;
      float:left;
      margin:10px 7px;
      background:none;
      width:auto;
      height:auto;
    }
    .images {
      width:200px;
      height:200px;
    }

    .progress { position:relative; width:100%; border: 1px solid #ddd; padding: 1px; border-radius: 3px; height: 28px;}
    .bar { background-color: #1C5FA1; color: #fff; width:0%; border-radius: 3px; height: 28px; }
    .percent { position:absolute; display:inline-block; top:3px; width: 100%; text-align: center; color: #fff; }
    </style>

</head>

<body style="background:none !important;">

  <div class="container" style="padding:0 !important;">
  	<form method="post" name="image_upload_form" id="image_upload_form" enctype="multipart/form-data" action="cms_photo.php?method=add">
      <input type="file" name="images_upload[]" id="image_upload" multiple >
      <input type="hidden" name="pid" value="<?=$_REQUEST["pid"]?>">
  	</form>
  	<br>
  	<div class="progress" style="display:none;">
  		<div class="bar"></div >
  		<div class="percent">0%</div >
  	</div>

  </div>

  <div class="container" style="padding:0 !important;">
    <div class="row">
      <?
      $run = 0;
      foreach(glob(ROOTPATH."/data/cmsphoto/".$_REQUEST["pid"]."/*.*") as $inputFileName) {
          $imgfile = "";
          $imgfile = str_replace(ROOTPATH,ROOTPATHDOMAIN,$inputFileName);
        ?>
      <div class="col-xs-6 _filebolg_<?=$run?>" style="margin-top:10px; position:relative;">
        <img src="<?=$imgfile?>" width="100%">
        <div style="position: absolute; z-index:1; left:15px; top:3px; cursor:pointer;">
          <img src="<?=ROOTPATHDOMAIN?>backoffice/asset/icon_x.png" onclick="removeFile('<?=$inputFileName?>','<?=$run?>');" >
        </div>
      </div>
    <? $run++; } ?>
    </div>

  </div>



<script type="text/javascript">
$(document).ready(function(){
	var bar = $('.bar');
	var percent = $('.percent');
	var status = $('#status');
	$('#image_upload').on('change',function(){
		 $('#image_upload_form').ajaxForm({
			beforeSend: function() {
				$(".progress").show();
				var percentVal = '0%';
				bar.width(percentVal);
				percent.html(percentVal);
			},
			uploadProgress: function(event, position, total, percentComplete) {
				var percentVal = percentComplete + '%';
				bar.width(percentVal);
				percent.html(percentVal);
			},
			success: function(data, statusText, xhr) {
				var percentVal = '100%';
				bar.width(percentVal);
				percent.html(percentVal);
        setTimeout(function () {window.location="cms_photo.php?pid=<?=$_REQUEST["pid"]?>";},1000);
			},
			error: function(xhr, statusText, err) {

			}
		 }).submit();
	});
});


function removeFile(path,id) {
  swal({
      title: "ยืนยันการลบรายการ",
      text: "ยืนยันการลบรายการ",
     type: "error",
      showCancelButton: true,
      confirmButtonColor: "#F27474",
      confirmButtonText: "ตกลง",
  cancelButtonText: "ยกเลิก",
      closeOnConfirm: true
    }, function (isConfirm) {
      if (isConfirm) {
          removeFileAction(path,id);
      }
    });
}

function removeFileAction(path,id) {
  $.ajax({
      type: "GET",
      url: "cms_photo.php?method=delete&p="+path,
      dataType: "text",
      success : function(data) {
        $('._filebolg_'+id).remove();
      }
  });
}

</script>

</body>
</html>
