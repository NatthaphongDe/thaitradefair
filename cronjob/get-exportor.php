<?php
ini_set('display_errors', '0');
session_start();
date_default_timezone_set("Asia/Bangkok");

define ("__DB_CHARSET__",	'UTF-8');
define ("__DB_TYPE__",		'mysqli');
define ("__DB_PORT__",		'');
define ("__DB_HOSTNAME__", "localhost");
define ("__DB_USERNAME__", "ibusiness_trade");
define ("__DB_PASSWORD__", "!F*MLhKz8V34A.yo");
define ("__DB_NAME__", "ibusiness_trade");

$mysqli = mysqli_connect(__DB_HOSTNAME__,__DB_USERNAME__,__DB_PASSWORD__,__DB_NAME__);
mysqli_set_charset($mysqli,"utf8");

function getExportorList($type,$offset,$limitpage) {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://driveapi.ditp.go.th/api/getDITPMemberDetails',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array('Token' => '46c6f9c9-b624-4ce4-969b-8c56e136314c','Type' => $type,'Offset' => $offset,'Limit' => $limitpage,'LastMidifyDate' => '2017/01/01'),
  ));

  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  
  $response = curl_exec($curl);
  curl_close($curl);
  $json = json_decode($response, true);
  return $json;

}

function getExportorListDetail($userid)
{
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://driveapi.ditp.go.th/api/getDITPMemberDetail',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array('Token' => '46c6f9c9-b624-4ce4-969b-8c56e136314c', 'Type' => '1', 'Offset' => '0', 'Limit' => '10', 'UserID' => $userid),
  ));
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  $response = curl_exec($curl);
  curl_close($curl);
  $json = json_decode($response, true);
  return $json;
}

/* function getThaitradeSellerCode($keyword) {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.thaitrade.com/search-service/api/v1/getsellersearchmore',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS =>'{"keyword_search" : "'.$keyword.'"}',
    CURLOPT_HTTPHEADER => array(
      'Content-Type: application/json'
    ),
  ));

  $response = curl_exec($curl);
    curl_close($curl);
  $json = json_decode($response, true);
  return $json;

} */

/* ใหม่วันที่ 17/11/2568 */
function getThaitradeSellerCode($com_taxno) {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://openapi-strapi.thaitrade.com/api/v1/company/by_tax_id/'.$com_taxno,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
  ));

  $response = curl_exec($curl);
    curl_close($curl);
  $json = json_decode($response, true);
  return $json;

}

function getThaitradeSellerDetail($code) {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://www.thaitrade.com/seller-service/api/v1/getcompanyprofilebysellercode?sellerCode='.$code,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
  ));

  $response = curl_exec($curl);
    curl_close($curl);
  $json = json_decode($response, true);
  return $json;

}

/*
$sqltran = " truncate table tt_exportor_list ";
$stmttran = $mysqli->prepare($sqltran);
$stmttran->execute();
*/

/* $sqltran = " truncate table tt_exportor_product ";
$stmttran = $mysqli->prepare($sqltran);
$stmttran->execute(); */


$arraytype = array(0,1,3,5,6,7,32);
// 1=El, 3=TDC , 5=SPL ,6=SEL ,7=LSP,32=SMEX
$limitpage = 100;
for($zz=1;$zz<=6;$zz++) {
  $offset = 0;
  
  for($round=0;$round<=1000;$round++) {
    
    $json = getExportorList($arraytype[$zz],$offset,$limitpage);
    
    /*
    if($zz==1) {
      $json = getExportorList(1,$offset,$limitpage);
    } else {
      if($zz==2) {
        $json = getExportorList(6,$offset,$limitpage);
      } else {
        $json = getExportorList(7,$offset,$limitpage);
      }
    }
    */
    $offset = $offset+$limitpage;

    if($json["Status"]["CountItem"]>0) {

      for($i=0;$i<count($json["DITPMember_Details"]);$i++) {

        $User_ID = "";
        $DBD_Register_No = "";
        $DBD_Register_Date = "";
        $Corporate_Type_TH = "";
        $Corporate_Type_EN = "";
        $Corporate_Name_TH = "";
        $Corporate_Name_EN = "";
        $Property_Name = "";
        $Modify_Date = "";
        $User_Status = "";
        $Telephone = "";
        $Mail = "";

        $User_ID = $json["DITPMember_Details"][$i]["User_ID"];
        $DBD_Register_No = $json["DITPMember_Details"][$i]["DBD_Register_No"];
        $DBD_Register_Date = $json["DITPMember_Details"][$i]["DBD_Register_Date"];
        $Corporate_Type_TH = $json["DITPMember_Details"][$i]["Corporate_Type_TH"];
        $Corporate_Type_EN = $json["DITPMember_Details"][$i]["Corporate_Type_EN"];
        $Corporate_Name_TH = $json["DITPMember_Details"][$i]["Corporate_Name_TH"];
        $Corporate_Name_EN = $json["DITPMember_Details"][$i]["Corporate_Name_EN"];
        $Property_Name = $json["DITPMember_Details"][$i]["Property_Name"];
        $Modify_Date = $json["DITPMember_Details"][$i]["Modify_Date"];
        $User_Status = $json["DITPMember_Details"][$i]["User_Status"];

        /*
        $Telephone = $json["DITPMember_Details"][$i]["List_CorporateAddress"][0]["Telephone"];
        $Mail = $json["DITPMember_Details"][$i]["List_CorporateAddress"][0]["Mail"];
        */


        for($j=0;$j<count($json["DITPMember_Details"][$i]["List_CorporateAddress"]);$j++) {
          if($json["DITPMember_Details"][$i]["List_CorporateAddress"][$j]["Address_Type"]=="1" or $json["DITPMember_Details"][$i]["List_CorporateAddress"][$j]["Address_Type"]==1) {
            $Telephone = $json["DITPMember_Details"][$i]["List_CorporateAddress"][$j]["Telephone"];
            $Mail = $json["DITPMember_Details"][$i]["List_CorporateAddress"][$j]["Mail"];
          }
        }


        $thaitrand_seller_code = "";
        $thaitrand_shop_url = "";
        $thaitrand_com_url = "";

        
        $sellercode = getThaitradeSellerCode($Corporate_Name_EN);
        if($sellercode["data"] != null) {
            $thaitrand_seller_code = $sellercode["data"]["seller_code"];
            $thaitrand_shop_url = $sellercode["data"]["shop_url"];
            if($thaitrand_shop_url!="") {
              $thaitrand_shop_url = "https://www.thaitrade.com".$thaitrand_shop_url;
            }
            $sellercodedetail = getThaitradeSellerDetail($thaitrand_seller_code);
            $thaitrand_com_url = $sellercodedetail["data"]["website"];
        }
       


        if ($Corporate_Name_EN != '' && $Corporate_Name_TH != '' && $Corporate_Name_EN != '-' && $Corporate_Name_TH != '-' ) {
          $sqlfchk = "select * from tt_exportor_list where User_ID = ? limit 1  ";
          $stmtfchk = $mysqli->prepare($sqlfchk);
          $stmtfchk->bind_param('s',$User_ID);
          $stmtfchk->execute();
          $resultfchk = $stmtfchk->get_result();
          $numrowfchk = $resultfchk->num_rows;
          if($numrowfchk>0) {
            $datafchk = $resultfchk->fetch_assoc();

            $sql = "update tt_exportor_list set
                    DBD_Register_No = ?,
                    DBD_Register_Date = ?,
                    Corporate_Type_TH = ?,
                    Corporate_Type_EN = ?,
                    Corporate_Name_TH = ?,
                    Corporate_Name_EN = ?,
                    Property_Name = ?,
                    Modify_Date = ?,
                    User_Status = ?,
                    Telephone = ?,
                    Mail = ?,
                    update_date = now(),
                    thaitrand_seller_code = ?,
                    thaitrand_shop_url = ?,
                    thaitrand_com_url = ?
                    where exp_id = ? ";
            $stmt = $mysqli->prepare($sql);
            $stmt->bind_param('ssssssssssssssi',$DBD_Register_No,$DBD_Register_Date,$Corporate_Type_TH,$Corporate_Type_EN,$Corporate_Name_TH,$Corporate_Name_EN,$Property_Name,$Modify_Date,$User_Status,$Telephone,$Mail,$thaitrand_seller_code,$thaitrand_shop_url,$thaitrand_com_url,$datafchk["exp_id"]);
            $stmt->execute();
            $exp_id = $datafchk["exp_id"];

          } else {
            
            
              $sql = "insert into tt_exportor_list (User_ID,DBD_Register_No,DBD_Register_Date,Corporate_Type_TH,Corporate_Type_EN,Corporate_Name_TH,Corporate_Name_EN,Property_Name,Modify_Date,User_Status,Telephone,Mail,update_date,thaitrand_seller_code,thaitrand_shop_url,thaitrand_com_url) values (?,?,?,?,?,?,?,?,?,?,?,?,now(),?,?,?) ";
              $stmt = $mysqli->prepare($sql);
              $stmt->bind_param('sssssssssssssss',$User_ID,$DBD_Register_No,$DBD_Register_Date,$Corporate_Type_TH,$Corporate_Type_EN,$Corporate_Name_TH,$Corporate_Name_EN,$Property_Name,$Modify_Date,$User_Status,$Telephone,$Mail,$thaitrand_seller_code,$thaitrand_shop_url,$thaitrand_com_url);
              $stmt->execute();
              $exp_id = $stmt->insert_id;
            
            
          }
        }
        /* for($p=0;$p<count($json["DITPMember_Details"][$i]["List_CorporateProducts"]);$p++) {
          $ID = $json["DITPMember_Details"][$i]["List_CorporateProducts"][$p]["ID"];
          $Product_Cat_Name_TH = $json["DITPMember_Details"][$i]["List_CorporateProducts"][$p]["Product_Cat_Name_TH"];
          $Product_Cat_Name_EN = $json["DITPMember_Details"][$i]["List_CorporateProducts"][$p]["Product_Cat_Name_EN"];
          $Product_Sub_Cat_Name_TH = $json["DITPMember_Details"][$i]["List_CorporateProducts"][$p]["Product_Sub_Cat_Name_TH"];
          $Product_Sub_Cat_Name_EN = $json["DITPMember_Details"][$i]["List_CorporateProducts"][$p]["Product_Sub_Cat_Name_EN"];
          $Product_Group_Name_TH = $json["DITPMember_Details"][$i]["List_CorporateProducts"][$p]["Product_Group_Name_TH"];
          $Product_Group_Name_EN = $json["DITPMember_Details"][$i]["List_CorporateProducts"][$p]["Product_Group_Name_EN"];
          $Product_Name_TH = $json["DITPMember_Details"][$i]["List_CorporateProducts"][$p]["Product_Name_TH"];
          $Product_Name_EN = $json["DITPMember_Details"][$i]["List_CorporateProducts"][$p]["Product_Name_EN"];
          $Product_Brand_TH = $json["DITPMember_Details"][$i]["List_CorporateProducts"][$p]["Product_Brand_TH"];
          $Product_Brand_EN = $json["DITPMember_Details"][$i]["List_CorporateProducts"][$p]["Product_Brand_EN"];
          $Product_Description_TH = $json["DITPMember_Details"][$i]["List_CorporateProducts"][$p]["Product_Description_TH"];
          $Product_Description_EN = $json["DITPMember_Details"][$i]["List_CorporateProducts"][$p]["Product_Description_EN"];

          $sqlimg = "insert into tt_exportor_product (exp_id,ID,Product_Cat_Name_TH,Product_Cat_Name_EN,Product_Sub_Cat_Name_TH,Product_Sub_Cat_Name_EN,Product_Group_Name_TH,Product_Group_Name_EN,Product_Name_TH,Product_Name_EN,Product_Brand_TH,Product_Brand_EN,Product_Description_TH,Product_Description_EN) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
          $stmtimg = $mysqli->prepare($sqlimg);
          if($stmtimg) {
            $stmtimg->bind_param('isssssssssssss',$exp_id,$ID,$Product_Cat_Name_TH,$Product_Cat_Name_EN,$Product_Sub_Cat_Name_TH,$Product_Sub_Cat_Name_EN,$Product_Group_Name_TH,$Product_Group_Name_EN,$Product_Name_TH,$Product_Name_EN,$Product_Brand_TH,$Product_Brand_EN,$Product_Description_TH,$Product_Description_EN);
            $stmtimg->execute();
          }
        } */
      }
    } else {
      break;
    }
  }

}

$sqltran = " truncate table tt_exportor_product ";
$stmttran = $mysqli->prepare($sqltran);
$stmttran->execute();

$sql = "select exp_id,User_ID from tt_exportor_list ";
$stmtimg = $mysqli->prepare($sql);
$stmtimg->execute();
$result = $stmtimg->get_result();
$numrow = $result->num_rows;
if ($numrow > 0) {
  while ($data = $result->fetch_assoc()) {
    $exp_id = $data['exp_id'];
    $userid = $data['User_ID'];
    if ($userid != "") {
      $json = getExportorListDetail($userid);
      if ($json["Status"]["CountItem"] > 0) {
        if (count($json["Member_Detail"]["List_CorporateProducts"]) > 0) {
          for ($p = 0; $p < count($json["Member_Detail"]["List_CorporateProducts"]); $p++) {
            $ID = $json["Member_Detail"]["List_CorporateProducts"][$p]["ID"];
            $Product_Cat_Name_TH = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Cat_Name_TH"] != "" ? $json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Cat_Name_TH"] : NULL);
            $Product_Cat_Name_EN = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Cat_Name_EN"] != "" ? $json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Cat_Name_EN"] : NULL);
            $Product_Sub_Cat_Name_TH = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Sub_Cat_Name_TH"] != "" ? $json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Sub_Cat_Name_TH"] : NULL);
            $Product_Sub_Cat_Name_EN = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Sub_Cat_Name_EN"] != "" ? $json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Sub_Cat_Name_EN"] : NULL);
            $Product_Group_Name_TH = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Group_Name_TH"] != "" ? $json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Group_Name_TH"] : NULL);
            $Product_Group_Name_EN = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Group_Name_EN"] != "" ? $json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Group_Name_EN"] : NULL);
            $Product_Name_TH = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Name_TH"] != "" ? $json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Name_TH"] : NULL);
            $Product_Name_EN = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Name_EN"] != "" ? $json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Name_EN"] : NULL);
            $Product_Brand_TH = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Brand_TH"] != "" ? $json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Brand_TH"] : NULL);
            $Product_Brand_EN = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Brand_EN"] != "" ? $json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Brand_EN"] : NULL);
            $Product_Description_TH = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Description_TH"] != "" ? $json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Description_TH"] : NULL);
            $Product_Description_EN = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Description_EN"] != "" ? $json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Description_EN"] : NULL);

            $sqlimg = "insert into tt_exportor_product (exp_id,ID,Product_Cat_Name_TH,Product_Cat_Name_EN,Product_Sub_Cat_Name_TH,
                              Product_Sub_Cat_Name_EN,Product_Group_Name_TH,Product_Group_Name_EN,Product_Name_TH,Product_Name_EN,Product_Brand_TH,
                              Product_Brand_EN,Product_Description_TH,Product_Description_EN) 
                              values (?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
            try {
              $stmtimg = $mysqli->prepare($sqlimg);
              if ($stmtimg) {
                $stmtimg->bind_param('isssssssssssss', $exp_id, $ID, $Product_Cat_Name_TH, $Product_Cat_Name_EN, $Product_Sub_Cat_Name_TH, $Product_Sub_Cat_Name_EN, $Product_Group_Name_TH, $Product_Group_Name_EN, $Product_Name_TH, $Product_Name_EN, $Product_Brand_TH, $Product_Brand_EN, $Product_Description_TH, $Product_Description_EN);
                $stmtimg->execute();
              }
            } catch (\Throwable $th) {
              echo "Error: " . $th->getMessage();
            }
          }
        }
      }
    }
  }
}
