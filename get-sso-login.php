<?
include_once ("backoffice/connect.php");

function getSSO($code) {
  $codesso = "Bearer ".$code;
  $client_id = "SSO220628";
  $curl = curl_init();
   curl_setopt_array($curl, array(
     CURLOPT_URL => 'https://sso.ditp.go.th/sso/api/getinfo',
     CURLOPT_RETURNTRANSFER => true,
     CURLOPT_ENCODING => '',
     CURLOPT_MAXREDIRS => 10,
     CURLOPT_TIMEOUT => 0,
     CURLOPT_FOLLOWLOCATION => true,
     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
     CURLOPT_CUSTOMREQUEST => 'GET',
     CURLOPT_HTTPHEADER => array(
       'code: '.$codesso,
       'client_id: '.$client_id
     ),
   ));

   $response = curl_exec($curl);
   curl_close($curl);

   $datasso = json_decode($response, true);
   return $datasso;

}
$code = $_GET["code"];
$ssodata = getSSO($code);

$sso_id = "";
$sso_json = "";
$sso_naturalId = "";
$sso_name_th = "";
$sso_name_en = "";
$sso_email = "";
$sso_country = "";
$sso_address_th = "";
$sso_address_en = "";
$sso_company_en = "";
$sso_company_th = "";
$sso_title_name_en = "";
$sso_title_name_th = "";
$sso_login_ip = get_real_ip();

if($ssodata["res_code"]=="00") {
  $sso_json = json_encode($ssodata);
  $sso_id = $ssodata["res_result"]["ssoid"];
  $sso_naturalId = $ssodata["res_result"]["naturalId"];
  if($ssodata["res_result"]["member"]) {
    $sso_title_name_th = $ssodata["res_result"]["member"]["titleTh"];
    $sso_title_name_en = $ssodata["res_result"]["member"]["titleEn"];
    $sso_name_th = $ssodata["res_result"]["member"]["nameTh"]." ".$ssodata["res_result"]["member"]["lastnameEn"];
    $sso_name_en = $ssodata["res_result"]["member"]["nameEn"]." ".$ssodata["res_result"]["member"]["lastnameEn"];
    $sso_email = $ssodata["res_result"]["member"]["email"];
    $sso_country = $ssodata["res_result"]["member"]["tel_country_code"];

    if($ssodata["res_result"]["addressTh"]) {
      $sso_address_th = $ssodata["res_result"]["addressTh"]["address"]." ".$ssodata["res_result"]["addressTh"]["subdistrict"]." ".$ssodata["res_result"]["addressTh"]["district"]." ".$ssodata["res_result"]["addressTh"]["province"]." ".$ssodata["res_result"]["addressTh"]["postcode"];
    }

    if($ssodata["res_result"]["addressEn"]) {
      $sso_address_en = $ssodata["res_result"]["addressEn"]["address"]." ".$ssodata["res_result"]["addressEn"]["subdistrict"]." ".$ssodata["res_result"]["addressEn"]["district"]." ".$ssodata["res_result"]["addressEn"]["province"]." ".$ssodata["res_result"]["addressEn"]["postcode"];
    }


  } else {
    if($ssodata["res_result"]["sub_member"]) {
      $sso_title_name_th = $ssodata["res_result"]["sub_member"][0]["titleTh"];
      $sso_title_name_en = $ssodata["res_result"]["sub_member"][0]["titleEn"];
      $sso_name_th = $ssodata["res_result"]["sub_member"][0]["nameTh"]." ".$ssodata["res_result"]["sub_member"][0]["lastnameEn"];
      $sso_name_en = $ssodata["res_result"]["sub_member"][0]["nameEn"]." ".$ssodata["res_result"]["sub_member"][0]["lastnameEn"];
      $sso_email = $ssodata["res_result"]["sub_member"][0]["email"];
      $sso_country = $ssodata["res_result"]["sub_member"][0]["tel_country_code"];
    }

    if($ssodata["res_result"]["company"]) {
      $sso_company_th = $ssodata["res_result"]["company"]["nameTh"];
      $sso_company_en = $ssodata["res_result"]["company"]["nameEn"];
    }

    if($ssodata["res_result"]["addressTh"]) {
      $sso_address_th = $ssodata["res_result"]["addressTh"]["address"]." ".$ssodata["res_result"]["addressTh"]["subdistrict"]." ".$ssodata["res_result"]["addressTh"]["district"]." ".$ssodata["res_result"]["addressTh"]["province"]." ".$ssodata["res_result"]["addressTh"]["postcode"];
    }

    if($ssodata["res_result"]["addressEn"]) {
      $sso_address_en = $ssodata["res_result"]["addressEn"]["address"]." ".$ssodata["res_result"]["addressEn"]["subdistrict"]." ".$ssodata["res_result"]["addressEn"]["district"]." ".$ssodata["res_result"]["addressEn"]["province"]." ".$ssodata["res_result"]["addressEn"]["postcode"];
    }

  }

  $sql = "select * from tt_sso_login where sso_id = ? ";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param('s',$sso_id);
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {
    $data = $result->fetch_assoc();

    $sqlup = "update tt_sso_login set
              sso_json = ?,
              sso_naturalId = ?,
              sso_name_en = ?,
              sso_name_th = ?,
              sso_email = ?,
              sso_company_en = ?,
              sso_company_th = ?,
              sso_country = ?,
              sso_address_en = ?,
              sso_address_th = ?,
              sso_login_date = now(),
              sso_login_ip = ?
              where sso_id = ? ";
    $stmtup = $mysqli->prepare($sqlup);
    $stmtup->bind_param('ssssssssssss',$sso_json,$sso_naturalId,$sso_name_en,$sso_name_th,$sso_email,$sso_company_en,$sso_company_th,$sso_country,$sso_address_en,$sso_address_th,$sso_login_ip,$sso_id);
    $stmtup->execute();
  } else {

    $sqlup = "insert into tt_sso_login (sso_id,sso_json,sso_naturalId,sso_name_en,sso_name_th,sso_email,sso_company_en,sso_company_th,sso_country,sso_address_en,sso_address_th,sso_login_date,sso_login_ip) values (?,?,?,?,?,?,?,?,?,?,?,now(),?) ";
    $stmtup = $mysqli->prepare($sqlup);
    $stmtup->bind_param('ssssssssssss',$sso_id,$sso_json,$sso_naturalId,$sso_name_en,$sso_name_th,$sso_email,$sso_company_en,$sso_company_th,$sso_country,$sso_address_en,$sso_address_th,$sso_login_ip);
    $stmtup->execute();

  }

  $ip = get_real_ip();
  $sqlgl = "insert into tt_activity_sso (log_type,sso_id,sso_date,sso_ip,log_action) values ('1',?,now(),?,'Sign IN') ";
  $stmtgl = $mysqli->prepare($sqlgl);
  if($stmtgl) {
    $stmtgl->bind_param('ss',$sso_id,$ip);
    $stmtgl->execute();
  }

  setcookie("ssoid",$sso_id,$exire_cookie,'/',$DOMAIN,1);
}
if ($_GET["page"] != null) {
  header('Location: '.$_GET["page"]);
}else{
  header('Location: '.ROOTPATHDOMAIN."my-profile/");

}
exit();
?>
