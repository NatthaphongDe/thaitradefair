<?
include_once("../connect.php");
if($_REQUEST["method"]=="delete") {

  $id = (int)$_GET["id"];

  $sql = "delete from tt_fair_content_file where file_id = ? ";
  $stmt = $mysqli->prepare($sql);
  if($stmt) {
    $stmt->bind_param('i',$id);
    $stmt->execute();
    if($id>0) {
      $rmdirs =ROOTPATH."/data/fairattachfile/".$id;
      deleteDirectory($rmdirs);
    }

  }

  exit();
}
?>
