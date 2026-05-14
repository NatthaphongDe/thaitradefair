<?php
include_once ("backoffice/connect.php");

$itemdatas = $mysqli->real_escape_string($_POST["chklist"]);
$csrf_token = $mysqli->real_escape_string($_POST["csrf_token"]);
if ($_SESSION['csrf_token'] != $csrf_token) { ?>
  <script type="text/javascript">
    setTimeout(function () {top.pc_overlay(2);top.alertToken();},2000);
  </script>
<?}
$itemall = "";
foreach ($itemdatas as $key => $value) {
  if((int)$value>0) {
    if($itemall=="") {
      $itemall = (int)$value;
    } else {
      $itemall = $itemall.",".(int)$value;
    }
  }
}

?>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<html>
<head>
<META HTTP-EQUIV="Content-Type" CONTENT="text/html; charset=UTF-8">
</head>
<body onload="window.print();">


<?
$sqleld = " select * from tt_exhibitor_list where exl_id in ($itemall) group by exl_id order by com_name ASC ";
$stmteld = $mysqli->prepare($sqleld);
$stmteld->execute();
$resulteld = $stmteld->get_result();
$numroweld = $resulteld->num_rows;
if($numroweld>0) {
  while($dataeld = $resulteld->fetch_assoc()) {
?>
<table border="0" cellpadding="10" width="100%" cellspacing="10" style="border-collapse:collapse; font-family:Tahoma;">
	<tr>
      <td colspan="2"><h3 style="padding-bottom:0; margin-bottom:0;"><u><?=$dataeld["com_name"]?></u></h3></td>
    </tr>
      <tr>
        <td width="30%" valign="top"><b>Product :</b></td>
        <td width="70%" valign="top"><?=$dataeld["product_group"]?></td>
      </tr>
      <tr>
        <td width="30%" valign="top"><b>Brand :</b></td>
        <td width="70%" valign="top">
          <? if($dataeld["product_brand"]=="") { ?>
            <?=$dataeld["product_brand_desc"]?>
          <? } else { ?>
            <?=$dataeld["product_brand"]?>
            <br />
            <?=$dataeld["product_brand_desc"]?>
          <? } ?>
        </td>
      </tr>
      <tr>
        <td width="30%" valign="top"><b>Category :</b></td>
        <td width="70%" valign="top"><?=$dataeld["product_cat"]?></td>
      </tr>
      <tr>
        <td width="30%" valign="top"><b>Booth :</b></td>
        <td width="70%" valign="top">
          <?
          $sqleldb = " select Hall_Name from tt_exhibitor_booth where exl_id = ? group by Hall_Name order by Hall_Name ASC ";
          $stmteldb = $mysqli->prepare($sqleldb);
          $stmteldb->bind_param('i',$exl_id);
          $stmteldb->execute();
          $resulteldb = $stmteldb->get_result();
          $numroweldb = $resulteldb->num_rows;
          if($numroweldb>0) {
            while($dataeldb = $resulteldb->fetch_assoc()) {

              $sqleldbb = " select Block_Code from tt_exhibitor_booth where exl_id = ? and Hall_Name = ?  group by Block_Code order by Block_Code ASC ";
              $stmteldbb = $mysqli->prepare($sqleldbb);
              $stmteldbb->bind_param('is',$exl_id,$dataeldb["Hall_Name"]);
              $stmteldbb->execute();
              $resulteldbb = $stmteldbb->get_result();
              $numroweldbb = $resulteldbb->num_rows;
              if($numroweldbb>0) {
                while($dataeldbb = $resulteldbb->fetch_assoc()) {
              ?>
              <?=$dataeldb["Hall_Name"]?> : <?=$dataeldbb["Block_Code"]?> -
              <?
              $bb = 0;
              $sqleldbbb = " select Booth_no from tt_exhibitor_booth where exl_id = ? and Hall_Name = ? and Block_Code = ? order by Block_Code ASC ";
              $stmteldbbb = $mysqli->prepare($sqleldbbb);
              $stmteldbbb->bind_param('iss',$exl_id,$dataeldb["Hall_Name"],$dataeldbb["Block_Code"]);
              $stmteldbbb->execute();
              $resulteldbbb = $stmteldbbb->get_result();
              $numroweldbbb = $resulteldbbb->num_rows;
              if($numroweldbbb>0) {
                while($dataeldbbb = $resulteldbbb->fetch_assoc()) {
              ?>
                <? if($bb==0) { ?>
                  <?=$dataeldbbb["Booth_no"]?>
                <? } else { ?>
                  , <?=$dataeldbbb["Booth_no"]?>
                <? } ?>
              <? $bb++; } } ?>
              <br />
              <?
                }
              }
            }
          }
          ?>
        </td>
      </tr>
      <tr>
        <td width="30%" valign="top"><b>Address :</b></td>
        <td width="70%" valign="top"><?=$dataeld["com_address"]?></td>
      </tr>
      <tr>
        <td width="30%" valign="top"><b>Contact Person :</b></td>
        <td width="70%" valign="top"><?=$dataeld["contact_name"]?> - <?=$dataeld["contact_position"]?></td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><b><u>Company Contact</u></b></td>
      </tr>
      <tr>
        <td width="30%" valign="top"><b>Telephone :</b></td>
        <td width="70%" valign="top"><?=$dataeld["com_tel"]?></td>
      </tr>
      <tr>
        <td width="30%" valign="top"><b>Fax :</b></td>
        <td width="70%" valign="top"><?=$dataeld["com_fax"]?></td>
      </tr>
      <tr>
        <td width="30%" valign="top"><b>Email :</b></td>
        <td width="70%" valign="top"><?=$dataeld["com_email"]?></td>
      </tr>
</table>
<hr>
<? } } ?>


</body>
</html>
