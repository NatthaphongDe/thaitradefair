<style>
#circle {

width: 80px; /* ความกว้าง */

height: 80px; /* ความสูง */

background: #022644; /* สี */

-moz-border-radius: 70px;

-webkit-border-radius: 70px;

border-radius: 70px;

color: white;

line-height: 2.5;

}

#scroll {
        width: 100%;
        height: 300px;
        overflow: scroll; /* showing scrollbars */

  }

  ::-webkit-scrollbar {
      width: 0px;
}
</style>
<nav class="navbar navbar-fixed-top white-bg" role="navigation">
    <div class="navbar-header">
    <a class="navbar-minimalize minimalize-styl-2 btn btn-default" href="#"><i class="fa fa-bars"></i> </a>
    </div>
    <ul class="nav navbar-top-links navbar-right">
    <li class="dropdown">
        <?php
            $ex = explode(" ",$_SESSION["admin_name"]);
            $name_a = mb_substr($ex[0], 0, 1);
            $name_b = mb_substr($ex[1], 0, 1);

            // echo $_SESSION["fgid"];
        $rolelvlogin = $datarr["role_lv"];
        $sqlr = " select * from tt_admin where id = ?";
        			$stmtr = $mysqli->prepare($sqlr);
        		    $stmtr->bind_param('i',$_SESSION["id"]);
        		    $stmtr->execute();
                    $resultr = $stmtr->get_result();
        		    $datar_admin = $resultr->fetch_assoc();

                    $sqlra = "select * from tt_admin_role where role_id = ?";
        			$stmtra = $mysqli->prepare($sqlra);
        		    $stmtra->bind_param('i',$datar_admin["user_type"]);
        		    $stmtra->execute();
                    $resultra = $stmtra->get_result();
        		    $datar_admina = $resultra->fetch_assoc();
                    if($datar_admina["role_lv"] == 1) {
                        $status = "superadmin";
                    } else if($datar_admina["role_lv"] == 2) {
                        $status = "staff";
                    } else if($datar_admina["role_lv"] == 3) {
                        $status = "fairadmin";
                    }

                if($rolelvlogin == 2) {
                    $sql_side = " select * from tt_fair_group_admin a left join tt_fair_group b on a.fair_group_id=b.fair_group_id where a.admin_id = ? and b.fair_group_status != '9' and b.fair_group_id = ?";
        			$stmtr_side = $mysqli->prepare($sql_side);
        		    $stmtr_side->bind_param('ii',$_SESSION["id"],$_SESSION["fgid"]);
        		    $stmtr_side->execute();
                    $resultr_side = $stmtr_side->get_result();
        		    $datar_side = $resultr_side->fetch_assoc();
                    $side = $datar_side["fair_group_name_th"];
                } else if($rolelvlogin == 3) {

                    // echo $_SESSION["frgid"];
                    // echo "|";
                    // echo $_SESSION["frlid"];
                    // $_SESSION["frgid"] = $gid;

                    $sqlr = "select * from tt_fair_list_admin a left join tt_fair_list b on a.fair_id=b.fair_id where a.admin_id = ? and b.fair_status != '9' and b.fair_id = ?";
                    $stmtr = $mysqli->prepare($sqlr);
                    $stmtr->bind_param('ii',$_SESSION["id"],$_SESSION["frlid"]);
                    $stmtr->execute();
                    $resultr = $stmtr->get_result();
                    $datar_side = $resultr->fetch_assoc();
                    $side = $datar_side["fair_name"];
                }
         ?>
        <a class="dropdown-toggle" data-toggle="dropdown" href="#" style="height: 50px; margin-left: -4%;">
            <font color="#1C5FA1"><?=$_SESSION["admin_name"];?></font> <font size="1"> <?=$status;?> | <?=$side;?></font> <span class="caret"></span></a>
            <p>
        <ul class="dropdown-menu" style="width: 300px;">
        <li>
            <br>
            <center><div id="circle"><b><font size="6"><?=$name_a.$name_b;?></font></b></div><br>
            <?=$_SESSION["admin_name"];?><br>
            <?=$status;?> | <?=$datar_admin["email"];?> </center><br>
            </li>

        <?php if($rolelvlogin == 2) { ?>
        <ul class="list-group" id="scroll">

            <?php  $sqlr = " select * from tt_fair_group_admin a left join tt_fair_group b on a.fair_group_id=b.fair_group_id where a.admin_id = ? and b.fair_group_status != '9' ";
        			$stmtr = $mysqli->prepare($sqlr);
        		    $stmtr->bind_param('i',$_SESSION["id"]);
        		    $stmtr->execute();
                    $resultr = $stmtr->get_result();
        		    $numrowr = $resultr->num_rows;
        		      while($datar = $resultr->fetch_assoc()) {
                        ?>

                <li class="list-group-item" style="cursor: pointer;" onclick="window.location='admin_role.php?method=fgroup&id=<?=$datar["fair_group_id"]?>';"><img class="img-circle" alt="Cinque Terre" src="<?=ROOTPATHDOMAIN?><?=$datar["fair_group_logo_path"]?>" width="15%"> &nbsp;&nbsp;<?=$datar["fair_group_name_th"]?></li>

          <?php } ?>
            </ul>

        <?php } else if($rolelvlogin == 3) { ?>

            <ul class="list-group" id="scroll">
            <?php
             $sqlr = "select * from tt_fair_list_admin a left join tt_fair_list b on a.fair_id=b.fair_id where a.admin_id = ? and b.fair_status != '9' ";
             $stmtr = $mysqli->prepare($sqlr);
             $stmtr->bind_param('i',$_SESSION["id"]);
             $stmtr->execute();
             $resultr = $stmtr->get_result();
             $numrowr = $resultr->num_rows;
             while($datar = $resultr->fetch_assoc()) {


            // $sqlrl = " select * from tt_fair_group_list a left join tt_fair_group b on a.fair_group_id=b.fair_group_id where a.fair_id = ? and a.fair_flag != '9' and b.fair_group_status != '9' ";
        		// 	$stmtr = $mysqli->prepare($sqlr);
        		//     $stmtr->bind_param('i',$datar["fair_id"]);
        		//     $stmtr->execute();
            //         $resultr = $stmtr->get_result();
        		//     $numrowr = $resultr->num_rows;
        		//       while($datar = $resultr->fetch_assoc()) {

                        $sqlrl = " select * from tt_fair_group_list a left join tt_fair_group b on a.fair_group_id=b.fair_group_id where a.fair_id = ? and a.fair_flag != '9' and b.fair_group_status != '9' ";
              			$stmtrl = $mysqli->prepare($sqlrl);
            		    $stmtrl->bind_param('i',$datar["fair_id"]);
            		    $stmtrl->execute();
                        $resultrl = $stmtrl->get_result();
            		    $numrowrl = $resultrl->num_rows;
            		    $datarl = $resultrl->fetch_assoc();
                        ?>

                <li class="list-group-item" style="cursor: pointer;" onclick="window.location='fair_admin_role.php?method=flist&id=<?=$datar["fair_id"]?>';"><img class="img-circle" alt="Cinque Terre" src="<?=ROOTPATHDOMAIN?><?=$datarl["fair_group_logo_path"]?>" width="15%"> &nbsp;&nbsp;<?=$datar["fair_name"]?></li>

          <?php } ?>

            </ul>

        <?php } ?>

            <li><center><br><a class="btn btn-default" href="logout.php" style="color:#1C5FA1;"><i class="fa fa-sign-out"></i> Sing out</a></center><br>
        </li>
        </ul>
      </li>
    <!-- <li>
          <?php //echo $_SESSION["admin_name"]; ?>
        </span>
    </li> -->
    </ul>
</nav>
