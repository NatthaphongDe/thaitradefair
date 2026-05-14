<?php
function getRequestHeaders() {
    $headers = array();
    foreach($_SERVER as $key => $value) {
        if (substr($key, 0, 5) <> 'HTTP_') {
            continue;
        }
        $header = str_replace(' ', '-', ucwords(str_replace('_', ' ', strtolower(substr($key, 5)))));
        $headers[$header] = $value;
    }
    return $headers;
}

function checklogin(){
	if ($_SESSION["id"]==""){
		?>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<script language="JavaScript">alert('Session expired please re login.');top.window.location='index.php';</script>
		<?php
		exit();
	}
}
function alphanumeric_random_wms($num_require=12) {
	$alphanumeric = array('a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z',1,2,3,4,5,6,7,8,9);
	if($num_require > sizeof($alphanumeric)){
		echo "Error alphanumeric_rand(\$num_require) : \$num_require must less than " . sizeof($alphanumeric) . ", $num_require given";
		return;
	}
	$rand_key = array_rand($alphanumeric , $num_require);
	for($i=0;$i<sizeof($rand_key);$i++) $randomstring .= $alphanumeric[$rand_key[$i]];
	return $randomstring;
}

function lastdot($str){
	$str=strtolower($str);
	$type=explode('.',$str);
	$s=sizeof($type)-1;
	$type=$type[$s];
	return $type;
}

function deleteDirectory($dir) {
        if (!file_exists($dir)) return true;
        if (!is_dir($dir)) return unlink($dir);
        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;
            if (!deleteDirectory($dir.DIRECTORY_SEPARATOR.$item)) return false;
        }
        return rmdir($dir);
}

function text_change($str) {
	$str=htmlspecialchars(stripslashes($str));
	$str=str_replace("'","`",$str);
	$str = trim($str);
	return $str;
}

function debug_bind_param(){
    $numargs = func_num_args();
    $numVars = $numargs - 2;
    $arg2 = func_get_arg(1);
    $flagsAr = str_split($arg2);
    $showAr = array();
    for($i=0;$i<$numargs;$i++){
        switch($flagsAr[$i]){
        case 's' :  $showAr[] = "'".func_get_arg($i+2)."'";
        break;
        case 'i' :  $showAr[] = func_get_arg($i+2);
        break;
        case 'd' :  $showAr[] = func_get_arg($i+2);
        break;
        case 'b' :  $showAr[] = "'".func_get_arg($i+2)."'";
        break;
        }
    }
    $query = func_get_arg(0);
    $querysAr = str_split($query);
    $lengthQuery = count($querysAr);
    $j = 0;
    $display = "";
    for($i=0;$i<$lengthQuery;$i++){
        if($querysAr[$i] === '?'){
            $display .= $showAr[$j];
            $j++;
        }else{
            $display .= $querysAr[$i];
        }
    }
    if($j != $numVars){
        $display = "Mismatch on Variables to Placeholders (?)";
    }
    return $display;
}

function sql_safe($value,$allow_wildcards = false, $detect_numeric = true) {
  if (get_magic_quotes_gpc()) {
	if(ini_get('magic_quotes_sybase')) {
	  $value = str_replace("''", "'", $value);
	} else {
	  $value = stripslashes($value);
	}
  }
  return addslashes($value);
}

function get_real_ip(){
	$ip = false;
	if(!empty($_SERVER['HTTP_CLIENT_IP'])){
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} else {
  	if(!empty($_SERVER['HTTP_X_FORWARDED_FOR'])){
  		$ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
  		if($ip){
  			array_unshift($ips, $ip);
  			$ip = false;
  		}
  		for($i = 0; $i < count($ips); $i++){
  			if(!preg_match("/^(10|172\.16|192\.168)\./i", $ips[$i])){
  				if(version_compare(phpversion(), "5.0.0", ">=")){
  					if(ip2long($ips[$i]) != false){
  						$ip = $ips[$i];
  						break;
  					}
  				}else{
  					if(ip2long($ips[$i]) != - 1){
  						$ip = $ips[$i];
  						break;
  					}
  				}
  			}
  		}
  	}
  }
	return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
}

function strlimit($s,$n){
	if(iconv_strlen($s,'UTF-8')>$n)
		return iconv_substr($s, 0, $n, "UTF-8")."..";
	else
		return $s;
}

function getDateThai($date) {
	// $jobyear = date("Y",strtotime($date));
	// $jobyear = $jobyear+543;
	// $jobdate = date("d/m",strtotime($date));
	// $jobdate = $jobdate."/".$jobyear;
	// return $jobdate;
	$jobyear = date("Y",strtotime($date));
	$jobyear = $jobyear+543;
	$jobdate = date("m/d",strtotime($date));
	$jobdate = $jobyear."/".$jobdate;
	return $jobdate;
}

function getDateTimeThai($date) {
	// $jobyear = date("Y",strtotime($date));
	// $jobyear = $jobyear+543;
	// $jobdate = date("d/m",strtotime($date));
	// $jobdate = $jobdate."/".$jobyear;
	// $jobtime = date("H:i",strtotime($date));
	// $jobdate = $jobdate." ".$jobtime." น.";
	// return $jobdate;

	$jobyear = date("Y",strtotime($date));
	$jobyear = $jobyear+543;
	$jobdate = date("m/d",strtotime($date));
	$jobdate = $jobyear."/".$jobdate;
	$jobtime = date("H:i",strtotime($date));
	$jobdate = $jobdate." ".$jobtime." น.";
	return $jobdate;
}

function getDateContent($date) {
  $arr = array("","JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC");
  $day = date("d",strtotime($date));
  $day = (int)$day;

  $month = date("m",strtotime($date));
  $month = (int)$month;
  $month = $arr[$month];

  $year = date("Y",strtotime($date));
  $time = date("H:i",strtotime($date));

	$fulldate = $day." ".$month." ".$year." ".$time;
	return $fulldate;
}

function getDateCompany($date) {
  $arr = array("","JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC");
  $day = date("d",strtotime($date));
  $day = (int)$day;

  $month = date("m",strtotime($date));
  $month = (int)$month;
  $month = $arr[$month];

  $year = date("Y",strtotime($date));

	$fulldate = $day." ".$month." ".$year;
	return $fulldate;
}


function getAdminName($id) {
	global $mysqli;

	$name = "";
	$sql2 = "select * from tt_admin where id = ? limit 1 ";
	$stmt2 = $mysqli->prepare($sql2);
	if($stmt2) {
		$stmt2->bind_param('i',$id);
		$stmt2->execute();
		$result2 = $stmt2->get_result();
		$numrow2 = $result2->num_rows;
		if($numrow2>0) {
			$data = $result2->fetch_assoc();
			$name = $data["fullname"];
		}
	}
	return $name;
}

function plusDAy($num,$date) {
	$tomorrow = date('Y-m-d',strtotime($date . "+$num days"));
	return $tomorrow;
}

function getDateThaiMonth($date) {

	$arr = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");

	$jobyear = date("Y",strtotime($date));
	$jobyear = $jobyear+543;

	$jobdatem = date("m",strtotime($date));
	$jobdatem = (int)$jobdatem;
	$jobdatem  = $arr[$jobdatem];

	$jobdate = date("d",strtotime($date));
	$jobdate = $jobdate." ".$jobdatem." ".$jobyear;
	//$jobdate = $jobyear." ".$jobdatem." ".$jobdate;
	return $jobdate;
}

function getMonthEng($m) {
  $m = (int)$m;
	$arr = array("","JAN","FEB","MAR","APR","MAY","JUN","JUL","AUG","SEP","OCT","NOV","DEC");
	return $arr[$m];
}


function getFairList() {
  /* error_reporting(E_ALL);
  ini_set('display_errors', 1); */
  $curl = curl_init();
  
  curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://driveapi.ditp.go.th/api/getDITPActivityList',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'POST',
    //CURLOPT_POSTFIELDS => array('Token' => '46c6f9c9-b624-4ce4-969b-8c56e136314c','Offset' => '0','Limit' => '9999999','LastModifyDate' => '','Activity_Type_ID' => '12'),
    CURLOPT_POSTFIELDS => 'Token=46c6f9c9-b624-4ce4-969b-8c56e136314c&Offset=0&Limit=9999999&LastModifyDate=&Activity_Type_ID=12',
    CURLOPT_HTTPHEADER => array(
      'token: 46c6f9c9-b624-4ce4-969b-8c56e136314c',
      'Content-Type: application/x-www-form-urlencoded'
    ),
  ));
  
  curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
  $response = curl_exec($curl);

/*   if ($response === false) {
    echo "cURL Error: " . curl_error($curl);
  } else {
      print_r($response);
  }
  exit();
  */
  curl_close($curl);
  $json = json_decode($response, true);
  
  return $json;
}

function getRolePageStatus($role_id,$page_id) {
	global $mysqli;

	$array = array();
	$sql2 = "select * from tt_admin_role_page_item where role_id = ? and page_id = ? limit 1 ";
	$stmt2 = $mysqli->prepare($sql2);
	if($stmt2) {
		$stmt2->bind_param('ii',$role_id,$page_id);
		$stmt2->execute();
		$result2 = $stmt2->get_result();
		$numrow2 = $result2->num_rows;
		if($numrow2>0) {
			$data = $result2->fetch_assoc();
			$array = $data;
		}
	}
	return $array;
}

function getRolePageMenu($role_id,$page_id) {
	global $mysqli;

	$array = array();
	$sql2 = "select * from tt_admin_role_page_item where role_id = ? and page_id = ? limit 1 ";
	$stmt2 = $mysqli->prepare($sql2);
	if($stmt2) {
		$stmt2->bind_param('ii',$role_id,$page_id);
		$stmt2->execute();
		$result2 = $stmt2->get_result();
		$numrow2 = $result2->num_rows;
		if($numrow2>0) {
			$data = $result2->fetch_assoc();
			$array = $data["page_item_status"];
		}
	}
	return $array;
}

function getRolePagePermission($role_id,$page_id) {
	global $mysqli;

	$array = array();
	$sql2 = "select * from tt_admin_role_page_item where role_id = ? and page_id = ? limit 1 ";
	$stmt2 = $mysqli->prepare($sql2);
	if($stmt2) {
		$stmt2->bind_param('ii',$role_id,$page_id);
		$stmt2->execute();
		$result2 = $stmt2->get_result();
		$numrow2 = $result2->num_rows;
		if($numrow2>0) {
			$data = $result2->fetch_assoc();
			$array = $data["page_item_action"];
		}
	}
	return $array;
}

function checkAdminFairGroup($fairgroupid,$adminid) {
	global $mysqli;

	$sql2 = "select * from tt_fair_group_admin where fair_group_id = ? and admin_id = ? limit 1 ";
	$stmt2 = $mysqli->prepare($sql2);
	if($stmt2) {
		$stmt2->bind_param('ii',$fairgroupid,$adminid);
		$stmt2->execute();
		$result2 = $stmt2->get_result();
		$numrow2 = $result2->num_rows;
		if($numrow2>0) {
			return true;
		} else {
      return false;
    }
	}
	return false;
}

function checkAdminFairList($fair_id,$adminid) {
	global $mysqli;

	$sql2 = "select * from tt_fair_list_admin where fair_id = ? and admin_id = ? limit 1 ";
	$stmt2 = $mysqli->prepare($sql2);
	if($stmt2) {
		$stmt2->bind_param('ii',$fair_id,$adminid);
		$stmt2->execute();
		$result2 = $stmt2->get_result();
		$numrow2 = $result2->num_rows;
		if($numrow2>0) {
			return true;
		} else {
      return false;
    }
	}
	return false;
}

function getFairListMonth($year,$month) {
	global $mysqli;

  $monthdata = str_pad($month, 2, "0", STR_PAD_LEFT);
  $event = $year."-".$monthdata;

  $sql2 =  " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and a.fair_event_start like  '$event%' and b.fair_flag = '1' and c.fair_group_status = '1' order by fair_event_start DESC ";
	$stmt2 = $mysqli->prepare($sql2);
	if($stmt2) {
		//$stmt2->bind_param('s',$event.'%');
		$stmt2->execute();
		$result2 = $stmt2->get_result();
		$numrow2 = $result2->num_rows;
    return $numrow2;
	}
	return 0;
}

function getMasterMenu($fct_id) {
	global $mysqli;

	$sql2 = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fct_id = ? limit 1 ";
	$stmt2 = $mysqli->prepare($sql2);
	if($stmt2) {
		$stmt2->bind_param('i',$fct_id);
		$stmt2->execute();
		$result2 = $stmt2->get_result();
		$numrow2 = $result2->num_rows;
    if($numrow2>0) {
      $datac2 = $result2->fetch_assoc();
      if($datac2["fct_cms_type"]==0 or $datac2["fct_cms_type"]==4) {
        return $datac2["fcat_id"];
      } else {
        return $datac2["fcat_master"];
      }
    } else {
      return 0;
    }
	}
	return 0;
}

function getFairFirstMenu($fair_id) {
	global $mysqli;

	$sqlc = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fair_id = ? and b.fcat_type = '1' and b.fcat_status = '1' and a.fct_status = '1' order by a.fct_pos ASC limit 1 ";
	$stmtc = $mysqli->prepare($sqlc);
	if($stmtc) {
		$stmtc->bind_param('i',$fair_id);
		$stmtc->execute();
		$resultc = $stmtc->get_result();
		$numrowc = $resultc->num_rows;
    if($numrowc>0) {
      $datac = $resultc->fetch_assoc();
      if($datac["fct_cms_type"]==0) {
        $sqlc2 = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fair_id = ? and b.fcat_master = ? and b.fcat_type = '2' and b.fcat_status = '1' and a.fct_status = '1' order by a.fct_pos ASC limit 1 ";
        $stmtc2 = $mysqli->prepare($sqlc2);
        $stmtc2->bind_param('ii',$fair_id,$datac["fcat_id"]);
        $stmtc2->execute();
        $resultc2 = $stmtc2->get_result();
        $numrowc2 = $resultc2->num_rows;
        if($numrowc2>0) {
          $datac2 = $resultc2->fetch_assoc();
          return $datac2["fct_id"];
        } else {
          return 0;
        }
      } else {
        return $datac["fct_id"];
      }
    } else {
      return 0;
    }
	}
	return 0;
}

function getFairMenuName($fct_id) {
  global $mysqli;
  $name = "";

	$sql2 = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fct_id = ? limit 1 ";
	$stmt2 = $mysqli->prepare($sql2);
	if($stmt2) {
		$stmt2->bind_param('i',$fct_id);
		$stmt2->execute();
		$result2 = $stmt2->get_result();
		$numrow2 = $result2->num_rows;
    if($numrow2>0) {
      $datac2 = $result2->fetch_assoc();
      if($datac2["fcat_master"]!=0) {
        $name = $datac2["fcat_name"];
      }

    }
	}
	return $name;
}

function getFairMasterMenuName($fct_id) {
  global $mysqli;
  $name = "";

	$sql2 = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fct_id = ? limit 1 ";
	$stmt2 = $mysqli->prepare($sql2);
	if($stmt2) {
		$stmt2->bind_param('i',$fct_id);
		$stmt2->execute();
		$result2 = $stmt2->get_result();
		$numrow2 = $result2->num_rows;
    if($numrow2>0) {
      $datac2 = $result2->fetch_assoc();
      if($datac2["fcat_master"]==0) {
        $name = $datac2["fcat_name"];
      } else {
        $sqlc2x = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fcat_id = ? limit 1 ";
        $stmtc2x = $mysqli->prepare($sqlc2x);
        $stmtc2x->bind_param('i',$datac2["fcat_master"]);
        $stmtc2x->execute();
        $resultc2x = $stmtc2x->get_result();
        $numrowc2x = $resultc2x->num_rows;
        if($numrowc2x>0) {
          $datac2x = $resultc2x->fetch_assoc();
          $name = $datac2x["fcat_name"];
        }
      }
    }
	}
	return $name;
}

function getFairMenuType($fct_id) {
  global $mysqli;
  $type = 0;

	$sql2 = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fct_id = ? limit 1 ";
	$stmt2 = $mysqli->prepare($sql2);
	if($stmt2) {
		$stmt2->bind_param('i',$fct_id);
		$stmt2->execute();
		$result2 = $stmt2->get_result();
		$numrow2 = $result2->num_rows;
    if($numrow2>0) {
      $datac2 = $result2->fetch_assoc();
      $type = $datac2["fct_cms_type"];
    }
	}
	return $type;
}

function getFairHotel($fair_id) {
  global $mysqli;

  $sqlc = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fair_id = ? and b.fcat_status = '1' and a.fct_status = '1' and a.fcat_id = '16' limit 1 ";
  $stmtc = $mysqli->prepare($sqlc);
  if($stmtc) {
    $stmtc->bind_param('i',$fair_id);
    $stmtc->execute();
    $resultc = $stmtc->get_result();
    $numrowc = $resultc->num_rows;
    if($numrowc>0) {
      $datac = $resultc->fetch_assoc();
      return $datac["fct_id"];
    } else {
      return 0;
    }
  }
  return 0;
}

function getTagFair($id) {
  global $mysqli;

  $fairitem = "";

  $sqlc = "select * from tt_fair_group where fair_group_id = ? ";
  $stmtc = $mysqli->prepare($sqlc);
  if($stmtc) {
    $stmtc->bind_param('i',$id);
    $stmtc->execute();
    $resultc = $stmtc->get_result();
    $numrowc = $resultc->num_rows;
    if($numrowc>0) {
      $datac = $resultc->fetch_assoc();
      $fairitem = $datac["fair_group_abb"];
    }
  }
  return $fairitem;
}

function getTagFairShow($id) {
  global $mysqli;
  $sqlc = "select * from tt_fair_group where fair_group_id = ? ";
  $stmtc = $mysqli->prepare($sqlc);
  if($stmtc) {
    $stmtc->bind_param('i',$id);
    $stmtc->execute();
    $resultc = $stmtc->get_result();
    $numrowc = $resultc->num_rows;
    if($numrowc>0) {
      $datac = $resultc->fetch_assoc();
      ?>
      <span><button type="button" class="btn btn-tag-active"><?=$datac["fair_group_abb"]?></button></span>
      <?
    }
  }
}

function getTagMaster($dataitem) {
  global $mysqli;
  $fairnewitem = "";
  if($dataitem!="") {
    $fairitem = explode('|',$dataitem);
    for($z=0;$z<=count($fairitem);$z++) {
      $fairitemthis = (int)$fairitem[$z];
      if($fairitemthis>0) {
        if($fairnewitem=="") {
           $fairnewitem = $fairitem[$z];
        } else {
          $fairnewitem = $fairnewitem.",".$fairitem[$z];
        }
      }
    }
  }


  if($fairnewitem!="") {
    $sqlc = "select * from tt_tag_master where tag_id in (".$fairnewitem.") order by tag_name ASC ";
    $stmtc = $mysqli->prepare($sqlc);
    if($stmtc) {
      $stmtc->execute();
      $resultc = $stmtc->get_result();
      $numrowc = $resultc->num_rows;
      if($numrowc>0) {
        while($datac = $resultc->fetch_assoc()) {
          ?>
          <span><button type="button" class="btn btn-tag-active"><?=$datac["tag_name"]?></button></span>
          <?
        }
      }
    }
  }
}

function getTagMasterNews($dataitem) {
  global $mysqli;
  $fairnewitem = "";
  if($dataitem!="") {
    $fairitem = explode('|',$dataitem);
    for($z=0;$z<=count($fairitem);$z++) {
      $fairitemthis = (int)$fairitem[$z];
      if($fairitemthis>0) {
        if($fairnewitem=="") {
           $fairnewitem = $fairitem[$z];
           break;
        }
      }
    }
  }


  if($fairnewitem!="") {
    $sqlc = "select * from tt_tag_master where tag_id = ? ";
    $stmtc = $mysqli->prepare($sqlc);
    if($stmtc) {
      $stmtc->bind_param('i',$fairnewitem);
      $stmtc->execute();
      $resultc = $stmtc->get_result();
      $numrowc = $resultc->num_rows;
      if($numrowc>0) {
        while($datac = $resultc->fetch_assoc()) {
          $fairnewitem = $datac["tag_name"];
        }
      }
    }
  }

  return $fairnewitem;
}

function imageresize($source, $destination, $width = 0, $height = 0, $crop = false, $cropcenter = false, $quality = 80) {
    $quality = $quality ? $quality : 80;
    $image = imagecreatefromstring($source);
    if ($image) {
        // Get dimensions
        $w = imagesx($image);
        $h = imagesy($image);
        //die(json_encode(array('width' => $w, 'height' => $h)));
        if (($width && $w > $width) || ($height && $h > $height)) {
            $ratio = $w / $h;
            if (($ratio >= 1 || $height == 0) && $width && !$crop) {
                $new_height = $width / $ratio;
                $new_width = $width;
            } elseif ($crop && $ratio <= ($width / $height)) {
                $new_height = $width / $ratio;
                $new_width = $width;
            } else {
                $new_width = $height * $ratio;
                $new_height = $height;
            }
        } else {
            $new_width = $w;
            $new_height = $h;
        }
        $x_mid = $new_width * .5;  //horizontal middle
        $y_mid = $new_height * .5; //vertical middle
        // Resample
        error_log('height: ' . $new_height . ' - width: ' . $new_width);
        $new = imagecreatetruecolor(floor($new_width), floor($new_height));

        imagealphablending($new, false);
        imagesavealpha($new, true);
        $transparent = imagecolorallocatealpha($new, 255, 255, 255, 127);
        imagefilledrectangle($new, 0, 0, $new_width, $new_height, $transparent);

        $x = 0;
        if ($new_width > $new_height) {
            //$new_height = $new_height *8;
        } else {
            //$x = -$new_width * 7;
            //$new_width = $new_width *8;
        }
        imagecopyresampled($new, $image, 0, 0, $x, 0, $new_width, $new_height, $w, $h);

		$w = imagesx($new);
		$h = imagesy($new);


        // Crop
        if ($crop) {

          if($width>$w) {
            $width = $w;
          }

          if($height>$h) {
            $height = $h;
          }

            $crop = imagecreatetruecolor($width ? $width : $new_width, $height ? $height : $new_height);

            imagealphablending($crop, false);
            imagesavealpha($crop, true);
            $transparent = imagecolorallocatealpha($crop, 255, 255, 255, 127);
            imagefilledrectangle($crop, 0, 0, $width ? $width : $new_width, $height ? $height : $new_height, $transparent);

            if($cropcenter) {
              imagecopyresampled($crop, $new, 0, 0, ($x_mid - ($width * .5)), ($y_mid - ($height * .5)), $width, $height, $width, $height);
            } else {
              imagecopyresampled($crop, $new, 0, 0, ($x_mid - ($width * .5)), 0, $width, $height, $width, $height);
            }

            //($y_mid - ($height * .5))
        }
        // Output
        // Enable interlancing [for progressive JPEG]
        imageinterlace($crop ? $crop : $new, true);
        $dext = strtolower(pathinfo($destination, PATHINFO_EXTENSION));
        if ($dext == '') {
            $dext = $ext;
            $destination .= '.' . $ext;
        }
        switch ($dext) {
            case 'jpeg':
            case 'jpg':
                imagejpeg($crop ? $crop : $new, $destination, $quality);
                break;
            case 'png':
                $pngQuality = ($quality - 100) / 11.111111;
                $pngQuality = round(abs($pngQuality));
                imagepng($crop ? $crop : $new, $destination, $pngQuality);
                break;
            case 'gif':
                imagegif($crop ? $crop : $new, $destination);
                break;
        }
        
      // @imagedestroy($image);
      // @imagedestroy($new);
      // @imagedestroy($crop);
       
    }
}






function getEarlyFair($year) {
  global $mysqli;

  $focusfairid = 0;
  $todaydatedata = date("Y-m-d");
  $sqlfair = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and b.fair_flag = '1' and c.fair_group_status = '1' and a.fair_year = ? and a.fair_event_start >= ? order by a.fair_event_start ASC limit 1 ";
  $stmtfair = $mysqli->prepare($sqlfair);
  $stmtfair->bind_param('is',$year,$todaydatedata);
  $stmtfair->execute();
  $resultfair = $stmtfair->get_result();
  $numrowfair = $resultfair->num_rows;
  if($numrowfair>0) {
    $datafair = $resultfair->fetch_assoc();
    $focusfairid = $datafair["fair_id"];
  } else {
    $sqlfair2 = " select * from tt_fair_list a left join tt_fair_group_list b on a.fair_id=b.fair_id  left join tt_fair_group c on b.fair_group_id=c.fair_group_id where a.fair_status = '1' and b.fair_flag = '1' and c.fair_group_status = '1' and a.fair_year = ? and a.fair_event_start <= ? order by a.fair_event_start DESC limit 1 ";
    $stmtfair2 = $mysqli->prepare($sqlfair2);
    $stmtfair2->bind_param('is',$year,$todaydatedata);
    $stmtfair2->execute();
    $resultfair2 = $stmtfair2->get_result();
    $numrowfair2 = $resultfair2->num_rows;
    if($numrowfair2>0) {
      $datafair2 = $resultfair2->fetch_assoc();
      $focusfairid = $datafair2["fair_id"];
    }
  }

  return $focusfairid;
}

function getSSOLoginShortName($ssoid) {
  global $mysqli;

  $nname = "";

  $sql = "select * from tt_sso_login where sso_id = ? ";
  $stmt = $mysqli->prepare($sql);
  $stmt->bind_param('s',$ssoid);
  $stmt->execute();
  $result = $stmt->get_result();
  $numrow = $result->num_rows;
  if($numrow>0) {
    $data = $result->fetch_assoc();

    if($data["sso_company_en"]!="") {
      $nname = $data["sso_company_en"];
    } else {
      if($data["sso_company_th"]!="") {
        $nname = $data["sso_company_th"];
      } else {
        if($data["sso_name_en"]!="") {
          $nname = $data["sso_name_en"];
        } else {
          $nname = $data["sso_name_th"];
        }
      }
    }

    $nname = mb_strtoupper(mb_substr($nname, 0, 1),'UTF-8');
  }

  return $nname;
}

function saveLogActivity($log_type,$admin_id,$ref_id,$log_action) {
  global $mysqli;

  $ip = get_real_ip();
  $sqlgl = "insert into tt_activity_log (log_type,admin_id,admin_date,admin_ip,ref_id,log_action) values (?,?,now(),?,?,?) ";
  $stmtgl = $mysqli->prepare($sqlgl);
  if($stmtgl) {
    $stmtgl->bind_param('iisis',$log_type,$admin_id,$ip,$ref_id,$log_action);
    $stmtgl->execute();
  }
}


?>
