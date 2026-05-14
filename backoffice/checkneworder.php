<?php include("connect.php");

$alldata = file_get_contents("php://input");
$jsondata = json_decode(file_get_contents("php://input"));
$_REQUEST[api_id] = $jsondata->api_id*1;
$_REQUEST[current_time] = $jsondata->current_time;

$array_all = array();
$sql = " select * from order_list where order_status = '0' and ship_add != '' and order_create_date > '$_REQUEST[current_time]' ";
$exec = mysqli_query($conn, $sql);
$num = mysqli_num_rows($exec);
if($num>0) {
  $array_all['status'] = 1;
  $array_all['current_time'] = date("Y-m-d H:i:s");
  $array_all['allnum'] = "(".number_format($num).")";
} else {
  $array_all['status'] = 0;
  $array_all['current_time'] = $_REQUEST[current_time];
  $array_all['allnum'] = 0;
}

echo json_encode($array_all);
exit();
?>
