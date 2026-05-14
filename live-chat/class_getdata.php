<?php
// include 'class_main.php';

class getdata extends main
{


  public function Get_product($img = '', $search = '', $byid = array(), $category_status = '', $img_product = '')
  {

    if ($search != '') {
      $where = " AND product_category_name_en LIKE '%" . trim($search) . "%' ";
    }

    // if($byid!=''){
    if (count($byid) > 0) {
      $byid =  implode(',', $byid);
      $where .= " AND  product_category_id in ($byid) ";
    }
    // }
    if ($category_status == '') {
      $ss = ' AND product_category_status = 0 ';
    } else {
    }

    if ($img_product == 1 && $search != '') {
      // echo
      $sqltag     = " SELECT * FROM product_img WHERE 1  AND tag LIKE '%" . trim($search) . "%' GROUP BY product_category_id ";
      $resulttag  = $this->conn->query($sqltag);
      $arrx = array();
      while ($data_tag = $resulttag->fetch_assoc()) {
        array_push($arrx, $data_tag['product_category_id']);
      }
      if (count($arrx) > 0) {
        $arrx =  implode(',', $arrx);
        $wherex = " OR  product_category_id in ($arrx) ";
      }
    }


    $all_data = array();
    // echo
    $sql     = " SELECT * FROM product_category WHERE 1  $ss $where $wherex ORDER BY product_category_name_en ASC  ";
    $result  = $this->conn->query($sql);
    $ro      = 0;
    while ($data_res = $result->fetch_assoc()) {
      $d['id']        = $data_res['product_category_id'];
      $d['number']    = $ro;
      $d['name']      = $data_res['product_category_name_en'];
      $d['color']     = $data_res['product_category_color'];
      $d['path_img']     = $this->file_exists('/data/product_category/floorplan/' . $data_res['product_category_id'] . '/s/' . $data_res['img_floorplan']);
      $d['path_img_l']   = $this->file_exists('/data/product_category/floorplan/' . $data_res['product_category_id'] . '/l/' . $data_res['img_floorplan']);
      $d['path_img_original']   = $this->file_exists('/data/product_category/floorplan/' . $data_res['product_category_id'] . '/original/' . $data_res['img_floorplan']);
      $d['title_page']     = $data_res['product_category_page'];

      // $d['url-name']  = str_replace(' & ', 'and', $d['name']);
      // $d['url-name']  = str_replace(' / ', '-', $d['url-name']);
      // $d['url-name']  = strtolower(str_replace(' ', '', $d['url-name']));


      $d['url-name']  =  $this->set_url($d['name']);
      $d['row']       =  $data_res['product_category_id'];
      $d['video']     =  $data_res['product_category_video'];
      if ($img == 1) {
        $arr_img = array();
        $sqlimg = "SELECT * FROM `product_category_img` WHERE `product_category_id` = $d[id] ";
        $resultimg = $this->conn->query($sqlimg);
        while ($data_resimg = $resultimg->fetch_assoc()) {
          $data_resimg['path_img'] = '/data/product_category/' . $data_resimg['product_category_id'] . '/s/' . $data_resimg['product_category_img_name'];
          array_push($arr_img, $data_resimg);
        }
        $d['img']   = $arr_img;
      }

      if ($img_product == 1) {
        $arr_img = array();
        $sqlimg = " SELECT p.company_id,p.product_img_name,c.code,p.product_img_id
                        FROM `product_img` as p
                        LEFT JOIN company as c on (p.company_id = c.company_id)
                        WHERE p.product_category_id = $d[id]
                        AND c.company_status = 0
                        AND c.company_enable = 0 ";
        $resultimg = $this->conn->query($sqlimg);
        while ($data_resimg = $resultimg->fetch_assoc()) {
          $data_resimg_1 = array();
          $data_resimg_1['id'] = $data_resimg['company_id'];
          $data_resimg_1['name'] = $data_resimg['product_img_name'];
          $data_resimg_1['code'] = $data_resimg['code'];
          $data_resimg_1['img_id'] = $data_resimg['product_img_id'];

          $path_img = '../../data/product/' . $data_resimg['company_id'] . '/s/' . $data_resimg['product_img_name'];

          $path_info               = getimagesize($path_img);
          $data_resimg['pathimg']  = $path;
          if ($path_info[0] > $path_info[1]) {
            $data_resimg_1['w-h'] = 'w';
          } else {
            $data_resimg_1['w-h'] = 'h';
          }
          array_push($arr_img, $data_resimg_1);
        }
        $d['img_product']   = $arr_img;
      }
      array_push($all_data, $d);
      $ro++;
    }
    return $all_data;
  }



  public function Get_zone($page = '')
  {


    $all_data = array();
    if ($page == 'Showcase' || $page == 'home') {

      $sql = " SELECT * FROM `zone` WHERE `zone_show` = 0 ";
    } else if ($page == 'DitpServices') {
      $sql = " SELECT * FROM `zone` WHERE `zone_show` = 1 ORDER BY `zone`.`zone_id` DESC ";
    } else {
      $sql = " SELECT * FROM zone ";
    }

    $result = $this->conn->query($sql);
    $i = 0;
    while ($data_res = $result->fetch_assoc()) {
      $i++;
      $d = array();
      $d['id']              = $data_res['zone_id'];
      $d['name']            = $data_res['zone_name'];
      $d['desc']            = $data_res['zone_desc'];
      $d['video']           = $data_res['zone_video_mobile'];
      $d['video_mobile']    = $data_res['zone_video_mobile'];
      $d['img_floorplan']   = $data_res['zone_img_floorplan'];
      $d['path_img']        = '/data/zone/floorplan/' . $data_res['zone_id'] . '/s/' . $data_res['zone_img_floorplan'];
      $d['path_img_l']      = '/data/zone/floorplan/' . $data_res['zone_id'] . '/original/' . $data_res['zone_img_floorplan'];

      $d['position']        = $data_res['zone_position'];
      $d['zone_show']        = $data_res['zone_show'];
      $d['row']             = $i;

      // $data_res['url-name'] = str_replace(' & ', 'and', $data_res['zone_name']);
      // $data_res['url-name'] = str_replace(' / ', '-', $data_res['url-name']);
      // $data_res['url-name'] = strtolower(str_replace(' ', '', $data_res['url-name']));
      $d['url-name']  =  $this->set_url($data_res['zone_name']);

      // $d['url-name']        = $data_res['url-name'];

      $arr_img = array();
      $sqlimg = " SELECT * FROM `zone_img` WHERE `zone_id` = $data_res[zone_id] AND zone_img_status = 0";
      $resultimg = $this->conn->query($sqlimg);
      while ($data_resimg = $resultimg->fetch_assoc()) {
        $data_resimg['path_img']  = '/data/zone/gallery/' . $data_resimg['zone_id'] . '/s/' . $data_resimg['zone_img_name'];
        $data_resimg['path_img_l']   = '/data/zone/gallery/' . $data_resimg['zone_id'] . '/l/' . $data_resimg['zone_img_name'];
        array_push($arr_img, $data_resimg);
      }
      if (count($arr_img) > 0) {
        $d['img'] = $arr_img;
      }
      array_push($all_data, $d);
    }
    return $all_data;
  }

  public function Get_office($value = '')
  {
    $all_data = array();
    $sql = " SELECT * FROM office ";
    $result = $this->conn->query($sql);
    $data_res = $result->fetch_assoc();

    $time       = time();
    $startDate  = strtotime($data_res['startDate']);
    $endDate    = strtotime($data_res['endDate']);

    if ($startDate <= $now  && $endDate >= $now) {
      $data_res['status'] = 1;
    }

    $data_res['popup_path'] = '/data/popup/' . $data_res['id'] . '/s/' . $data_res['popup_img'];
    return $data_res;
  }

  public function Get_blog($limit = '', $search = '', $by_id = '', $all = '')
  {
    $lim = '';
    if ($all == 'All') {
    } else {
      if ($limit != '') {
        $limitx  = $limit . ', ';
      }

      $lim = " LIMIT  $limitx 5 ";
    }

    if ($search != '') {
      $where = " AND (
                    blog_title LIKE '%" . trim($search) . "%'
                    or blog_desc LIKE '%" . trim($search) . "%'
                    or blog_detail LIKE '%" . trim($search) . "%'
                    ) ";
    }


    if ($by_id != '') {
      $where1 = " AND  blog_id = '$by_id' ";
    }

    $date = date('Y-m-d H:i:s');
    $and_1 = " AND blog_date_start <= '$date' AND blog_date_end >= '$date' ";
    $all_data = array();
    // echo
    $sql = " SELECT * FROM blog WHERE blog_status = 0 $where1 $where $and_1 ORDER BY `blog_date_start` DESC $lim  ";
    $result = $this->conn->query($sql);
    while ($data_res = $result->fetch_assoc()) {
      $d['blog_id']      = $data_res['blog_id'];
      $d['blog_title']   = $data_res['blog_title'];
      $d['blog_desc']    = $data_res['blog_desc'];
      $d['blog_detail']  = $data_res['blog_detail'];
      // $d['blog_detail']  = str_replace('https://stayinstylebangkok.com','',$d['blog_detail']);
      $d['blog_date']    = $this->date_en($data_res['blog_date_start']);
      $d['blog_img']     = $data_res['blog_img'];
      $d['blog_date_old']  = $data_res['blog_date_start'];

      array_push($all_data, $d);
    }
    return $all_data;
  }




  public function Get_exhibitor($limit = '', $search = '')
  {
    $all_data = array();

    if ($limit != '') {
      $limitx  = $limit . ', ';
    }

    if ($search['type_search'] == 1) {
      if ($search['search_text'] != '') {
        $where = " AND (company_name LIKE '%" . trim($search[search_text]) . "%'
                            or company_address LIKE '%" . trim($search[search_text]) . "%'
                            or company_desc LIKE '%" . trim($search[search_text]) . "%'
                            or hall LIKE '%" . trim($search[search_text]) . "%'
                            or booth_no LIKE '%" . trim($search[search_text]) . "%'
                            or company_name_seo LIKE '%" . trim($search[search_text]) . "%'
                          ) ";
      }
      if ($search['year'] != '') {
        $where .= " AND  event_date LIKE '%" . trim($search[year]) . "%'  ";
      }
    } else  if ($search['type_search'] == 2) {
      if ($search['Booth_No'] != '') {
        $where .= " AND ( booth_no LIKE '%" . trim($search[Booth_No]) . "%' ) ";
      }



      if ($search['Product'] != '') {
        $data_Company =  $this->get_company_company_byid($search['Product']);
        $data_Company =  implode(',', $data_Company);
        if ($data_Company != '') {
          $where .= "AND  com.company_id in ($data_Company) ";
        }
      }


      if ($search['Brands'] != '') {
        // echo $search['Brands'];
        $da_Brands =  $this->get_company_brands_byid($search['Brands']);
        $da_Brands =  implode(',', $da_Brands);
        if ($da_Brands != '') {
          $where .= "AND  com.company_id in ($da_Brands) ";
        }
        // print_r($da_Brands);
      }



      if ($search['Company'] != '') {
        $where .= " AND (company_name LIKE '%" . trim($search[Company]) . "%' ) ";
      }


      if ($search['Product'] != '' && $search['Brands'] == '') {
        if ($search['Booth_No'] == '' && $search['Company'] == '' && $data_Company == '') {
          return $all_data;
        }
      } else if ($search['Product'] == '' && $search['Brands'] != '') {
        if ($search['Booth_No'] == '' && $search['Company'] == '' && $da_Brands == '') {
          return $all_data;
        }
      } else if ($search['Product'] == '' && $search['Brands'] != '') {
        if ($search['Booth_No'] == '' && $search['Company'] == '' && $data_Company == '' && $da_Brands == '') {
          return $all_data;
        }
      }
    }
    // echo
    $sql = " SELECT
              com.company_id
              ,com.company_name as company_name
              ,com.booth_no as booth_no
              ,com.hall
              ,com.booth_no
              ,com.event_date
              ,com.code
        FROM `company` as com
        WHERE 1 $where
        AND company_status = 0
        AND company_enable = 0
        ORDER BY com.company_name ASC
        LIMIT $limitx $search[limit_page]  ";
    $result = $this->conn->query($sql);
    while ($data_res = $result->fetch_assoc()) {

      $sql_com = "  SELECT * FROM `company_cate` AS cc
                          LEFT JOIN product_category AS pc
                          ON        (cc.product_category_id = pc.product_category_id)
                          WHERE     cc.company_id = $data_res[company_id]
                          ORDER BY  id ASC
                          LIMIT     1  ";
      $resul_com = $this->conn->query($sql_com);
      $data_com = $resul_com->fetch_array();





      $data_res['url-name']  =  $this->set_url($data_com['product_category_name_en']);
      $arr_img = array();
      // echo
      // $sqlimg = " SELECT * FROM `company_img` WHERE `company_id` = $data_res[company_id]  ORDER BY product_category_id DESC";
      $sqlimg = " SELECT * FROM `company_img` WHERE `company_id` = $data_res[company_id]  ORDER BY company_default DESC, user_id DESC";


      $resultimg = $this->conn->query($sqlimg);
      while ($data_resimg = $resultimg->fetch_assoc()) {
        // echo "<br>";
        $data_resimg['path_img'] = '/data/company/' . $data_resimg['company_id'] . '/m/' . $data_resimg['company_img_name'];
        // echo "<br>";
        array_push($arr_img, $data_resimg);
      }
      if (count($arr_img) > 0) {
        $data_res['img'] = $arr_img;
      }
      array_push($all_data, $data_res);
    }
    return $all_data;
  }



  public function Get_exhibitor_num($search = '')
  {
    if ($search['type_search'] == 1) {
      if ($search['search_text'] != '') {
        $where = " AND (company_name LIKE '%" . trim($search[search_text]) . "%'
                          or company_address LIKE '%" . trim($search[search_text]) . "%'
                          or company_desc LIKE '%" . trim($search[search_text]) . "%'
                          or hall LIKE '%" . trim($search[search_text]) . "%'
                          or booth_no LIKE '%" . trim($search[search_text]) . "%'
                          or company_name_seo LIKE '%" . trim($search[search_text]) . "%'
                        ) ";
      }
      if ($search['year'] != '') {
        $where .= " AND  event_date LIKE '%" . trim($search[year]) . "%'  ";
      }
    } else  if ($search['type_search'] == 2) {
      if ($search['Booth_No'] != '') {
        $where .= " AND ( booth_no LIKE '%" . trim($search[Booth_No]) . "%' ) ";
      }

      if ($search['Brands'] != '') {
        $da_Brands =  $this->get_company_brands_byid($search['Brands']);
        $da_Brands =  implode(',', $da_Brands);
        if ($da_Brands != '') {
          $where .= "AND  company_id in ($da_Brands) ";
        }
      }

      if ($search['Product'] != '') {
        $data_Company =  $this->get_company_company_byid($search['Product']);
        $data_Company =  implode(',', $data_Company);
        if ($data_Company != '') {
          $where .= "AND  company_id in ($data_Company) ";
        }
      }


      if ($search['Company'] != '') {
        $where .= " AND (company_name LIKE '%" . trim($search[Company]) . "%' ) ";
      }


      if ($search['Product'] != '' && $search['Brands'] == '') {
        if ($search['Booth_No'] == '' && $search['Company'] == '' && $data_Company == '') {
          return $all_data;
        }
      } else if ($search['Product'] == '' && $search['Brands'] != '') {
        if ($search['Booth_No'] == '' && $search['Company'] == '' && $da_Brands == '') {
          return $all_data;
        }
      } else if ($search['Product'] == '' && $search['Brands'] != '') {
        if ($search['Booth_No'] == '' && $search['Company'] == '' && $data_Company == '' && $da_Brands == '') {
          return $all_data;
        }
      }
    }
    $res = $this->conn->query("SELECT * FROM `company` WHERE 1 $where     AND company_status = 0   AND company_enable = 0 ");
    return $res->num_rows;
  }

  public function Get_blog_num($search = '')
  {
    if ($search != '') {
      $where = " AND (
                  blog_title LIKE '%" . trim($search) . "%'
                  or blog_desc LIKE '%" . trim($search) . "%'
                  or blog_detail LIKE '%" . trim($search) . "%'
                  ) ";
    }
    $date = date('Y-m-d H:i:s');
    $and_1 = " AND blog_date_start <= '$date' AND blog_date_end >= '$date' ";

    $res = $this->conn->query("SELECT * FROM `blog` WHERE blog_status = 0 $where $and_1 ");
    return $res->num_rows;
  }


  public function Get_company($search = '', $searchtext = '')
  {
    $wh = '';
    if ($search != '') {
      $wh .= " AND code = $search ";
    }
    if ($searchtext != '') {
      $wh .= " AND company_name LIKE '%" . trim($searchtext) . "%' ";
    }
    $all_data = array();
    $sql = "  SELECT * FROM company
                  WHERE 1 $wh
                  AND company_status = 0
                  AND company_enable = 0 ";
    $result = $this->conn->query($sql);
    while ($data_res = $result->fetch_assoc()) {
      unset($data_res['company_password']);
      unset($data_res['create_date']);
      array_push($all_data, $data_res);
    }
    return $all_data;
  }

  public function Get_company_by_id($company_id, $type)
  {
    $all_data = array();
    if($type == '1'){
      $sql = "SELECT * FROM tt_exhibitor_list WHERE com_taxno = '".$company_id."'";
      $result = $this->conn->query($sql);
      while ($data_res = $result->fetch_assoc()) {
        $data_res['room_t'] = '1';
        $data_res['company_tax_id'] = $data_res["com_taxno"];
        $data_res['company_name'] = $data_res["com_name"];
        array_push($all_data, $data_res);
      }
    }else if($type == '2'){
      $sql = "SELECT * FROM tt_exportor_list WHERE User_ID = '".$company_id."'";
      $result = $this->conn->query($sql);
      while ($data_res = $result->fetch_assoc()) {
        $data_res['room_t'] = '2';
        $data_res['company_tax_id'] = $data_res["User_ID"];
        if($data_res["Corporate_Name_EN"] != ""){
          $data_res['company_name'] = $data_res["Corporate_Name_EN"];
        }else{
          $data_res['company_name'] = $data_res["Corporate_Name_TH"];
        }
        array_push($all_data, $data_res);
      }
    }
    return $all_data;
  }

  public function get_chat_room($user_id)
  {
    $result = array();

    $sql = "SELECT * FROM tt_chat_history WHERE user_id = " . $user_id;
    $stmt = $this->conn->query($sql);
    $all_data = array();
    while ($data_res = $stmt->fetch_assoc()) {
      array_push($all_data, $data_res['room_id']);
    }

    if (count($all_data) > 0) {
      $sql = "SELECT * FROM tt_chat_history cl LEFT JOIN tt_chat_room cr ON cl.room_id = cr.room_id WHERE cl.room_id in (";
      foreach ($all_data as $key => $value) {
        $sql .= $value;
        if (count($all_data) != ($key + 1)) {
          $sql .= ", ";
        }
      }
      $sql .= ") AND cl.user_type = 0 ORDER BY cr.room_date DESC;";
      $stmt = $this->conn->query($sql);
      while ($data_res = $stmt->fetch_assoc()) {
        $result[$data_res['user_id']] = $data_res['room_key'];
      }
    }
    return $result;
  }

  public function get_chat_log($user_id)
  {

    $company = array();

    $sql = "SELECT * FROM tt_chat_history WHERE user_id = " . $user_id;
    $result = $this->conn->query($sql);
    $all_data = array();
    while ($data_res = $result->fetch_assoc()) {
      array_push($all_data, $data_res['room_id']);
    }


    if (count($all_data) > 0) {
      $sql = "SELECT * FROM tt_chat_history cl LEFT JOIN tt_chat_room cr ON cl.room_id = cr.room_id WHERE cl.room_id in (";
      foreach ($all_data as $key => $value) {
        $sql .= $value;
        if (count($all_data) != ($key + 1)) {
          $sql .= ", ";
        }
      }
      $sql .= ") AND cl.user_type = 0 ORDER BY cr.room_date DESC;";
      $result = $this->conn->query($sql);
      $all_data = array();
      while ($data_res = $result->fetch_assoc()) {
        array_push($all_data, ['userid' => $data_res['user_id'], 'room_type' => $data_res['room_type'], 'chat_time' => $data_res['room_date']]);
      }


      foreach ($all_data as $key => $value) {
        if($value["room_type"] == "1"){
          $sql = "SELECT * FROM tt_exhibitor_list WHERE com_taxno = " . $value["userid"];
          $result = $this->conn->query($sql);
          while ($data_res = $result->fetch_assoc()) {
            $data_res['room_t'] = '1';
            $data_res['company_tax_id'] = $data_res["com_taxno"];
            $data_res['company_name'] = $data_res["com_name"];
            $data_res['chat_time'] = $value["chat_time"];
            array_push($company, $data_res);
          }
        }else if($value["room_type"] == "2"){
          $sql = "SELECT * FROM tt_exportor_list WHERE User_ID = " . $value["userid"];
          $result = $this->conn->query($sql);
          while ($data_res = $result->fetch_assoc()) {
            $data_res['room_t'] = '2';
            $data_res['company_tax_id'] = $data_res["User_ID"];
            $data_res['company_name'] = $data_res["Corporate_Name_EN"];
            $data_res['chat_time'] = $value["chat_time"];
            array_push($company, $data_res);
          }
        }

      }
    }
    return $company;
  }


  public function Get_brands($search_byid = array())
  {

    if (count($search_byid) > 0) {
      $search_byid =  implode(',', $search_byid);
      $where = " AND  brands_id in ($search_byid) ";
    }

    $all_data = array();
    $sql = " SELECT * FROM brands WHERE 1 $where ORDER BY `brands`.`brands_name` ASC ";
    $result = $this->conn->query($sql);
    while ($data_res = $result->fetch_assoc()) {
      array_push($all_data, $data_res);
    }
    return $all_data;
  }


  public function get_company_brands_byid($value = '')
  {
    $all_data = array();
    $sql = " SELECT * FROM `company_brands` WHERE `brands_id` = $value ";
    $result = $this->conn->query($sql);
    while ($data_res = $result->fetch_assoc()) {
      array_push($all_data, $data_res['company_id']);
    }
    return $all_data;
  }

  public function get_company_company_byid($value = '')
  {
    $all_data = array();
    $sql = " SELECT * FROM `company_cate` WHERE `product_category_id` = $value ";
    $result = $this->conn->query($sql);
    while ($data_res = $result->fetch_assoc()) {
      array_push($all_data, $data_res['company_id']);
    }
    return $all_data;
  }


  public function get_company_company_byid_1($value = '')
  {
    $all_data = array();
    $sql = " SELECT * FROM `company_cate` WHERE `company_id` = $value ";
    $result = $this->conn->query($sql);
    while ($data_res = $result->fetch_assoc()) {
      array_push($all_data, $data_res['product_category_id']);
    }
    return $all_data;
  }




  public function get_contact_us_byid($search = '')
  {
    $all_data = array();
    $sql = " SELECT * FROM `contact_us` WHERE `company_id` = $search ";
    $result = $this->conn->query($sql);
    while ($data_res = $result->fetch_assoc()) {
      array_push($all_data, $data_res);
    }
    return $all_data;
  }



  public function get_company_img($search = '')
  {

    if ($search != '') {
      $wh = " AND company_id = $search ";
    }
    $arr_img = array();
    $sqlimg = " SELECT * FROM `company_img` WHERE 1 $wh ORDER BY company_default DESC";
    $resultimg = $this->conn->query($sqlimg);
    while ($data_resimg = $resultimg->fetch_assoc()) {
      $data_resimg['path_img'] = '/data/company/' . $data_resimg['company_id'] . '/s/' . $data_resimg['company_img_name'];

      array_push($arr_img, $data_resimg);
    }
    return $arr_img;
  }




  public function get_company_cate($search = '')
  {

    if ($search != '') {
      $wh = " AND company_id = $search ";
    }
    $arr_img = array();
    // echo
    $sqlimg = " SELECT * FROM `product_img` WHERE 1 $wh ORDER BY product_default DESC";
    $resultimg = $this->conn->query($sqlimg);
    while ($data_resimg = $resultimg->fetch_assoc()) {

      $data_resimg['path_img'] = '/data/product/' . $data_resimg['company_id'] . '/s/' . $data_resimg['product_img_name'];
      $path_info = 'data/product/' . $data_resimg['company_id'] . '/s/' . $data_resimg['product_img_name'];
      $path_info               = getimagesize($path_info);
      if ($path_info[0] > $path_info[1]) {
        $ck_img = 'exhibitor-w';
      } else {
        $ck_img = 'exhibitor-h';
      }
      $data_resimg['h-w'] = $ck_img;

      array_push($arr_img, $data_resimg);
    }
    return $arr_img;
  }





  public function get_product_category_img($limit = '', $search = '')
  {

    if ($limit != '') {
      $limitx  = $limit . ', ';
    }

    if ($search['cat_id'] != '') {
      $wh = " AND p.product_category_id = $search[cat_id] ";
    }

    if ($search['search_text'] != '') {
      // $wh .= " AND c.company_name LIKE '%".trim($search[search_text])."%' ";

      $wh .= " AND (
                      c.company_name LIKE '%" . trim($search[search_text]) . "%'
                      or c.company_name_seo LIKE '%" . trim($search[search_text]) . "%'
                      or p.tag LIKE '%" . trim($search[search_text]) . "%'
                      ) ";
    }

    $arr_img = array();
    // echo
    $sqlimg = " SELECT
                        p.product_img_id
                        ,p.company_id
                        ,p.product_category_id
                        ,p.product_img_name
                        ,p.tag
                        ,c.company_name
                        ,c.company_name_seo
                        ,c.code
                        ,pc.product_category_color as color
                        ,pc.product_category_name_en
                      FROM `product_img` AS p
                      LEFT JOIN company AS c
                      ON (p.company_id = c.company_id)
                      LEFT JOIN product_category AS pc
                      ON (p.product_category_id = pc.product_category_id)
                      WHERE 1 $wh
                      AND company_status = 0
                      AND company_enable = 0
                      ORDER BY c.company_name $search[order]
                      LIMIT $limitx $search[limit_page] ";
    $resultimg = $this->conn->query($sqlimg);
    while ($data_resimg = $resultimg->fetch_assoc()) {
      $path                    = '/data/product/' . $data_resimg['company_id'] . '/s/' . $data_resimg['product_img_name'];
      $path_info               = '../../data/product/' . $data_resimg['company_id'] . '/s/' . $data_resimg['product_img_name'];
      $path_info               = getimagesize($path_info);
      $data_resimg['pathimg']  = $path;
      if ($path_info[0] > $path_info[1]) {
        $ck_img = 'w-img';
      } else {
        $ck_img = 'h-img';
      }
      $data_resimg['class']    = $ck_img;
      $url                     =  $this->set_url($data_resimg['product_category_name_en']);
      $data_resimg['url']      = '/exhibitor/' . $url . '/' . $data_resimg['code'];
      $data_resimg['id']      = $data_resimg['product_img_id'];
      array_push($arr_img, $data_resimg);
    }
    return $arr_img;
  }


  public function get_product_category_img_num($search = '')
  {





    if ($search['cat_id'] != '') {
      $wh = " AND p.product_category_id = $search[cat_id] ";
    }

    if ($search['search_text'] != '') {
      $wh .= " AND (
                  c.company_name LIKE '%" . trim($search[search_text]) . "%'
                  or c.company_name_seo LIKE '%" . trim($search[search_text]) . "%'
                  or p.tag LIKE '%" . trim($search[search_text]) . "%'
                          ) ";
    }

    $sqlimg = " SELECT
                              p.company_id
                              ,p.product_category_id
                              ,p.product_img_name
                              ,c.company_name
                              ,c.code
                              ,pc.product_category_color as color
                              ,pc.product_category_name_en
                            FROM `product_img` AS p
                            LEFT JOIN company AS c
                            ON (p.company_id = c.company_id)
                            LEFT JOIN product_category AS pc
                            ON (p.product_category_id = pc.product_category_id)
                            WHERE 1 $wh
                            AND company_status = 0
                            AND company_enable = 0
                            ORDER BY c.company_name ASC  ";
    $res = $this->conn->query($sqlimg);
    return $res->num_rows;
  }



  public function Get_product_banner($id = '')
  {

    if ($id != '') {
      $where = " AND  product_category_id = '$id' ";
    }

    $all_data = array();
    // echo
    $sql = " SELECT * FROM product_category WHERE 1  $where ORDER BY product_category_name_en ASC ";
    $result = $this->conn->query($sql);
    $ro = 0;
    while ($data_res = $result->fetch_assoc()) {
      $ro++;
      $d['id']        = $data_res['product_category_id'];
      $d['name']      = $data_res['product_category_name_en'];
      $d['color']     = $data_res['product_category_color'];
      $d['title_page']     = $data_res['product_category_page'];

      if ($data_res['img_floorplan'] != '') {
        $d['path_img']    = '/data/product_category/floorplan/' . $data_res['product_category_id'] . '/l/' . $data_res['img_floorplan'];
      }
      $d['url-name']  =  $this->set_url($d['name']);
      $d['row']       =  $data_res['product_category_id'];
      $d['video']     =  $data_res['product_category_video'];
      // if($img==1){
      $arr_img = array();
      $sqlimg = "SELECT * FROM `product_category_img` WHERE `product_category_id` = $d[id] ";
      $resultimg = $this->conn->query($sqlimg);
      while ($data_resimg = $resultimg->fetch_assoc()) {
        $data_resimg['path_img']     = '/data/product_category/' . $data_resimg['product_category_id'] . '/l/' . $data_resimg['product_category_img_name'];

        array_push($arr_img, $data_resimg);
      }
      $d['img']   = $arr_img;
      // }
      array_push($all_data, $d);
    }
    return $all_data;
  }


  public function all_search()
  {


    $arr_search = array();



    // product_img
    $arrcheck = array();
    $sqlsearch = " SELECT tag,company_id,product_img_name FROM `product_img` WHERE `tag` != '' GROUP BY tag ORDER BY `tag` ASC ";
    $resultsearch = $this->conn->query($sqlsearch);
    while ($data_ressearch = $resultsearch->fetch_assoc()) {
      $all = array();
      // $all['title'] = $data_ressearch['tag'];

      $newdata = explode(',', $data_ressearch['tag']);
      foreach ($newdata as $key => $value) {
        $value = trim($value);
        $value_strtolower = strtolower($value);
        if (count($arrcheck) == 0) {
          array_push($arrcheck, $value_strtolower);
          $all['value'] = $value;
          $all['url'] = '/Product/ALL?text=' . $value;
          array_push($arr_search, $all);
        } else {
          if (!in_array($value_strtolower, $arrcheck)) {
            array_push($arrcheck, $value_strtolower);
            $all['value'] = $value;
            $all['url'] = '/Product/ALL?text=' . $value;
            array_push($arr_search, $all);
          }
        }
      }
      // $all['img'] =  '/data/product/'.$data_ressearch['company_id'].'/s/'.$data_ressearch['product_img_name'];
    }




    $sqlsearch = "  SELECT com.company_name,com.code,com.company_name_seo,com.company_id,ci.company_img_name
                          FROM company as com
                          LEFT join company_img as ci on (com.company_id = ci.company_id)
                          WHERE com.company_status = 0
                          AND com.company_enable = 0
                          ORDER BY com.company_name ASC ";
    $resultsearch = $this->conn->query($sqlsearch);
    while ($data_ressearch = $resultsearch->fetch_assoc()) {
      $all = array();
      $all['value'] = $data_ressearch['company_name'];
      $all['url'] = '/exhibitor/' . $data_ressearch['code'];
      // $all['img'] = '/data/company/'.$data_ressearch['company_id'].'/s/'.$data_ressearch['company_img_name'];

      array_push($arr_search, $all);
      if ($data_ressearch['company_name_seo'] != '') {
        $newdata = explode(',', $data_ressearch['company_name_seo']);
        foreach ($newdata as $key => $value) {
          $value = trim($value);
          $value_strtolower = strtolower($value);
          if (count($arrcheck) == 0) {
            array_push($arrcheck, $value_strtolower);
            $all['value'] = $value;
            $all['url'] = '/Product/ALL?text=' . $value;
            array_push($arr_search, $all);
          } else {
            if (!in_array($value_strtolower, $arrcheck)) {
              array_push($arrcheck, $value_strtolower);
              $all['value'] = $value;
              $all['url'] = '/Product/ALL?text=' . $value;
              array_push($arr_search, $all);
            }
          }
        }

        // $all = array();
        // $all['value'] = $data_ressearch['company_name_seo'];
        // $all['url'] = '/Product/ALL?text='.$data_ressearch['company_name_seo'];
        // $all['img'] = '/data/company/'.$data_ressearch['company_id'].'/s/'.$data_ressearch['company_img_name'];

        // array_push($arr_search,$all);
      }
    }


    $sqlsearch = " SELECT * FROM `product_category` WHERE `product_category_name_en` != 'All' AND product_category_status = 0  ORDER BY `product_category_name_en` ASC ";
    $resultsearch = $this->conn->query($sqlsearch);
    while ($data_ressearch = $resultsearch->fetch_assoc()) {
      $all = array();
      $all['value'] = $data_ressearch['product_category_name_en'];
      $all['url'] = '/Product/' . $this->set_url($data_ressearch['product_category_name_en']);
      array_push($arr_search, $all);
    }

    // echo
    $sqlsearch = " SELECT z.zone_name,img.zone_img_name,img.zone_id FROM zone as z LEFT JOIN zone_img as img on z.zone_id = img.zone_id WHERE img.zone_img_status = 0 GROUP BY z.zone_id ";
    $resultsearch = $this->conn->query($sqlsearch);
    while ($data_ressearch = $resultsearch->fetch_assoc()) {
      $all = array();
      // $all['title'] = $data_ressearch['zone_name'];
      $all['value'] = $data_ressearch['zone_name'];
      // $all['img']   = '/data/zone/gallery/'.$data_ressearch['zone_id'].'/l/'.$data_ressearch['zone_img_name'];
      $all['url'] = '/Showcase/' . $this->set_url($data_ressearch['zone_name']);
      array_push($arr_search, $all);
    }



    return $arr_search;
  }


  public function Get_blog_oth($data, $id = '')
  {
    $date = date('Y-m-d H:i:s');
    $and_1 = " AND blog_date_start <= '$date' AND blog_date_end >= '$date' ";

    $all_data = array();

    $sql = " SELECT * FROM blog WHERE blog_status = 0 AND blog_date_start > '$data' $and_1 ORDER BY `blog_date_start` DESC limit 3  ";
    $result = $this->conn->query($sql);
    $check = 0;
    if ($result->num_rows > 0) {
      while ($data_res = $result->fetch_assoc()) {
        $check++;
        $d['blog_id']      = $data_res['blog_id'];
        $d['blog_title']   = $data_res['blog_title'];
        $d['blog_desc']    = $data_res['blog_desc'];
        $d['blog_date']    = $this->date_en($data_res['blog_date_start']);
        $d['blog_img']     = $data_res['blog_img'];
        array_push($all_data, $d);
      }
    }

    if ($check < 3) {
      // echo
      $sql = " SELECT * FROM blog WHERE blog_status = 0 AND blog_date_start < '$data' $and_1 ORDER BY `blog_date_start` DESC limit 3  ";
      $result = $this->conn->query($sql);
      if ($result->num_rows > 0) {
        while ($data_res = $result->fetch_assoc()) {
          $check++;
          $d['blog_id']      = $data_res['blog_id'];
          $d['blog_title']   = $data_res['blog_title'];
          $d['blog_desc']    = $data_res['blog_desc'];
          $d['blog_detail']  = $data_res['blog_detail'];
          $d['blog_date']    = $this->date_en($data_res['blog_date_start']);
          $d['blog_img']     = $data_res['blog_img'];
          array_push($all_data, $d);
          if ($check == 3) {
            break;
          }
        }
      }
    }

    return $all_data;
  }


  function get_banner($value = '')
  {
    $date = date('Y-m-d H:i:s');
    $and_1 = " AND startDate <= '$date' AND endDate >= '$date' ";

    $sqlsearch = "  SELECT * FROM `banner` WHERE `status` = 0  $and_1 ORDER BY pin DESC,startDate ASC ";
    $resultsearch = $this->conn->query($sqlsearch);
    $arr_search = array();
    while ($data_ressearch = $resultsearch->fetch_assoc()) {
      $data_ressearch['path'] = '/data/banner/' . $data_ressearch['id'] . '/original/' . $data_ressearch['url_img'];
      $data_ressearch['path_mobile'] = '/data/banner_mobile/' . $data_ressearch['id'] . '/original/' . $data_ressearch['url_img_mobile'];
      array_push($arr_search, $data_ressearch);
    }

    return $arr_search;
    //return    $sqlsearch;

  }



  public function Get_country($value = '')
  {
    if ($value != '') {
      $where = " WHERE id = $value ";
    }
    $sqlsearch = "  SELECT * FROM `country` $where ORDER BY country_name ASC ";
    $resultsearch = $this->conn->query($sqlsearch);
    $arr_search = array();
    while ($data_ressearch = $resultsearch->fetch_assoc()) {
      if ($value != '') {
        return $data_ressearch;
      }
      array_push($arr_search, $data_ressearch);
    }

    return $arr_search;
  }
  public function Get_businesstype($value = '')
  {
    if ($value != '') {
      $where = " WHERE businesstype_id = $value ";
    }
    $sqlsearch = "  SELECT * FROM `businesstype` $where ORDER BY businesstype_name ASC ";
    $resultsearch = $this->conn->query($sqlsearch);
    $arr_search = array();
    while ($data_ressearch = $resultsearch->fetch_assoc()) {
      if ($value != '') {
        return $data_ressearch;
      }
      array_push($arr_search, $data_ressearch);
    }

    return $arr_search;
  }



  public function Get_InterestedProducts($value = '')
  {
    if ($value != '') {
      $where = " WHERE Interesting_id = $value ";
    }
    $sqlsearch      = "  SELECT * FROM `InterestedProducts` $where ORDER BY name ASC ";
    $resultsearch   = $this->conn->query($sqlsearch);
    $arr_search     = array();
    while ($data_ressearch = $resultsearch->fetch_assoc()) {
      if ($value != '') {
        return $data_ressearch;
      }
      array_push($arr_search, $data_ressearch);
    }
    return $arr_search;
  }





  public function addcompany($value = '')
  {
    $sqlsearch = "  UPDATE company  SET website_open = website_open + 1  WHERE code = $value ";
    $resultsearch = $this->conn->query($sqlsearch);
    return $resultsearch;
  }

  public function all_all_search($text = '')
  {


    $all_data = array();
    if ($text != '') {
      $where_1 = " AND ( blog_title LIKE '%" . trim($text) . "%'
                            or blog_desc LIKE '%" . trim($text) . "%'
                            or blog_detail LIKE '%" . trim($text) . "%'
                          ) ";
    }

    $res = $this->conn->query("SELECT blog_id,blog_title,blog_desc,blog_detail FROM `blog` WHERE blog_status = 0 $where_1 ");
    if ($res->num_rows > 0) {
      while ($data_res = $res->fetch_assoc()) {
        $res_assoc = array();
        $res_assoc['path']  = '/About/' . $data_res['blog_id'];
        $res_assoc['title'] = $data_res['blog_title'];
        array_push($all_data, $res_assoc);
      }
    }



    if ($text != '') {
      $where_2 = " AND ( code LIKE '%" . trim($text) . "%'
                            or company_name LIKE '%" . trim($text) . "%'
                            or company_address LIKE '%" . trim($text) . "%'
                            or website LIKE '%" . trim($text) . "%'
                            or company_desc LIKE '%" . trim($text) . "%'
                            or hall LIKE '%" . trim($text) . "%'
                            or booth_no LIKE '%" . trim($text) . "%'
                            or company_name_seo LIKE '%" . trim($text) . "%'
                            or contact LIKE '%" . trim($text) . "%'
                          ) ";
    }
    $sql = "  SELECT code,company_name,company_address,website,company_desc,hall,booth_no,company_name_seo,contact
                  FROM `company`
                  WHERE 1 $where_2
                  AND company_status = 0
                  AND company_enable = 0 ";
    $res = $this->conn->query($sql);
    if ($res->num_rows > 0) {
      while ($data_res = $res->fetch_assoc()) {
        $res_assoc = array();
        $res_assoc['path']  = '/exhibitor/' . $data_res['code'];
        $res_assoc['title'] = $data_res['company_name'];
        array_push($all_data, $res_assoc);
      }
    }




    if ($text != '') {
      $where_3 = " AND ( product_category_name_en LIKE '%" . trim($text) . "%' ) ";
    }
    $sql = "SELECT product_category_name_en FROM `product_category` WHERE product_category_status = 0 $where_3 ";
    $res = $this->conn->query($sql);
    if ($res->num_rows > 0) {
      while ($data_res = $res->fetch_assoc()) {
        $res_assoc = array();
        $res_assoc['path']  = '/Product/' . $this->set_url($data_res['product_category_name_en']);  // seturl
        $res_assoc['title'] = $data_res['product_category_name_en'];
        array_push($all_data, $res_assoc);
      }
    }



    if ($text != '') {
      $where_4 = " AND ( zone_name LIKE '%" . trim($text) . "%'  OR zone_desc LIKE '%" . trim($text) . "%' ) ";
    }
    $sql = "SELECT zone_name,zone_desc FROM `zone` WHERE 1 $where_4 ";
    $res = $this->conn->query($sql);
    if ($res->num_rows > 0) {
      while ($data_res = $res->fetch_assoc()) {
        $res_assoc = array();
        $res_assoc['path']  = '/Showcase/' . $this->set_url($data_res['zone_name']);  // seturl
        $res_assoc['title'] = $data_res['zone_name'];
        array_push($all_data, $res_assoc);
      }
    }



    if ($text != '') {
      $where_5 = " AND ( tag LIKE '%" . trim($text) . "%' ) ";
    }
    $sql = " SELECT tag FROM `product_img` WHERE `tag` != ''  $where_5 GROUP BY tag ORDER BY `tag` ASC ";
    $res = $this->conn->query($sql);
    if ($res->num_rows > 0) {
      while ($data_res = $res->fetch_assoc()) {
        $res_assoc = array();
        $res_assoc['title'] = $data_res['tag'];
        $res_assoc['path'] = '/Product/ALL?text=' . $data_res['tag'];
        array_push($all_data, $res_assoc);
      }
    }


    return $all_data;
  }


  public function Get_product_brand($value = '')
  {
    $all_data = array();
    $sql = " SELECT * FROM `company_brands` as com left join brands as br on (com.brands_id = br.brands_id) WHERE `company_id` = $value ";
    $result = $this->conn->query($sql);
    while ($data_res = $result->fetch_assoc()) {
      array_push($all_data, $data_res);
    }
    return $all_data;
  }

  public function get_spam_contact($email = '')
  {
    $res = $this->conn->query("SELECT * FROM `contact` WHERE contact_deprive = 1 AND `contact_email` = '$email' ");
    return $res->num_rows;
  }

  public function get_visit()
  {
    $res = $this->conn->query("SELECT * FROM `ip_visit` WHERE ip = '" . $_SERVER['REMOTE_ADDR'] . "'");
    return $res->num_rows;
  }
  public function questionnaire()
  {
    $res = $this->conn->query("SELECT * FROM `questionnaire`  WHERE ip = '" . $_SERVER['REMOTE_ADDR'] . "'");
    return $res->num_rows;
  }

  public function get_profile($user_id)
  {
    $sql = " SELECT user_id, email, company_name, fullname, telephone, country_id, address FROM `user_account` WHERE user_id = " . $user_id;
    $result = $this->conn->query($sql);
    $data_res = $result->fetch_assoc();
    return $data_res;
  }

  public function get_profile_image($user_id)
  {
    $sql = "SELECT * FROM user_img WHERE user_id = '" . $user_id . "'";
    $result = $this->conn->query($sql);
    $data_res = $result->fetch_assoc();
    return $data_res;
  }

  public function insert_profile($table, $params, $show_sql = false)
  {
    $fields = "";
    $values = "";
    $i = 1;
    foreach ($params as $key => $val) {
      if ($i != 1) {
        $fields .= ", ";
        $values .= ", ";
      }
      $fields .= "$key";
      $values .= "'$val'";
      $i++;
    }

    $sql = "INSERT INTO $table ($fields) VALUES ($values)";

    if ($show_sql) {
      return $sql;
    }

    $result = $this->conn->query($sql);
    $last_id = $this->conn->insert_id;

    $sqlx = "SELECT email FROM user_account WHERE user_id = '" . $last_id . "' ";
    $query = $this->conn->query($sqlx);

    while ($res = $query->fetch_assoc()) {
      $return = $res;
    }

    return $return;
  }

  public function get_countryp($country_id = null)
  {
    $sql = "SELECT id,country_name FROM country";

    if ($country_id) {
      $sql .= " WHERE id = " . $country_id;
    }

    $query = $this->conn->query($sql);

    while ($res = $query->fetch_assoc()) {
      $return[] = $res;
    }

    return $return;
  }

  public function Get_Set_Time_Slot($create_by)
  {
    $sql = "SELECT * FROM Set_Time_Slot WHERE status_slot = 0 ";

    if ($create_by) {
      $sql .= " AND create_by = " . $create_by;
    }

    $query = $this->conn->query($sql);

    $return = $query->fetch_assoc();

    return $return;
  }

  public function Get_sup_time_slot($id_m, $showSql = false)
  {
    $sql = "SELECT * FROM sup_time_slot WHERE status_slot = 0";
    if ($id_m) {
      $sql .= " AND id_m = " . $id_m;
    }

    if ($showSql) {
      return $sql;
    }

    $query = $this->conn->query($sql);
    $return = [];
    while ($res = $query->fetch_assoc()) {
      $return[] = $res;
    }

    return $return;
  }

  public function get_user_account($email, $password = null)
  {
    $sql = "SELECT * FROM user_account";

    if ($email) {
      $sql .= " WHERE email = '" . $email . "'";
    }

    if ($password) {
      if ($email) {
        $sql .= " AND ";
      } else {
        $sql .= " WHERE ";
      }
      $sql .= "re_password = '" . $password . "'";
    }

    $query = $this->conn->query($sql);
    $res = $query->fetch_assoc();
    return $res;
  }

  function verify_email($email)
  {
    $sql = "UPDATE user_account SET status_user = 1 WHERE email = '" . $email . "'";
    $query = $this->conn->query($sql);
    return $query;
  }

  public function getRequest_meeting($where, $showSql = false)
  {

    $sql = "SELECT * FROM request_meeting ";
    $sql .= "WHERE request_datetime >= NOW()";

    $fields = '';
    $i = 0;
    foreach ($where as $key => $value) {
      $sql .= " AND ";
      $sql .= $key . " = '" . $value . "'";
    }

    if ($showSql) {
      return $sql;
    }
    $query = $this->conn->query($sql);

    while ($res = $query->fetch_assoc()) {
      $return[] = $res;
    }

    return $return;
  }

  public function get_meeting_message($user_id, $user_company)
  {

    $sql = "SELECT massage FROM message_meeting WHERE user_company = " . $user_company . " AND user_id = " . $user_id;

    $query = $this->conn->query($sql);

    while ($res = $query->fetch_assoc()) {
      $return[] = $res;
    }

    return $return;
  }

  // SELECT c.company_id, c.code, c.company_name, ci.company_img_id, ci.company_img_name FROM company c LEFT JOIN company_img ci ON c.company_id = ci.company_id WHERE c.company_status = 0 AND c.company_enable = 0 AND ci.company_default = 1
  public function getToken($company_id, $showSql = false)
  {
    // $sql = "SELECT login_notification_token FROM user_login WHERE login_user_type = '0' AND login_user_id = '" . $company_id . "' AND login_platform != 'web' group by login_notification_token ORDER BY login_logintime desc";

    $sql = "SELECT login_user_id, login_notification_token, login_status FROM user_login WHERE login_user_type = '0' AND login_user_id = '" . $company_id . "' ORDER BY login_logintime desc";

    if ($showSql) {
      return $sql;
    }

    $query = $this->conn->query($sql);

    $return = array();
    while ($res = $query->fetch_assoc()) {
      if ($res['login_status'] == 1) {
        $return[] = $res['login_notification_token'];
      }
    }

    return $return;
  }
}
