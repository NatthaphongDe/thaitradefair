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

function getExhibitorList($ditpid) {
  $curl = curl_init();
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://driveapi.ditp.go.th/api/getfaircatalogue',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => 'SecurityKey=46c6f9c9-b624-4ce4-969b-8c56e136314c&User=ibusiness&ActivityID='.$ditpid,
  ));
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  $response = curl_exec($curl);
  curl_close($curl);
  $json = json_decode($response, true);

  return $json;

}
/* เก่า */
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
$sqltran = " truncate table tt_exhibitor_list ";
$stmttran = $mysqli->prepare($sqltran);
$stmttran->execute();
*/

$sqltran = " truncate table tt_exhibitor_booth ";
$stmttran = $mysqli->prepare($sqltran);
$stmttran->execute();

$sqltran = " truncate table tt_exhibitor_product ";
$stmttran = $mysqli->prepare($sqltran);
$stmttran->execute();


$sqlf = "select * from tt_fair_list where fair_status != 9 and fair_ditp_id > 0 order by fair_id ASC  ";
$stmtf = $mysqli->prepare($sqlf);
if($stmtf) {
  $stmtf->execute();
  $resultf = $stmtf->get_result();
  $numrowf = $resultf->num_rows;
  if($numrowf>0) {
    while($dataf = $resultf->fetch_assoc()) {

      $fairid = $dataf["fair_id"];
      $fairditpid = (int)$dataf["fair_ditp_id"];
      $json = getExhibitorList($fairditpid);

      if($json["CountItem"]>0) {

        for($i=0;$i<count($json["FairCatalogue"]);$i++) {

          $Company_name = "";
          $Company_Telephone = "";
          $Company_Fax = "";
          $Company_Email = "";
          $Company_Website = "";
          $Company_Address_Contact = "";
          $Company_Contanct_Full_Name = "";
          $Company_Contanct_Position = "";
          $Product_group = "";
          $Product_cat = "";
          $Product_brand = "";
          $Product_des = "";
          // if($json["FairCatalogue"][$i]["CompanyParent"]) {
          //   if($json["FairCatalogue"][$i]["CompanyParent"]["ParentID"]) {
          //     if($json["FairCatalogue"][$i]["CompanyParent"]["ParentID"]!="") {

                $Company_name = $json["FairCatalogue"][$i]["Company"]["Corporate_Name_EN"];
                $com_taxno = $json["FairCatalogue"][$i]["Company"]["TaxNo"];
                $Company_Address_Contact = $json["FairCatalogue"][$i]["Company"]["Address_EN"];
                $Company_Telephone = $json["FairCatalogue"][$i]["Company"]["Telephone"];
                $Company_Fax = $json["FairCatalogue"][$i]["Company"]["Fax"];
                $Company_Email = $json["FairCatalogue"][$i]["Company"]["Email"];
                $Company_Website = $json["FairCatalogue"][$i]["Company"]["Website"];
                $Company_Contanct_Full_Name = $json["FairCatalogue"][$i]["ListContact"][0]["Full_Name"];
                $Company_Contanct_Position = $json["FairCatalogue"][$i]["ListContact"][0]["Position"];
                $Product_group = $json["FairCatalogue"][$i]["FairCatalogueProduct"]["Product_group"];
                $Product_cat = $json["FairCatalogue"][$i]["FairCatalogueProduct"]["Product_cat"];
                $Product_brand = $json["FairCatalogue"][$i]["FairCatalogueProduct"]["Product_brand"];
                $Product_des = $json["FairCatalogue"][$i]["FairCatalogueProduct"]["Product_des"];

                $thaitrand_seller_code = "";
                $thaitrand_shop_url = "";
                $thaitrand_com_url = "";

                /*
                $sellercode = getThaitradeSellerCode($Company_name);
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
                $sellercode = getThaitradeSellerCode($com_taxno);
                if($sellercode["data"] != null) {
                    $thaitrand_seller_code = $sellercode["data"]["seller_code"];
                    $thaitrand_shop_url = $sellercode["data"]["shop_url"];
                    if($thaitrand_shop_url!="") {
                      $thaitrand_shop_url = "https://www.thaitrade.com".$thaitrand_shop_url;
                    }
                    $sellercodedetail = getThaitradeSellerDetail($thaitrand_seller_code);
                    $thaitrand_com_url = $sellercodedetail["data"]["website"];
                }

                $sqlfchk = "select * from tt_exhibitor_list where fair_id = ? and fair_ditp_id = ? and com_name = ? and com_taxno = ? limit 1  ";
                $stmtfchk = $mysqli->prepare($sqlfchk);
                $stmtfchk->bind_param('iiss',$fairid,$fairditpid,$Company_name,$com_taxno);
                $stmtfchk->execute();
                $resultfchk = $stmtfchk->get_result();
                $numrowfchk = $resultfchk->num_rows;
                if($numrowfchk>0) {
                  $datafchk = $resultfchk->fetch_assoc();

                  $sql = "update tt_exhibitor_list set
                           com_address = ?,
                           com_tel = ?,
                           com_fax = ?,
                           com_email = ?,
                           com_web = ?,
                           contact_name = ?,
                           contact_position = ?,
                           product_group = ?,
                           product_cat = ?,
                           product_brand = ?,
                           product_brand_desc = ?,
                           update_date = now(),
                           thaitrand_seller_code = ?,
                           thaitrand_shop_url = ?,
                           thaitrand_com_url = ?
                           where exl_id = ? ";
                  $stmt = $mysqli->prepare($sql);
                  $stmt->bind_param('ssssssssssssssi',$Company_Address_Contact,$Company_Telephone,$Company_Fax,$Company_Email,$Company_Website,$Company_Contanct_Full_Name,$Company_Contanct_Position,$Product_group,$Product_cat,$Product_brand,$Product_des,$thaitrand_seller_code,$thaitrand_shop_url,$thaitrand_com_url,$datafchk["exl_id"]);
                  $stmt->execute();
                  $exl_id = $datafchk["exl_id"];
                } else {
                  $sql = "insert into tt_exhibitor_list (fair_id,fair_ditp_id,com_name,com_address,com_tel,com_fax,com_email,com_web,contact_name,contact_position,product_group,product_cat,product_brand,product_brand_desc,update_date,thaitrand_seller_code,thaitrand_shop_url,thaitrand_com_url,com_taxno) values (?,?,?,?,?,?,?,?,?,?,?,?,?,?,now(),?,?,?,?) ";
                  $stmt = $mysqli->prepare($sql);
                  $stmt->bind_param('iissssssssssssssss',$fairid,$fairditpid,$Company_name,$Company_Address_Contact,$Company_Telephone,$Company_Fax,$Company_Email,$Company_Website,$Company_Contanct_Full_Name,$Company_Contanct_Position,$Product_group,$Product_cat,$Product_brand,$Product_des,$thaitrand_seller_code,$thaitrand_shop_url,$thaitrand_com_url,$com_taxno);
                  $stmt->execute();
                  $exl_id = $stmt->insert_id;
                }

                if($json["FairCatalogue"][$i]["ListBoothAllocate"]) {
                  for($b=0;$b<count($json["FairCatalogue"][$i]["ListBoothAllocate"]);$b++) {

                    $Booth_Amount = $json["FairCatalogue"][$i]["ListBoothAllocate"][$b]["Booth_Amount"];
                    $Hall_Id = $json["FairCatalogue"][$i]["ListBoothAllocate"][$b]["Hall_Id"];
                    $Hall_Name = $json["FairCatalogue"][$i]["ListBoothAllocate"][$b]["Hall_Name"];
                    $Block_Code = $json["FairCatalogue"][$i]["ListBoothAllocate"][$b]["Block_Code"];
                    $Booth_no = $json["FairCatalogue"][$i]["ListBoothAllocate"][$b]["Booth_no"];

                    $sqlbooth = "insert into tt_exhibitor_booth (exl_id,Booth_Amount,Hall_Id,Hall_Name,Block_Code,Booth_no) values (?,?,?,?,?,?) ";
                    $stmtbooth = $mysqli->prepare($sqlbooth);
                    if($stmtbooth) {
                      $stmtbooth->bind_param('isssss',$exl_id,$Booth_Amount,$Hall_Id,$Hall_Name,$Block_Code,$Booth_no);
                      $stmtbooth->execute();
                    }
                  }
                }


                if($json["FairCatalogue"][$i]["FairCatalogueProduct"]) {
                  if($json["FairCatalogue"][$i]["FairCatalogueProduct"]["ListImageUrl"]) {
                    for($p=0;$p<count($json["FairCatalogue"][$i]["FairCatalogueProduct"]["ListImageUrl"]);$p++) {

                      $ImageTitle = $json["FairCatalogue"][$i]["FairCatalogueProduct"]["ListImageUrl"][$p]["ImageTitle"];
                      $ImageUrl = $json["FairCatalogue"][$i]["FairCatalogueProduct"]["ListImageUrl"][$p]["ImageUrl"];

                      $sqlimg = "insert into tt_exhibitor_product (exl_id,ImageTitle,ImageUrl,update_date) values (?,?,?,now()) ";
                      $stmtimg = $mysqli->prepare($sqlimg);
                      if($stmtimg) {
                        $stmtimg->bind_param('iss',$exl_id,$ImageTitle,$ImageUrl);
                        $stmtimg->execute();
                      }

                    }
                  }
                }




            //     }
            //   }
            // }

          }
        }

    }
  }
}
?>
