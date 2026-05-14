<?php
$header_name = "Online Meeting Log";
$menu_name = "Online Meeting Log";
$back_link = "meeting_log";
?>

<div class="row wrapper page-heading">
     <div class="col-xs-4 col-lg-6">
       <br>
        <ol class="breadcrumb">
            <li>
                <a><h2><?=$header_name?></h2></a>
            </li>
        </ol>
    </div>

    <div class="col-xs-8 col-lg-6 text-right"><h3>&nbsp;</h3>
      <a href="php/export_meeting_report.php" target="_blank" class="btn btn-success"><i class="fa fa-print" aria-hidden="true"></i> Export</a>
    </div>

</div>
<link href="js/jquery.dataTables.min.css" rel="stylesheet">
<script  src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {

    $('#deferRenderTable').DataTable( {
        "deferRender": true
    } );

} );
</script>
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-lg-12">
        	<div class="ibox float-e-margins">
          	<div class="table-responsive">
               <table class="table table-stripped table-hover table-bordered" id="deferRenderTable">
                  <thead>
                    <tr>
                    	<th class="text-center tr-head">#</th>
                      <th class="text-center tr-head">Sender Email</th>
                      <th class="text-center tr-head">Sender Name</th>
                      <th class="text-center tr-head">Receiver Email</th>
                      <th class="text-center tr-head">Receiver Name</th>
                      <th class="text-center tr-head">Request Date/Time</th>
                      <th class="text-center tr-head">Request Status</th>
                      <th class="text-center tr-head">Receiver Select</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?
                    $sql = "select * from tt_meeting a left join tt_sso_login b on a.meeting_ssoid=b.sso_id order by a.meeting_create DESC limit 3000 ";
                    $stmt = $mysqli->prepare($sql);
                    if($stmt) {
                      $stmt->execute();
                      $result = $stmt->get_result();
                      $numrow = $result->num_rows;
                      if($numrow>0) {
                        $runno = 1;
                        while($data = $result->fetch_assoc()) {
                          $SenderEmail = $data["sso_email"];
                          $SenderName = "";
                          if($data["sso_name_en"]!="") {
                            $SenderName = $data["sso_name_en"];
                          } else {
                            $SenderName = $data["sso_name_th"];
                          }

                          $ReceiveEmail = "";
                          $ReceiveName = "";

                          if($data["meeting_exl_id"]>0) {
                            $sqllog = "select * from tt_exhibitor_list where exl_id = ? ";
                            $stmtlog = $mysqli->prepare($sqllog);
                            $stmtlog->bind_param('i',$data["meeting_exl_id"]);
                            $stmtlog->execute();
                            $resultlog = $stmtlog->get_result();
                            $numrowlog = $resultlog->num_rows;
                            if($numrowlog>0) {
                              $datalog = $resultlog->fetch_assoc();
                              $ReceiveEmail = $datalog["com_email"];
                              $ReceiveName = $datalog["com_name"];
                            }
                          }

                          if($data["meeting_exp_id"]>0) {
                            $sqllog = "select * from tt_exportor_list where exp_id = ? ";
                            $stmtlog = $mysqli->prepare($sqllog);
                            $stmtlog->bind_param('i',$data["meeting_exp_id"]);
                            $stmtlog->execute();
                            $resultlog = $stmtlog->get_result();
                            $numrowlog = $resultlog->num_rows;
                            if($numrowlog>0) {
                              $datalog = $resultlog->fetch_assoc();
                              $ReceiveEmail = $datalog["Mail"];
                              $ReceiveName = $datalog["Corporate_Name_EN"];
                            }
                          }

                          $status = "";
                          if($data["meeting_status"]==0) {
                            $status = '<span style="color:#FCBC3E">Pending</span>';
                          }
                          if($data["meeting_status"]==1) {
                            $status = '<span style="color:#259382">Confirm</span>';
                          }
                          if($data["meeting_status"]==2) {
                            $status = '<span style="color:#DB5353">Reject</span>';
                          }

                          $dateslot = "";
                          $confirmslot = "";
                          $sqllog = "select * from  tt_meeting_list where meeting_list_mid = ? order by meeting_list_datetime ASC ";
                          $stmtlog = $mysqli->prepare($sqllog);
                          $stmtlog->bind_param('i',$data["meeting_id"]);
                          $stmtlog->execute();
                          $resultlog = $stmtlog->get_result();
                          $numrowlog = $resultlog->num_rows;
                          if($numrowlog>0) {
                            while($datalog = $resultlog->fetch_assoc()) {
                              if($dateslot=="") {
                                $dateslot = date("Y-m-d H:i A",strtotime($datalog["meeting_list_datetime"]));
                              } else {
                                $dateslot = $dateslot."<br>".date("Y-m-d H:i A",strtotime($datalog["meeting_list_datetime"]));
                              }

                              if($datalog["meeting_list_status"]==1) {
                                $confirmslot = date("Y-m-d H:i A",strtotime($datalog["meeting_list_datetime"]));
                              }

                            }
                          }


                          ?>
                          <tr>
                          	<td class="text-center vmiddle"><?=$runno?></td>
                            <td class="text-center vmiddle"><?=$SenderEmail?></td>
                            <td class="text-center vmiddle"><?=$SenderName?></td>
                            <td class="text-center vmiddle"><?=$ReceiveEmail?></td>
                            <td class="text-center vmiddle"><?=$ReceiveName?></td>
                            <td class="text-center vmiddle"><?=$dateslot?></td>
                            <td class="text-center vmiddle"><?=$status?></td>
                            <td class="text-center vmiddle"><?=$confirmslot?></td>
                          </tr>
                          <?
                          $runno++;
                        }
                      }
                    }
                    ?>
                  </tbody>
              </table>
              </div>
          </div>
        </div>
	</div>
</div>
