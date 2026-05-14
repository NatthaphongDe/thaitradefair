<?php
include_once ("backoffice/connect.php");

$itemdatas = $mysqli->real_escape_string($_POST["chklist"]);
$itemdatas2 = $mysqli->real_escape_string($_POST["chklist2"]);
$itemdatas3 = $mysqli->real_escape_string($_POST["chklist3"]);
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

foreach ($itemdatas2 as $key => $value) {
  if((int)$value>0) {
    if($itemall=="") {
      $itemall = (int)$value;
    } else {
      $itemall = $itemall.",".(int)$value;
    }
  }
}

foreach ($itemdatas3 as $key => $value) {
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
$sqleld = " select * from tt_exportor_list where exp_id in ($itemall) group by exp_id order by Corporate_Name_EN ASC ";
$stmteld = $mysqli->prepare($sqleld);
$stmteld->execute();
$resulteld = $stmteld->get_result();
$numroweld = $resulteld->num_rows;
if($numroweld>0) {
  while($dataeld = $resulteld->fetch_assoc()) {

    $productitem = "";
    $sqleldp = " select Product_Name_EN from tt_exportor_product where exp_id = ? and Product_Name_EN != '' group by Product_Name_EN order by Product_Name_EN ASC ";
    $stmteldp = $mysqli->prepare($sqleldp);
    $stmteldp->bind_param('i',$dataeld["exp_id"]);
    $stmteldp->execute();
    $resulteldp = $stmteldp->get_result();
    $numroweldp = $resulteldp->num_rows;
    if($numroweldp>0) {
      while($dataeldp = $resulteldp->fetch_assoc()) {
        if($productitem=="") {
          $productitem = $dataeldp["Product_Name_EN"];
        } else {
          $productitem = $productitem.", ".$dataeldp["Product_Name_EN"];
        }
      }
    }

    $branditem = "";
    $sqleldp = " select Product_Brand_EN from tt_exportor_product where exp_id = ? and Product_Brand_EN != '' group by Product_Brand_EN order by Product_Brand_EN ASC ";
    $stmteldp = $mysqli->prepare($sqleldp);
    $stmteldp->bind_param('i',$dataeld["exp_id"]);
    $stmteldp->execute();
    $resulteldp = $stmteldp->get_result();
    $numroweldp = $resulteldp->num_rows;
    if($numroweldp>0) {
      while($dataeldp = $resulteldp->fetch_assoc()) {
        if($branditem=="") {
          $branditem = $dataeldp["Product_Brand_EN"];
        } else {
          $branditem = $branditem.", ".$dataeldp["Product_Brand_EN"];
        }
      }
    }

    $catitem = "";
    $sqleldp = " select Product_Cat_Name_EN from tt_exportor_product where exp_id = ? and Product_Cat_Name_EN != '' group by Product_Cat_Name_EN order by Product_Cat_Name_EN ASC ";
    $stmteldp = $mysqli->prepare($sqleldp);
    $stmteldp->bind_param('i',$dataeld["exp_id"]);
    $stmteldp->execute();
    $resulteldp = $stmteldp->get_result();
    $numroweldp = $resulteldp->num_rows;
    if($numroweldp>0) {
      while($dataeldp = $resulteldp->fetch_assoc()) {
        if($catitem=="") {
          $catitem = $dataeldp["Product_Cat_Name_EN"];
        } else {
          $catitem = $catitem.", ".$dataeldp["Product_Cat_Name_EN"];
        }
      }
    }

    $subcatitem = "";
    $sqleldp = " select Product_Sub_Cat_Name_EN from tt_exportor_product where exp_id = ? and Product_Sub_Cat_Name_EN != '' group by Product_Sub_Cat_Name_EN order by Product_Sub_Cat_Name_EN ASC ";
    $stmteldp = $mysqli->prepare($sqleldp);
    $stmteldp->bind_param('i',$dataeld["exp_id"]);
    $stmteldp->execute();
    $resulteldp = $stmteldp->get_result();
    $numroweldp = $resulteldp->num_rows;
    if($numroweldp>0) {
      while($dataeldp = $resulteldp->fetch_assoc()) {
        if($subcatitem=="") {
          $subcatitem = $dataeldp["Product_Sub_Cat_Name_EN"];
        } else {
          $subcatitem = $subcatitem.", ".$dataeldp["Product_Sub_Cat_Name_EN"];
        }
      }
    }

    $shortdetail = "";
    $sqleldp = " select Product_Description_EN from tt_exportor_product where exp_id = ? and Product_Description_EN != '' group by Product_Description_EN order by Product_Description_EN ASC ";
    $stmteldp = $mysqli->prepare($sqleldp);
    $stmteldp->bind_param('i',$dataeld["exp_id"]);
    $stmteldp->execute();
    $resulteldp = $stmteldp->get_result();
    $numroweldp = $resulteldp->num_rows;
    if($numroweldp>0) {
      while($dataeldp = $resulteldp->fetch_assoc()) {
        if($shortdetail=="") {
          $shortdetail = $dataeldp["Product_Description_EN"];
        } else {
          $shortdetail = $subcatitem.", ".$dataeldp["Product_Description_EN"];
        }
      }
    }

?>
<table border="0" cellpadding="10" width="100%" cellspacing="10" style="border-collapse:collapse; font-family:Tahoma;">
	<tr>
      <td colspan="2"><h3 style="padding-bottom:0; margin-bottom:0;"><u>
        <? if($dataeld["Corporate_Name_EN"]!="") { ?>
          <?=$dataeld["Corporate_Name_EN"]?>
        <? } else { ?>
          <?=$dataeld["Corporate_Name_TH"]?>
        <? } ?>
      </u></h3></td>
    </tr>
      <tr>
        <td width="30%" valign="top"><b>Company Register Date :</b></td>
        <td width="70%" valign="top"><?=getDateCompany($dataeld["DBD_Register_Date"])?></td>
      </tr>
      <tr>
        <td width="30%" valign="top"><b>Product :</b></td>
        <td width="70%" valign="top">
          <?=$productitem?>
        </td>
      </tr>
      <tr>
        <td width="30%" valign="top"><b>Brand :</b></td>
        <td width="70%" valign="top"><?=$branditem?></td>
      </tr>
      <tr>
        <td width="30%" valign="top"><b>Category :</b></td>
        <td width="70%" valign="top">
          <?=$catitem?>
        </td>
      </tr>
      <tr>
        <td width="30%" valign="top"><b>Sub Category :</b></td>
        <td width="70%" valign="top"><?=$subcatitem?></td>
      </tr>
      <tr>
        <td width="30%" valign="top"><b>Short Detail :</b></td>
        <td width="70%" valign="top"><?=$shortdetail?></td>
      </tr>
      <tr>
        <td width="30%" valign="top"><b>Last Update :</b></td>
        <td width="70%" valign="top"><?=getDateContent($dataeld["Modify_Date"])?></td>
      </tr>
      <tr>
        <td colspan="2" valign="top"><b><u>Company Contact</u></b></td>
      </tr>
      <tr>
        <td width="30%" valign="top"><b>Telephone :</b></td>
        <td width="70%" valign="top"><?=$dataeld["Telephone"]?></td>
      </tr>
      <tr>
        <td width="30%" valign="top"><b>Email :</b></td>
        <td width="70%" valign="top"><?=$dataeld["Mail"]?></td>
      </tr>
</table>
<hr>
<? } } ?>


</body>
</html>
