<?php
$header_name = "Admin Activity Report";
$menu_name = "Admin Activity Report";
$back_link = "admin_report";
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
      <a href="php/export_admin_report.php" target="_blank" class="btn btn-success"><i class="fa fa-print" aria-hidden="true"></i> Export</a>
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
                    <th class="text-center tr-head">Username</th>
                    <th class="text-center tr-head">Role</th>
                    <th class="text-center tr-head">Type</th>
                    <th class="text-center tr-head">Fair Group</th>
                    <th class="text-center tr-head">Fair Name</th>
                    <th class="text-center tr-head">Caterory Name</th>
                    <th class="text-center tr-head">Title Name</th>
                    <th class="text-center tr-head">Action</th>
                    <th class="text-center tr-head">Date</th>
                    <th class="text-center tr-head">IP Address</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?
                    $sql = "select * from tt_activity_log a left join tt_admin b on a.admin_id=b.id left join tt_admin_role c on b.user_type=c.role_id where log_action != 'Delete' order by a.admin_date DESC limit 3000 ";
                    $stmt = $mysqli->prepare($sql);
                    if($stmt) {
                      $stmt->execute();
                      $result = $stmt->get_result();
                      $numrow = $result->num_rows;
                      if($numrow>0) {
                        $runno = 1;
                        while($data = $result->fetch_assoc()) {
                          $rolelv = "";
                          if($data["role_lv"]==1) {
                            $rolelv = "Super Admin";
                          }
                          if($data["role_lv"]==2) {
                            $rolelv = "Admin";
                          }
                          if($data["role_lv"]==3) {
                            $rolelv = "Fair Admin";
                          }

                          $fairgroup = "";
                          $fairname = "";
                          $catname = "";
                          $titlename = "";
                          $type = "";
                          if($data["log_type"]==1) {
                            $type = "Access";
                          }
                          if($data["log_type"]==2) {
                            $type = "Fair Master";

                            $sqllog = "select * from tt_fair_group where fair_group_id = ? ";
                            $stmtlog = $mysqli->prepare($sqllog);
                            $stmtlog->bind_param('i',$data["ref_id"]);
                            $stmtlog->execute();
                            $resultlog = $stmtlog->get_result();
                            $numrowlog = $resultlog->num_rows;
                            if($numrowlog>0) {
                              $datalog = $resultlog->fetch_assoc();
                              $fairgroup = $datalog["fair_group_abb"];
                            }


                          }
                          if($data["log_type"]==3) {
                            $type = "Fair List";

                            $sqllog = "select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_id = ? and b.fair_flag != '9' limit 1 ";
                            $stmtlog = $mysqli->prepare($sqllog);
                            $stmtlog->bind_param('i',$data["ref_id"]);
                            $stmtlog->execute();
                            $resultlog = $stmtlog->get_result();
                            $numrowlog = $resultlog->num_rows;
                            if($numrowlog>0) {
                              $datalog = $resultlog->fetch_assoc();
                              $fairgroup = $datalog["fair_group_abb"];
                              $fairname = $datalog["fair_name"];
                            }

                          }
                          if($data["log_type"]==4) {
                            $type = "Content CMS";

                            $sqllog = "select * from tt_fair_content_onepage z left join tt_fair_list a on z.fair_id=a.fair_id left join tt_fair_group_list b on a.fair_id=b.fair_id left join tt_fair_group c on b.fair_group_id=c.fair_group_id left join tt_fair_category d on z.fcat_id=d.fcat_id where z.fc_id = ? and b.fair_flag != '9' limit 1 ";
                            $stmtlog = $mysqli->prepare($sqllog);
                            $stmtlog->bind_param('i',$data["ref_id"]);
                            $stmtlog->execute();
                            $resultlog = $stmtlog->get_result();
                            $numrowlog = $resultlog->num_rows;
                            if($numrowlog>0) {
                              $datalog = $resultlog->fetch_assoc();
                              $fairgroup = $datalog["fair_group_abb"];
                              $fairname = $datalog["fair_name"];
                              $catname = $datalog["fcat_name"];
                            }

                          }
                          if($data["log_type"]==5) {
                            $type = "Content Article";

                            $sqllog = "select * from tt_fair_content_article z left join tt_fair_list a on z.fair_id=a.fair_id left join tt_fair_group_list b on a.fair_id=b.fair_id left join tt_fair_group c on b.fair_group_id=c.fair_group_id left join tt_fair_category d on z.fcat_id=d.fcat_id where z.fca_id = ? and b.fair_flag != '9' limit 1 ";
                            $stmtlog = $mysqli->prepare($sqllog);
                            $stmtlog->bind_param('i',$data["ref_id"]);
                            $stmtlog->execute();
                            $resultlog = $stmtlog->get_result();
                            $numrowlog = $resultlog->num_rows;
                            if($numrowlog>0) {
                              $datalog = $resultlog->fetch_assoc();
                              $fairgroup = $datalog["fair_group_abb"];
                              $fairname = $datalog["fair_name"];
                              $catname = $datalog["fcat_name"];
                              $titlename = $datalog["fca_title_en"];
                            }

                          }
                          if($data["log_type"]==6) {
                            $type = "Gallery";

                            $sqllog = "select * from tt_fair_content_gallery z left join tt_fair_list a on z.fair_id=a.fair_id left join tt_fair_group_list b on a.fair_id=b.fair_id left join tt_fair_group c on b.fair_group_id=c.fair_group_id left join tt_fair_category d on z.fcat_id=d.fcat_id where z.fcg_id = ? and b.fair_flag != '9' limit 1 ";
                            $stmtlog = $mysqli->prepare($sqllog);
                            $stmtlog->bind_param('i',$data["ref_id"]);
                            $stmtlog->execute();
                            $resultlog = $stmtlog->get_result();
                            $numrowlog = $resultlog->num_rows;
                            if($numrowlog>0) {
                              $datalog = $resultlog->fetch_assoc();
                              $fairgroup = $datalog["fair_group_abb"];
                              $fairname = $datalog["fair_name"];
                              $catname = $datalog["fcat_name"];
                              $titlename = $datalog["fcg_title_en"];
                            }
                          }
                          ?>
                          <tr>
                          	<td class="text-center vmiddle"><?=$runno?></td>
                            <td class="text-center vmiddle"><?=$data["username"]?></td>
                            <td class="text-center vmiddle"><?=$rolelv?></td>
                            <td class="text-center vmiddle"><?=$type?></td>
                            <td class="text-center vmiddle"><?=$fairgroup?></td>
                            <td class="text-center vmiddle"><?=$fairname?></td>
                            <td class="text-center vmiddle"><?=$catname?></td>
                            <td class="text-center vmiddle"><?=$titlename?></td>
                            <td class="text-center vmiddle" style="color:#1C5FA1;"><?=$data["log_action"]?></td>
                            <td class="text-center vmiddle"><?=$data["admin_date"]?></td>
                            <td class="text-center vmiddle"><?=$data["admin_ip"]?></td>
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
