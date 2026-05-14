<?php


class main
{

  function __construct()
  {

    global $conn, $image_not_available;

    $this->conn  = $conn;
    $this->image_not_available  = $image_not_available;
    // dd($this->conn);
  }

  public function __destruct()
  {
    // mysqli_close($this->conn);
    // $this->conn->close();
  }




  function escape_string($arr = '')
  {
    $res = array();
    foreach ($arr as $key => $value) {
      $res[$key]  = $this->conn->real_escape_string($value);
    }
    return $res;
  }

  public function select($select = array(), $table, $where = array(), $sort = null, $sort_type = "desc", $limit = null, $showSql = false)
  {

    $sql =  "SELECT ";

    if (count($select) > 0) {
      $index_select = 0;
      foreach ($select as $key => $value) {
        if ($index_select > 0) {
          $sql .= ", ";
        }
        $sql .= $value;
        $index_select++;
      }
    } else {
      $sql .= "*";
    }

    $sql .= " FROM " . $table;

    $index_where = 0;
    foreach ($where as $key => $value) {
      if ($index_where > 0) {
        $sql .= " AND $key = '$value'";
      } else {
        $sql .= " WHERE $key = '$value'";
      }
      $index_where++;
    }

    if ($sort) {
      $sql .= " ORDER BY " . $sort . " " . $sort_type;
    }

    if ($limit) {
      $sql .= " LIMIT " . $limit;
    }

    if ($showSql) {
      return $sql;
    }

    $query = $this->conn->query($sql);

    $result = new stdClass;
    $result->num_rows = $query->num_rows;
    $result->data = array();

    while ($res = $query->fetch_assoc()) {
      $result->data[] = $res;
    }

    return $result;
  }


  public function insert($table, $data = array(), $showSql = false)
  {
    $fields = "";
    $values = "";
    $i = 1;
    foreach ($data as $key => $val) {
      if ($i != 1) {
        $fields .= ", ";
        $values .= ", ";
      }
      $fields .= "$key";
      if ($val == "NOW()") {
        $values .= "$val";
      } else {
        $values .= "'$val'";
      }

      $i++;
    }
    // echo
    $sql = "INSERT INTO $table ($fields) VALUES ($values)";

    if ($showSql) {
      return $sql;
    }

    $result = $this->conn->query($sql);

    if ($result) {
      $last_id = $this->conn->insert_id;
      return $last_id;
    } else {
      return false;
    }
  }

  public function update($table, $data, $where, $showSql = false)
  {
    $sql = "UPDATE $table SET";

    $index_set = 0;
    foreach ($data as $key => $value) {
      if ($index_set > 0) {
        $sql .= ",";
      }
      $sql .= " $key = '$value'";
      $index_set++;
    }

    $index_where = 0;
    foreach ($where as $key => $value) {
      if ($index_where > 0) {
        $sql .= " AND $key = '$value'";
      } else {
        $sql .= " WHERE $key = '$value'";
      }
      $index_where++;
    }

    if ($showSql) {
      return $sql;
    }

    $query = $this->conn->query($sql);
    $affected_rows = $this->conn->affected_rows;

    if ($affected_rows > 0) {
      return true;
    } else {
      return false;
    }
  }


  public function delete($table, $where = array(), $showSql = false)
  {
    $sql = "DELETE FROM $table ";

    $i = 0;
    foreach ($where as $key => $value) {
      if ($i == 0) {
        $sql .= " WHERE ";
      } else {
        $sql .= " AND ";
      }
      $sql .= $key . " = '" . $value . "'";
      $i++;
    }

    if ($showSql) {
      return $sql;
    }

    $query = $this->conn->query($sql);
    return $query;
  }

  public function date_en($value = '')
  {
    $Dated = date("d", strtotime($value));
    $Datem = date("m", strtotime($value));

    $arrtext = array(
      "01"  =>  "January",
      "02"  =>  "February",
      "03"  =>  "March",
      "04"  =>  "April",
      "05"  =>  "May",
      "06"  =>  "June",
      "07"  =>  "July",
      "08"  =>  "August",
      "09"  =>  "September",
      "10"  =>  "October",
      "11"  =>  "November",
      "12"  =>  "December"
    );


    $DateY = date("Y", strtotime($value)) + 543;
    $newDate = $Dated . " " . $arrtext[$Datem] . " " . $DateY;


    return $newDate;
  }

  public function set_url($value = '')
  {
    $d = str_replace(' & ', 'and', $value);
    $d = str_replace(' / ', '-', $d);
    $d = strtolower(str_replace(' ', '', $d));
    return $d;
  }

  public function file_exists($path = '', $size = '')
  {

    $new_path = substr($path, 1);;
    if (file_exists($new_path)) {
      return $path;
    } else {
      if ($size == '' || $size == 's') {
        return $this->image_not_available;
      } else {
        return $this->image_not_available;
      }
    }
  }
}
