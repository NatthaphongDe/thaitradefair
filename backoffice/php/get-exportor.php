<?php
ini_set('display_errors', '0');
session_start();
date_default_timezone_set("Asia/Bangkok");

define ("__DB_CHARSET__",	'UTF-8');
define ("__DB_TYPE__",		'mysqli');
define ("__DB_PORT__",		'');
define ("__DB_HOSTNAME__", "localhost");
define ("__DB_USERNAME__", "ibusiness_trade");
define ("__DB_PASSWORD__", "LnZPGsh*U3PTU!li");
define ("__DB_NAME__", "ibusiness_trade");

$mysqli = mysqli_connect(__DB_HOSTNAME__,__DB_USERNAME__,__DB_PASSWORD__,__DB_NAME__);
mysqli_set_charset($mysqli,"utf8");

function getExportorList($type,$offset,$limitpage) {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'http://driveapi.ditp.go.th/api/getDITPMemberDetails',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => array('Token' => '46c6f9c9-b624-4ce4-969b-8c56e136314c','Type' => $type,'Offset' => $offset,'Limit' => $limitpage,'LastMidifyDate' => '2017/01/01'),
  ));

  $response = curl_exec($curl);
  curl_close($curl);
  $json = json_decode($response, true);

  return $json;

}

function getThaitradeSellerCode($keyword) {
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


$sqltran = " truncate table tt_exportor_list ";
$stmttran = $mysqli->prepare($sqltran);
$stmttran->execute();

$sqltran = " truncate table tt_exportor_product ";
$stmttran = $mysqli->prepare($sqltran);
$stmttran->execute();



$limitpage = 100;
for($zz=1;$zz<=2;$zz++) {
  $offset = 0;

  for($round=0;$round<=500;$round++) {

    if($zz==1) {
      $json = getExportorList(1,$offset,$limitpage);
    } else {
      $json = getExportorList(6,$offset,$limitpage);
    }
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
          if($json["DITPMember_Details"][$i]["List_CorporateAddress"][$j]["Address_Type"]=="0" or $json["DITPMember_Details"][$i]["List_CorporateAddress"][$j]["Address_Type"]==0) {
            $Telephone = $json["DITPMember_Details"][$i]["List_CorporateAddress"][$j]["Telephone"];
            $Mail = $json["DITPMember_Details"][$i]["List_CorporateAddress"][$j]["Mail"];
          }
        }


        $thaitrand_seller_code = "";
        $thaitrand_shop_url = "";
        $thaitrand_com_url = "";

        /*
        $sellercode = getThaitradeSellerCode($Corporate_Name_EN);
        if(count($sellercode["data"]["sellers"])>0) {
            $thaitrand_seller_code = $sellercode["data"]["sellers"][0]["seller_code"];
            $thaitrand_shop_url = $sellercode["data"]["sellers"][0]["shop_url"];
            if($thaitrand_shop_url!="") {
              $thaitrand_shop_url = "https://www.thaitrade.com".$thaitrand_shop_url;
            }
            $sellercodedetail = getThaitradeSellerDetail($thaitrand_seller_code);
            $thaitrand_com_url = $sellercodedetail["data"]["website"];
        }
        */


        $sql = "insert into tt_exportor_list (User_ID,DBD_Register_No,DBD_Register_Date,Corporate_Type_TH,Corporate_Type_EN,Corporate_Name_TH,Corporate_Name_EN,Property_Name,Modify_Date,User_Status,Telephone,Mail,update_date,thaitrand_seller_code,thaitrand_shop_url,thaitrand_com_url) values (?,?,?,?,?,?,?,?,?,?,?,?,now(),?,?,?) ";
        $stmt = $mysqli->prepare($sql);
        if($stmt) {
          $stmt->bind_param('sssssssssssssss',$User_ID,$DBD_Register_No,$DBD_Register_Date,$Corporate_Type_TH,$Corporate_Type_EN,$Corporate_Name_TH,$Corporate_Name_EN,$Property_Name,$Modify_Date,$User_Status,$Telephone,$Mail,$thaitrand_seller_code,$thaitrand_shop_url,$thaitrand_com_url);
          $stmt->execute();
          $exp_id = $stmt->insert_id;

          for($p=0;$p<count($json["DITPMember_Details"][$i]["List_CorporateProducts"]);$p++) {

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

          }

        }

      }
    } else {
      break;
    }
  }

}



?>
