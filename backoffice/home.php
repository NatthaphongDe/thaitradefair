<?php include("connect.php"); ?>
<?php checklogin();?>
<!DOCTYPE html>
<html>

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title><?=$site_name?> | Home</title>

    <link href="css/bootstrap.min.css?v=1001" rel="stylesheet">
    <link href="css/glyphicons.css" rel="stylesheet">
	  <link href="css/bootstrap-datepicker.min.css" rel="stylesheet">
    <link href="js/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css?v=1001" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="js/DataTables/Bootstrap-3.3.7/css/bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="js/DataTables/DataTables-1.10.13/css/dataTables.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="js/DataTables/AutoFill-2.1.3/css/autoFill.bootstrap.css"/>
    <link rel="stylesheet" type="text/css" href="js/DataTables/ColReorder-1.3.2/css/colReorder.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="js/DataTables/FixedHeader-3.1.2/css/fixedHeader.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="js/DataTables/KeyTable-2.2.0/css/keyTable.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="js/DataTables/Responsive-2.1.1/css/responsive.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="js/DataTables/RowReorder-1.2.0/css/rowReorder.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="js/DataTables/Scroller-1.4.2/css/scroller.bootstrap.min.css"/>
    <link rel="stylesheet" type="text/css" href="js/DataTables/Select-1.2.0/css/select.bootstrap.min.css"/>

    <!-- Toastr style -->
    <link href="css/plugins/toastr/toastr.min.css?v=1001" rel="stylesheet">

    <link href="css/plugins/footable/footable.core.css?v=1001" rel="stylesheet">
    <link href="css/plugins/awesome-bootstrap-checkbox/awesome-bootstrap-checkbox.css" rel="stylesheet">
    <link href="css/animate.css?v=1001" rel="stylesheet">
    <link href="css/style.css?v=1002" rel="stylesheet">

    <link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">



    <link href="css/wms_home.css?v=1001" rel="stylesheet">

	  <script type="text/javascript" src="js/DataTables/jQuery-2.2.4/jquery-2.2.4.min.js"></script>
    <script type="text/javascript" src="js/DataTables/Bootstrap-3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="js/DataTables/DataTables-1.10.13/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="js/DataTables/DataTables-1.10.13/js/dataTables.bootstrap.min.js"></script>
    <script type="text/javascript" src="js/DataTables/AutoFill-2.1.3/js/dataTables.autoFill.min.js"></script>
    <script type="text/javascript" src="js/DataTables/AutoFill-2.1.3/js/autoFill.bootstrap.min.js"></script>
    <script type="text/javascript" src="js/DataTables/ColReorder-1.3.2/js/dataTables.colReorder.min.js"></script>
    <script type="text/javascript" src="js/DataTables/FixedHeader-3.1.2/js/dataTables.fixedHeader.min.js"></script>
    <script type="text/javascript" src="js/DataTables/KeyTable-2.2.0/js/dataTables.keyTable.min.js"></script>
    <script type="text/javascript" src="js/DataTables/Responsive-2.1.1/js/dataTables.responsive.min.js"></script>
    <script type="text/javascript" src="js/DataTables/Responsive-2.1.1/js/responsive.bootstrap.min.js"></script>
    <script type="text/javascript" src="js/DataTables/RowReorder-1.2.0/js/dataTables.rowReorder.min.js"></script>
    <script type="text/javascript" src="js/DataTables/Scroller-1.4.2/js/dataTables.scroller.min.js"></script>
    <script type="text/javascript" src="js/DataTables/Select-1.2.0/js/dataTables.select.min.js"></script>
    <script src="js/bootstrap-datepicker.min.js"></script>
     <!-- Toastr -->
    <script src="js/plugins/toastr/toastr.min.js?v=1001"></script>

    <link href="js/sweetalert/sweetalert.css" rel="stylesheet">
    <script type="text/javascript" src="js/sweetalert/sweetalert.min.js"></script>
    <script src="https://cdn.tiny.cloud/1/xpj7ka71q6du6pkys35dukk4iuzpppk69wfq1qn0rs51hhpf/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>

    <script type="text/javascript">
    toastr.options = {
	  "closeButton": true
	}
	function alert_error(type_alert, txt, type_function){
		if(type_alert==1){
			toastr.error(txt);
		}else if(type_alert==2){
			toastr.warning(txt);
		}else if(type_alert==3){
			toastr.success(txt);
		}
	}

  function alertpopup(type,message) {
    if(type==1) {
      swal({
        title: "Success",
        text: message,
        type: "success",
        showCancelButton: false,
        confirmButtonColor: "#5cb85c",
        confirmButtonText: "close",
        closeOnConfirm: false
      });
    }

    if(type==2) {
      swal({
        title: "Error",
        text: message,
        type: "error",
        showCancelButton: false,
        confirmButtonColor: "#F27474",
        confirmButtonText: "close",
        closeOnConfirm: false
      });
    }
  }
    </script>



</head>

<body class="fixed-nav fixed-nav-basic" >
  <div class="loadingoverlay"></div>

    <div id="wrapper">
        <?php include "menu.php";?>
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
            <?php include "topmenu.php";?>
            </div>

            <?
                $page=$_GET["show"];
                // echo $page;
                include("php/".$page.".php");
            ?>

            <div class="footer">
                <div class="pull-right">
                    <!--10GB of <strong>250GB</strong> Free.-->
                </div>
                <div>
                    <!-- <strong>Copyright</strong> © 2022-<?php echo date("Y");?> -->
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade text-left w-100 bg-modal" id="full-scrn_edit_tag" tabindex="-1" aria-labelledby="myModalLabel20" aria-hidden="true">
      <div  class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full _modeltag" role="document"></div>

    </div>
    <iframe id="com_m" name="com_m" width="0" height="0" frameborder="0" scrolling="no" style="position:absolute; left:-10000%;"></iframe>

    <script src="js/plugins/metisMenu/jquery.metisMenu.js?v=1001"></script>
    <script src="js/plugins/slimscroll/jquery.slimscroll.min.js?v=1001"></script>

    <!-- Flot -->
    <script src="js/plugins/flot/jquery.flot.js?v=1001"></script>
    <script src="js/plugins/flot/jquery.flot.tooltip.min.js?v=1001"></script>
    <script src="js/plugins/flot/jquery.flot.spline.js?v=1001"></script>
    <script src="js/plugins/flot/jquery.flot.resize.js?v=1001"></script>
    <script src="js/plugins/flot/jquery.flot.pie.js?v=1001"></script>

    <!-- Peity -->
    <script src="js/plugins/peity/jquery.peity.min.js?v=1001"></script>
    <script src="js/demo/peity-demo.js?v=1001"></script>

    <!-- Custom and plugin javascript -->
    <script src="js/inspinia.js?v=1001"></script>
    <script src="js/plugins/pace/pace.min.js?v=1001"></script>

    <!-- GITTER -->
    <script src="js/plugins/gritter/jquery.gritter.min.js?v=1001"></script>

    <!-- Sparkline -->
    <script src="js/plugins/sparkline/jquery.sparkline.min.js?v=1001"></script>
    <!-- Data picker -->
   <script src="js/plugins/datapicker/bootstrap-datepicker.js"></script>
   <script src="js/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js" ></script>

   <!-- Select2 -->
    <script src="js/plugins/select2/select2.full.min.js"></script>

    <!-- Resize img -->
    <script type="text/javascript" src="js/plugins/canvasResize/jquery.exif.js?v=1001"></script>
	<script type="text/javascript" src="js/plugins/canvasResize/jquery.canvasResize.js?v=1001"></script>
    <script type="text/javascript" src="js/plugins/canvasResize/canvasResize.js?v=1001"></script>
    <!-- browse file -->
    <script type="text/javascript" src="js/plugins/filestyle/bootstrap-filestyle.min.js"> </script>


    <!-- FooTable -->
    <script src="js/plugins/footable/footable.all.min.js"></script>


    <script type="text/javascript">
    var setCookie = function(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}else var expires = "";
		document.cookie = name+"="+value+expires+"; path=/";
	};
	var getCookie = function(name) {
		var nameEQ = name + "=";
		var ca = document.cookie.split(';');
		for(var i=0;i < ca.length;i++) {
			var c = ca[i];
			while (c.charAt(0)==' ') c = c.substring(1,c.length);
			if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
		}
		return null;
	};
	var deleteCookie = function(name) {
		setCookie(name,"",-1);
	};
	$(document).ready(function(){
		var system2_user = getCookie('menu');
		if(system2_user == "open"){
			TriggerClick = 1;
			$("body").removeClass('mini-navbar');
			setCookie('menu',"open");
		}else{
			TriggerClick = 0;
			$("body").addClass('mini-navbar');
			deleteCookie('menu');
		}
		$(".navbar-minimalize").click(function(){
			if(TriggerClick==0){
				setCookie('menu',"open");
				TriggerClick=1;

			}else{
				//pace-done
				deleteCookie('menu');
				TriggerClick=0;

			};
		});
		//canvas img
		var ar_ext = ['jpg','png','jpeg'];
		function checkName(el) {
			// - www.coursesweb.net
			// get the file name and split it to separe the extension
			//alert(sbm);
			var name = el.value;
			var ar_name = name.split('.');

			// for IE - separe dir paths (\) from name
			var ar_nm = ar_name[0].split('\\');
			for(var i=0; i<ar_nm.length; i++) var nm = ar_nm[i];

			// add the name in 'to'
			//document.getElementById(to).value = nm;

			// check the file extension
			var re = 0;
			for(var i=0; i<ar_ext.length; i++) {
				if(ar_ext[i] == ar_name[1].toLowerCase()) {
				  re = 1;
				  break;
				}
			}

			// if re is 1, the extension is in the allowed list
			if(re==1) {
			// enable submit
			//document.getElementById(sbm).disabled = false;
			}else {
			// delete the file name, disable Submit, Alert message
				el.value = "";
				//document.getElementById(sbm).disabled = true;
				$(".page_check_in_cover_userup").hide();
				alert('".'+ ar_name[1]+ '" is not an file type allowed for upload ');
			}
		}
		$(".img_click").click(function() {
			$("#file_upload").click();
		});
		$('input[name=img_pic]').change(function(e) {
			checkName(this);
			var file = e.target.files[0];

			// CANVAS RESIZING
			$.canvasResize(file, {
				width: 1024,
				height: 0,
				crop: false,
				quality: 80,
				//rotate: 90,
				callback: function(data, width, height) {
					$(".page_check_in_cover_userup,#image_upload").show();
					$('#image_upload').attr('src', data);
					$('#img_base').text(data);
				}
			});
			$.canvasResize(file, {
				width: 400,
				height: 0,
				crop: false,
				quality: 80,
				//rotate: 90,
				callback: function(data, width, height) {
					$('#img_base_400').text(data);
				}
			});
		});

    $('input[name=img_pic_icon]').change(function(e) {
      checkName(this);
      var file = e.target.files[0];

      // CANVAS RESIZING
      $.canvasResize(file, {
        width: 100,
        height: 100,
        crop: false,
        quality: 80,
        //rotate: 90,
        callback: function(data, width, height) {
          $(".page_check_in_cover_userup,#image_upload").show();
          $('#image_upload').attr('src', data);
          $('#img_base').text(data);
        }
      });
    });
		$(":file").filestyle({placeholder: "No file"});

		$('.footable').footable();
        $('.footable2').footable();

		$('.datepicker_box').datepicker({
			format: 'yyyy-mm-dd'
		});

    $('.datepicker_box_d').datepicker({
			format: 'dd-mm-yyyy'
		});

		$(".timepicker_box").datetimepicker({
			formatViewType: 'time',
			format: 'hh:ii',
			startView: 1,
			pickDate: false,
			autoclose: true	,
			minuteStep: 30
		}).on("show", function(){
			$(".table-condensed .prev").css('visibility', 'hidden');
			$(".table-condensed .switch").text("Pick Time");
			$(".table-condensed .next").css('visibility', 'hidden');
		});

		$(".timepicker_box_2").datetimepicker({
			formatViewType: 'time',
			format: 'hh:ii',
			startView: 1,
			pickDate: false,
			autoclose: true	,
			minuteStep: 1
		}).on("show", function(){
			$(".table-condensed .prev").css('visibility', 'hidden');
			$(".table-condensed .switch").text("Pick Time");
			$(".table-condensed .next").css('visibility', 'hidden');
		});

		$(".timepicker_box_3").datetimepicker({
			formatViewType: 'time',
			format: 'hh:ii',
			startView: 1,
			pickDate: false,
			autoclose: true	,
			minuteStep: 15
		}).on("show", function(){
			$(".table-condensed .prev").css('visibility', 'hidden');
			$(".table-condensed .switch").text("Pick Time");
			$(".table-condensed .next").css('visibility', 'hidden');
		});

    calltinymce();

	});

	function checkB() {
		if(/chrom(e|ium)/.test(navigator.userAgent.toLowerCase())){

		} else {
			top.window.location='blank.php';
			return;
		}
	}

  function pc_overlay(type) {
  	if(type==1) {
  		$('.loadingoverlay').show();
  	} else {
  		$('.loadingoverlay').hide();
  	}
  }


  function calltinymce() {
    tinymce.init({
    selector: 'textarea.tinyclass',
    plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media template codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
    menubar: 'file edit view insert format tools table help',
    toolbar: 'undo redo | bold italic underline strikethrough | fontfamily fontsize blocks | alignleft aligncenter alignright alignjustify | outdent indent |  numlist bullist | forecolor backcolor removeformat | pagebreak | charmap emoticons | fullscreen  preview save print | insertfile image media template link anchor codesample | ltr rtl',
    toolbar_sticky: true,
    height: 520,
    quickbars_selection_toolbar: 'bold italic | quicklink h2 h3 blockquote quickimage quicktable',
    noneditable_noneditable_class: "mceNonEditable",
    toolbar_mode: 'sliding'
   });
  }

  /////// TAG MANAGE
  function changeTagFair(id) {
    $('.tagf').removeClass('btn-tag-active');
    $('#tagf_'+id).addClass('btn-tag-active');
    $('#tag_fair_id').val(id);
  }

  function changeTagMaster(id) {
    var newitem = "";
    var val = $('#tagm_'+id).data("val");
    if(val==0) {
      $('#tagm_'+id).data('val',1);
      $('#tagm_'+id).addClass('btn-tag-active');
    } else {
      $('#tagm_'+id).data('val',0);
      $('#tagm_'+id).removeClass('btn-tag-active');
    }

    $('.tagm_').each(function() {
      var valitem = $(this).data("val");
      var valitemid = $(this).data("id");
      if(valitem==1) {
        newitem = newitem+'|'+valitemid;
      }
    });

    $('#tag_master_id').val(newitem);

  }


  function addTag() {
    var dataid = $('#tag_master_id').val();
    $('#full-scrn_edit_tag').modal('show');
    $('._modeltag').empty();
    $.ajax({
        type: "GET",
        url: "php/ajax-addtag.php?dataid="+dataid,
        dataType: "text",
        success : function(data) {
          $('._modeltag').html(data);
        }
    });

  }

  function closeTag() {
    $('._modeltag').empty();
    $('#full-scrn_edit_tag').modal('hide');
  }

  function loadTag(dataid) {
    $('._showtagmaster').empty();
    $.ajax({
        type: "GET",
        url: "php/ajax-addtag.php?method=loadtag&dataid="+dataid,
        dataType: "text",
        success : function(data) {
          $('._showtagmaster').html(data);
          setTagItem();
        }
    });
  }

  function setTagItem() {
    var newitem = "";
    $('.tagm_').each(function() {
      var valitem = $(this).data("val");
      var valitemid = $(this).data("id");
      if(valitem==1) {
        newitem = newitem+','+valitemid;
        $(this).addClass('btn-tag-active');
      }
    });

    $('#tag_master_id').val(newitem);
  }

  function addmoreurl() {
    $.ajax({
        type: "GET",
        url: "php/ajax-addmore.php?method=addurl",
        dataType: "text",
        success : function(data) {
          $('._addurl').append(data);
        }
    });
  }

  function addmoreyoutube() {
    $.ajax({
        type: "GET",
        url: "php/ajax-addmore.php?method=addyoutube",
        dataType: "text",
        success : function(data) {
          $('._addyt').append(data);
        }
    });
  }

  function removeFileAtt(id) {
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
            removeFileAttAction(id);
        }
      });
  }

  function removeFileAttAction(id) {
    $.ajax({
        type: "GET",
        url: "php/ajax-removefile_att.php?method=delete&id="+id,
        dataType: "text",
        success : function(data) {
          $('._f_'+id).remove();
        }
    });
  }

    </script>
</body>
</html>
