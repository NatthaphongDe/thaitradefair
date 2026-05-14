<?
include_once("../connect.php");
if($_REQUEST["method"]=="add") {
  include_once("../connect.php");

  $adminid = $_SESSION["id"];
  $fair_id = $_POST["fair_id"];
  $admin_ids = $_POST["admin_id"];

  $sql2 = "delete from tt_fair_list_admin where fair_id = ? ";
  $stmt2 = $mysqli->prepare($sql2);
  $stmt2->bind_param('i',$fair_id);
  $stmt2->execute();

  //echo count($admin_ids);
  foreach ($admin_ids as $key => $value) {
    $id = (int)$value;
    if($id>0) {
      $sqlup = "insert into tt_fair_list_admin (fair_id,admin_id) values (?,?) ";
      $stmtup = $mysqli->prepare($sqlup);
      $stmtup->bind_param('ii',$fair_id,$id);
      $stmtup->execute();
    }
  }
  ?>
  <script type="text/javascript">
    top.pc_overlay(2);
    top.alertpopup("1","บันทึกรายการเรียบร้อย");
    setTimeout(function () {top.window.location="../home.php?show=adfair_list";},500);
  </script>
  <?php
  exit();

  exit();
}

$fair_id = $_GET["id"];
?>
<form class="form-horizontal" method="post" name="form_Suppliers_add" enctype="multipart/form-data" id="form_Suppliers_add" action="php/adfair_list_admin.php?method=add" target="com_m" onSubmit="pc_overlay(1);" >
<div class="modal-content">

    <div class="modal-body">
      <div class="row">
        <div class="col-xs-12">
          <strong class="menu_n_menu">เพิ่มผู้รับผิดชอบ</strong>
        </div>
      </div>
      <br>

      <div class="table-responsive">
         <table class="table table-stripped table-hover table-bordered" id="deferRenderTableAdmin">
            <thead>
            <tr>
              <th class="text-center tr-head">
                <input type="checkbox" id="act_id_chk"  onchange="getCheckItem('act_id_ss','act_id_chk');">
              </th>
              <th class="text-center tr-head">Username</th>
              <th class="text-center tr-head">Admin Name</th>
              <th class="text-center tr-head">Email</th>
              <th class="text-center tr-head">Role</th>
              <th class="text-center tr-head">Image</th>
            </tr>
            </thead>
            <tbody>
              <?
              $sql = "select * from tt_admin a left join tt_admin_role b on a.user_type=b.role_id where a.enable != '9' and b.role_lv = '3' and b.role_status != '9' order by a.username ASC ";
              $stmt = $mysqli->prepare($sql);
              if($stmt) {
                $stmt->execute();
                $result = $stmt->get_result();
                $numrow = $result->num_rows;
                if($numrow>0) {
                  $runno = 0;
                  while($data = $result->fetch_assoc()) {
                    ?>
                    <tr>
                      <td class="text-center vmiddle">
                        <input type="checkbox" name="admin_id[<?=$runno?>]" class="act_id_ss" value="<?=$data["id"]?>" <? if(checkAdminFairList($fair_id,$data["id"])) { echo "checked"; } ?>>
                      </td>
                      <td class="text-center vmiddle"><?=$data["username"]?></td>
                      <td class="text-center vmiddle"><?=$data["fullname"]?></td>
                      <td class="text-center vmiddle"><?=$data["email"]?></td>
                      <td class="text-center vmiddle"><?=$data["role_name"]?></td>
                      <td class="text-center vmiddle">
                        <img src="../backoffice/asset/icon_noimg.png" height="35">
                      </td>
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




      <br>
      <div class="row">
          <div class="col-xs-12">
            <button type="submit" class="btn btn-success" style="width:150px;">Save</button>
              &nbsp;&nbsp;&nbsp;
            <a href="home.php?show=adfair_list" class="btn btn-default" style="width:150px;">Cancel</a>
          </div>
      </div>


    </div>

</div>

<input type="hidden" name="fair_id" value="<?=$fair_id?>">
</form>

<script type="text/javascript">
function getCheckItem(div,id) {
  if($('#' + id).is(":checked")) {
    $("."+div).prop("checked", true);
  } else {
    $("."+div).prop("checked", false);
  }
}
</script>
