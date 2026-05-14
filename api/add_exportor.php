<?
include_once("../backoffice/connect.php");
function getExportorList($userid)
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
$userid = $_POST["UserID"];
$sql = "select * from tt_exportor_list where User_ID = ?";
$stmtimg = $mysqli->prepare($sql);
$stmtimg->bind_param('i',$userid);
$stmtimg->execute();
$result = $stmtimg->get_result();
$numrow = $result->num_rows;
$data = $result->fetch_assoc();
$exp_id = $data['exp_id'];

if ($userid != "") {
    $json = getExportorList($userid);
}

if (count($json["Member_Detail"]["List_CorporateProducts"]) > 0) {

    for ($p = 0; $p < count($json["Member_Detail"]["List_CorporateProducts"]); $p++) {

        $ID = $json["Member_Detail"]["List_CorporateProducts"][$p]["ID"];  
        $Product_Cat_Name_TH = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Cat_Name_TH"] != ""? $json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Cat_Name_TH"]:NULL); 
        $Product_Cat_Name_EN = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Cat_Name_EN"] != ""? $json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Cat_Name_EN"]:NULL); 
        $Product_Sub_Cat_Name_TH = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Sub_Cat_Name_TH"] != ""? $json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Sub_Cat_Name_TH"]:NULL); 
        $Product_Sub_Cat_Name_EN = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Sub_Cat_Name_EN"] != ""? $json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Sub_Cat_Name_EN"]:NULL); 
        $Product_Group_Name_TH = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Group_Name_TH"]!= ""?$json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Group_Name_TH"]:NULL); 
        $Product_Group_Name_EN = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Group_Name_EN"]!= ""?$json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Group_Name_EN"]:NULL); 
        $Product_Name_TH = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Name_TH"]!= ""?$json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Name_TH"]:NULL); 
        $Product_Name_EN = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Name_EN"]!= ""?$json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Name_EN"]:NULL); 
        $Product_Brand_TH = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Brand_TH"]!= ""?$json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Brand_TH"]:NULL); 
        $Product_Brand_EN = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Brand_EN"]!= ""?$json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Brand_EN"]:NULL); 
        $Product_Description_TH = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Description_TH"]!= ""?$json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Description_TH"]:NULL); 
        $Product_Description_EN = ($json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Description_EN"]!= ""?$json["Member_Detail"]["List_CorporateProducts"][$p]["Product_Description_EN"]:NULL); 

        $sqlimg = "insert into tt_exportor_product (exp_id,ID,Product_Cat_Name_TH,Product_Cat_Name_EN,Product_Sub_Cat_Name_TH,
        Product_Sub_Cat_Name_EN,Product_Group_Name_TH,Product_Group_Name_EN,Product_Name_TH,Product_Name_EN,Product_Brand_TH,
        Product_Brand_EN,Product_Description_TH,Product_Description_EN) 
        values (?,?,?,?,?,?,?,?,?,?,?,?,?,?) ";
       try {
        
        $stmtimg = $mysqli->prepare($sqlimg);
        if ($stmtimg) {
            $stmtimg->bind_param('isssssssssssss',$exp_id,$ID,$Product_Cat_Name_TH,$Product_Cat_Name_EN,$Product_Sub_Cat_Name_TH,$Product_Sub_Cat_Name_EN,$Product_Group_Name_TH,$Product_Group_Name_EN,$Product_Name_TH,$Product_Name_EN,$Product_Brand_TH,$Product_Brand_EN,$Product_Description_TH,$Product_Description_EN);
            $stmtimg->execute();
        }
       } catch (\Throwable $th) {
        echo "Error: " . $th->getMessage();
       }
        
    }
}
