<?php
$header_name = "Contact Information";
$menu_name = "Fair Contact Form";
$back_link = "fair_contact_list";
?>

<div class="row wrapper page-heading">
      <div class="col-xs-4 col-lg-6">
        <br>
         <ol class="breadcrumb">
             <li>
                 <a><h2><?=$header_name?></h2></a>
             </li>
             <li class="active">
                 <strong class="menu_n_menu"><?=$menu_name?></strong>
             </li>
         </ol>
     </div>

    <div class="col-xs-8 col-lg-6 text-right"><h3>&nbsp;</h3>
      <a class="btn btn-success">Download</a>
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
                    <th class="text-center tr-head">Date</th>
                    <th class="text-left tr-head">Title</th>
                    <th class="text-center tr-head">Sender Name</th>
                    <th class="text-center tr-head">Email</th>
                    <th class="text-center tr-head">Telephone</th>
                    <th class="text-left tr-head">Fair Name</th>
                    <th class="text-center tr-head">Status</th>
                  </tr>
                  </thead>
                  <tbody>
                    <?
                    if((int)$_SESSION["fgid"]>0) {
                      $sql = "select * from  tt_contact_list a left join tt_fair_list b on a.fair_id=b.fair_id where a.fair_id in (select fair_id from  tt_fair_group_list where fair_group_id = '".(int)$_SESSION["fgid"]."' and fair_flag != 9 ) and a.cont_status != 99 order by a.cont_create_date DESC ";
                    } else {
                      $sql = "select * from  tt_contact_list a left join tt_fair_list b on a.fair_id=b.fair_id where a.fair_id > 0 and a.cont_status != 99 order by a.cont_create_date DESC ";
                    }

                    $stmt = $mysqli->prepare($sql);
                    if($stmt) {
                      $stmt->execute();
                      $result = $stmt->get_result();
                      $numrow = $result->num_rows;
                      if($numrow>0) {
                        $runno = 1;
                        while($data = $result->fetch_assoc()) {
                      ?>
                      <tr>
                        <td class="text-center vmiddle"><?=$runno?></td>
                        <td class="text-center vmiddle"><?=$data["cont_create_date"]?></td>
                        <td class="text-left vmiddle"><?=$data["cont_subject"]?></td>
                        <td class="text-center vmiddle"><?=$data["cont_name"]?></td>
                        <td class="text-center vmiddle"><?=$data["cont_email"]?></td>
                        <td class="text-center vmiddle"><?=$data["cont_tel"]?></td>
                        <td class="text-left vmiddle"><?=$data["fair_name"]?></td>
                        <td class="text-center vmiddle">
                          <? if($data["cont_status"]==0) { ?>
                            <a href="home.php?show=fair_contact_edit&cont_id=<?=$data["cont_id"]?>"><img src="../backoffice/asset/icon-mail-new.png" ></a>
                          <? } else if($data["cont_status"]==1) { ?>
                            <a href="home.php?show=fair_contact_edit&cont_id=<?=$data["cont_id"]?>"><img src="../backoffice/asset/icon-mail-read.png" ></a>
                          <? } else if($data["cont_status"]==2) { ?>
                            <a href="home.php?show=fair_contact_edit&cont_id=<?=$data["cont_id"]?>"><img height="30px" src="../backoffice/asset/icon_sendmail.png" ></a>
                          <?php } ?>
                        </td>
                      </tr>
                      <? $runno++; } } } ?>

                  </tbody>
              </table>
              </div>
          </div>
        </div>
	</div>
</div>
