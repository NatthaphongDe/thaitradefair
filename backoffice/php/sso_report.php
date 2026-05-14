<?php
$header_name = "User SSO Report";
$menu_name = "User SSO Report";
$back_link = "sso_report";
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
      <a href="php/export_sso_report.php" target="_blank" class="btn btn-success"><i class="fa fa-print" aria-hidden="true"></i> Export</a>
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
                    <th class="text-center tr-head">SSO ID</th>
                    <th class="text-center tr-head">Name</th>
                    <th class="text-center tr-head">Company</th>
                    <th class="text-center tr-head">Action</th>
                    <th class="text-center tr-head">Date</th>
                    <th class="text-center tr-head">IP Address</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?
                    $sql = "select * from tt_activity_sso a left join tt_sso_login b on a.sso_id=b.sso_id order by a.sso_date DESC limit 3000 ";
                    $stmt = $mysqli->prepare($sql);
                    if($stmt) {
                      $stmt->execute();
                      $result = $stmt->get_result();
                      $numrow = $result->num_rows;
                      if($numrow>0) {
                        $runno = 1;
                        while($data = $result->fetch_assoc()) {
                          $name = "";
                          $company = "";
                          if($data["sso_name_en"]!="") {
                            $name = $data["sso_name_en"];
                          } else {
                            $name = $data["sso_name_th"];
                          }

                          if($data["sso_company_en"]!="") {
                            $company = $data["sso_company_en"];
                          } else {
                            $company = $data["sso_company_th"];
                          }

                          ?>
                          <tr>
                          	<td class="text-center vmiddle"><?=$runno?></td>
                            <td class="text-center vmiddle"><?=$data["sso_id"]?></td>
                            <td class="text-center vmiddle"><?=$name?></td>
                            <td class="text-center vmiddle"><?=$company?></td>
                            <td class="text-center vmiddle" style="color:#1C5FA1;"><?=$data["log_action"]?></td>
                            <td class="text-center vmiddle"><?=$data["sso_date"]?></td>
                            <td class="text-center vmiddle"><?=$data["sso_ip"]?></td>
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
