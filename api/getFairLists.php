<?
include_once ("../backoffice/connect.php");

$array_data = array();
$value_data = array();

$token = $_POST["token"];

$year = $_POST["year"];
$month = $_POST["month"];
$limit = $_POST["limit"];
$offset = $_POST["offset"];

if ($year != null) {
    $ysearch = "AND a.fair_year = ?";
} else {
    $ysearch = "";
}

if ($month > 0) {
    $mdata = str_pad($month, 2, "0", STR_PAD_LEFT);
    $evt = "%-".$mdata."-%";
    $msearch = "AND a.fair_event_start LIKE ?";
} else {
    $msearch = "";
}
if ($limit != null && $limit > 0) {
    $slimit = "LIMIT ?";
}else{
    $slimit = "";
}

if ($offset != null && $offset > 0) {
    $soffset = "OFFSET ?";
}else{
    $soffset = "";
}

$sql = "SELECT * FROM tt_fair_list a LEFT JOIN tt_fair_group_list b ON a.fair_id = b.fair_id LEFT JOIN tt_fair_group c ON b.fair_group_id = c.fair_group_id WHERE a.fair_status = '1' AND b.fair_flag = '1' AND c.fair_group_status = '1' $ysearch $msearch ORDER BY fair_event_start DESC $slimit $soffset";
/* print_r($sql); */
$stmt = $mysqli->prepare($sql);

if ($year != null && $month > 0  ) {
    if ($limit != null && $limit > 0 && $offset != null && $offset > 0) {
        $stmt->bind_param('isii', $year, $evt,$limit,$offset);
    }else if($limit != null && $limit > 0 &&( $offset == null || $offset <= 0)) {
        $stmt->bind_param('isi', $year, $evt,$limit);
    }else if($offset != null && $offset > 0 && ($limit == null || $limit <= 0)) {
        $stmt->bind_param('isi', $year, $evt,$offset);
    }else{
        $stmt->bind_param('is', $year, $evt);
    }
} else if ($year != null && $month <= 0) {
    if ($limit != null && $limit > 0 && $offset != null && $offset > 0) {
        $stmt->bind_param('iii', $year,$limit,$offset);
    }else if($limit != null && $limit > 0 && ($offset == null || $offset <= 0)) {
        $stmt->bind_param('ii', $year,$limit);
    }else if($offset != null && $offset > 0 && ($limit == null || $limit <= 0)) {
        $stmt->bind_param('ii', $year,$offset);
    }else{
        $stmt->bind_param('i', $year);
    }
} else if ($year == null && $month > 0) {
    if ($limit != null && $limit > 0 && $offset != null && $offset > 0) {
        $stmt->bind_param('sii',$evt,$limit,$offset);
    }else if($limit != null && $limit > 0 && ($offset == null || $offset <= 0)) {
        $stmt->bind_param('si', $evt,$limit);
    }else if($offset != null && $offset > 0 && ($limit == null || $limit <= 0)) {
        $stmt->bind_param('si', $evt,$offset);
    }else{
        $stmt->bind_param('s', $evt);
    }
}else{
    if ($limit != null && $limit > 0 && $offset != null && $offset > 0) {
        $stmt->bind_param('ii',$limit,$offset);
    }else if($limit != null && $limit > 0 && ($offset == null || $offset <= 0)) {
        $stmt->bind_param('i',$limit);
    }else if($offset != null && $offset > 0 && ($limit == null || $limit <= 0)) {
        $stmt->bind_param('i',$offset);
    }
}

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