<?
include_once ("../backoffice/connect.php");

$array_data = array();
$value_data = array();

$token = $_POST["token"];

$year = $_POST["year"];
$month = $_POST["month"];
$limit = $_POST["limit"];
$offset = $_POST["offset"];
$tag_id = $_POST["tag_id"];
$tag_name = $_POST["tag_name"];

$arr_bind_si = array();
$arr_val_search = array();

if ($year != null) {
    $ysearch = "AND a.fair_year = ?";
    array_push($arr_bind_si,'s');
    array_push($arr_val_search,$year);
} else {
    $ysearch = "";
}

if ($month > 0) {
    $mdata = str_pad($month, 2, "0", STR_PAD_LEFT);
    $evt = "%-".$mdata."-%";
    $msearch = "AND a.fair_event_start LIKE ?";
    array_push($arr_bind_si,'s');
    array_push($arr_val_search,$evt);
} else {
    $msearch = "";
}
if ($tag_id != null) {
    $tisearch = "AND c.fair_group_id = ?";
    array_push($arr_bind_si,'i');
    array_push($arr_val_search,$tag_id);
} else {
    $tisearch = "";
}

if ($tag_name != null) {
    $tnsearch = "AND c.fair_group_abb = ?";
    array_push($arr_bind_si,'s');
    array_push($arr_val_search,$tag_name);
} else {
    $tnsearch = "";
}
if ($limit != null && $limit > 0) {
    $slimit = "LIMIT ?";
    array_push($arr_bind_si,'i');
    array_push($arr_val_search,$limit);
}else{
    $slimit = "";
}

if ($offset != null && $offset > 0) {
    $soffset = "OFFSET ?";
    array_push($arr_bind_si,'i');
    array_push($arr_val_search,$offset);
}else{
    $soffset = "";
}

$bind_si = implode('', $arr_bind_si);
$count = count($arr_val_search);
$sql = "SELECT * FROM tt_fair_list a LEFT JOIN tt_fair_group_list b ON a.fair_id = b.fair_id LEFT JOIN tt_fair_group c ON b.fair_group_id = c.fair_group_id WHERE a.fair_status = '1' AND b.fair_flag = '1' AND c.fair_group_status = '1' $ysearch $msearch $tisearch $tnsearch ORDER BY fair_event_start DESC $slimit $soffset";

$stmt = $mysqli->prepare($sql);

if ($count==1) { $stmt->bind_param("$bind_si", $arr_val_search[0]);}
if ($count==2) { $stmt->bind_param("$bind_si", $arr_val_search[0], $arr_val_search[1]);}
if ($count==3) { $stmt->bind_param("$bind_si", $arr_val_search[0], $arr_val_search[1], $arr_val_search[2]);}
if ($count==4) { $stmt->bind_param("$bind_si", $arr_val_search[0], $arr_val_search[1], $arr_val_search[2], $arr_val_search[3]);}
if ($count==5) { $stmt->bind_param("$bind_si", $arr_val_search[0], $arr_val_search[1], $arr_val_search[2], $arr_val_search[3], $arr_val_search[4]);}
if ($count==6) { $stmt->bind_param("$bind_si", $arr_val_search[0], $arr_val_search[1], $arr_val_search[2], $arr_val_search[3], $arr_val_search[4], $arr_val_search[5]);}


$stmt->execute();
$result = $stmt->get_result();
$numrow = $result->num_rows;
if($numrow>0) {
    $array_data["res_code"] = "00";
    $array_data["res_status"] = "success";
    $array_data["res_text"] = "success";
    $array_data["ConntItem"] = $numrow;
    $array_data["ItemData"] = array();
    while($data = $result->fetch_assoc()) {
        $array_item = array();

        $arr = explode(', ', $data['fair_vanue']);
        (!empty($arr[0])? $vanue =$arr[0] :$vanue="-");
        (!empty($arr[1])? $country =$arr[1] :$country="-");

        $public_start['date'] = date("d",strtotime($data["fair_public_start"]));
        $public_start['month'] = date("m",strtotime($data["fair_public_start"]));
        $public_start['year'] = date("Y",strtotime($data["fair_public_start"]));

        $public_end['date'] = date("d",strtotime($data["fair_public_end"]));
        $public_end['month'] = date("m",strtotime($data["fair_public_end"]));
        $public_end['year'] = date("Y",strtotime($data["fair_public_end"]));

        $trade_start['date'] = date("d",strtotime($data["fair_trade_start"]));
        $trade_start['month'] = date("m",strtotime($data["fair_trade_start"]));
        $trade_start['year'] = date("Y",strtotime($data["fair_trade_start"]));

        $trade_end['date'] = date("d",strtotime($data["fair_trade_end"]));
        $trade_end['month'] = date("m",strtotime($data["fair_trade_end"]));
        $trade_end['year'] = date("Y",strtotime($data["fair_trade_end"]));
        
        $array_item['banner'] = ROOTPATHDOMAIN.$data['fair_path_banner'];
        $array_item['fair_name'] = $data['fair_name'];
        $array_item["tag_id"] = $data['fair_group_id'];
        $array_item["tag_name"] = $data['fair_group_abb'];
        $array_item['fair_public_start'] = $public_start;
        $array_item['fair_public_end'] = $public_end;
        $array_item['fair_trade_start'] = $trade_start;
        $array_item['fair_trade_end'] = $trade_end;
        $array_item['fair_venue'] = $vanue;
        $array_item['fair_country'] = $country;
        $array_item['fair_link'] = $data['fair_group_link'];
        
        array_push($value_data,$array_item);
        
    }
    $array_data["ItemData"] = $value_data;
}else{
    $array_data["res_code"] = "01";
    $array_data["res_status"] = "error";
    $array_data["res_text"] = "Not Found Data.";
    $array_data["ConntItem"] = 0;
}
echo $json = json_encode($array_data);