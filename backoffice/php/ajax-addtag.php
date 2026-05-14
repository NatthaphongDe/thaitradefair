<?
include_once("../connect.php");

if($_REQUEST["method"]=="loadtag") {
  $datatag = $_GET["dataid"];

  $tagitem = explode('|',$datatag);

  $sqlf = "select * from tt_tag_master where tag_status != '9' order by tag_name ASC ";
  $stmtf = $mysqli->prepare($sqlf);
  if($stmtf) {
    $stmtf->execute();
    $resultf = $stmtf->get_result();
    $numrowf = $resultf->num_rows;
    if($numrowf>0) {
      while($dataf = $resultf->fetch_assoc()) {
        $haveteg = 0;
        for($tt=0;$tt<count($tagitem);$tt++) {
          if($dataf["tag_id"]==$tagitem[$tt]) {
            $haveteg = 1;
            break;
          }
        }
        ?>
        <span>
          <button data-val="<?=$haveteg?>" data-id="<?=$dataf["tag_id"]?>" onclick="changeTagMaster('<?=$dataf["tag_id"]?>');" id="tagm_<?=$dataf["tag_id"]?>" type="button" class="tagm_ btn btn-tag"><?=$dataf["tag_name"]?></button>
        </span>
        <?
      }
    }
  }
  ?>
  <span>
    <button onclick="addTag('<?=$datatag?>');" type="button" class="btn btn-addtag">+ Add Tags</button>
  </span>
  <?
  exit();
}

if($_REQUEST["method"]=="add") {
  include_once("../connect.php");

  $adminid = $_SESSION["id"];
  $tag_name = $_POST["tag_name"];
  $dataid = $_POST["dataid"];
  $tag_status = 1;

  $sql = "insert into tt_tag_master (tag_name,tag_status,tag_create_date,tag_create_by,tag_update_date,tag_update_by) values (?,?,now(),?,now(),?) ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('siii',$tag_name,$tag_status,$adminid,$adminid);
    $stmt->execute();
  }

  ?>
  <script type="text/javascript">
    top.pc_overlay(2);
    top.closeTag();
    top.loadTag('<?=$dataid?>');
  </script>
  <?
  exit();
}

$fair_grop_id = $_GET["id"];
?>
<form class="form-horizontal" method="post" name="form_Suppliers_add" enctype="multipart/form-data" id="form_Suppliers_add" action="php/ajax-addtag.php?method=add" target="com_m" onSubmit="pc_overlay(1);" >
<div class="modal-content">

    <div class="modal-body">
      <div class="row">
        <div class="col-xs-12">
          <strong class="menu_n_menu">Add Tag</strong>
        </div>
      </div>
      <hr>

      <div class="row">
        <div class="col-xs-12">
          <div class="form-group">
            <label class="col-lg-12 control-label">Tag Name*</label>
            <div class="col-lg-12">
              <input type="text" class="form-control" name="tag_name" required autocomplete="off" />
            </div>
          </div>
        </div>
      </div>

      <br>
      <div class="row">
          <div class="col-xs-12">
            <button type="submit" class="btn btn-success" style="width:150px;">Save</button>
              &nbsp;&nbsp;&nbsp;
            <a onclick="closeTag();" class="btn btn-default" style="width:150px;">Cancel</a>
          </div>
      </div>


    </div>

</div>

<?
$dataid = $_GET["dataid"];
?>
<input type="hidden" name="dataid" value="<?=$dataid?>">
</form>
