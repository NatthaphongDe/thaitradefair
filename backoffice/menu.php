<nav class="navbar-default navbar-static-side" role="navigation" >
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                     </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                    <span class="clear text-center">
                    	<img src="asset/logo.png" >
                     </span>
                     </a>
                </div>
                <div class="logo-element">
                    <span class="fa fa-tasks login_icon"></span>
                </div>
            </li>

      <?
      $sqlrrr = " select * from tt_admin a left join tt_admin_role b on a.user_type=b.role_id where a.id = ?  ";
      $stmtrr = $mysqli->prepare($sqlrrr);
		  if($stmtrr) {
		    $stmtrr->bind_param('i',$_SESSION["id"]);
		    $stmtrr->execute();
				$resultrr = $stmtrr->get_result();
		    $numrowrr = $resultrr->num_rows;
		    if($numrowrr>0) {
		      $datarr = $resultrr->fetch_assoc();

          if($datarr["role_lv"]==1) {
            include 'menu_permission_1.php';
          }

          if($datarr["role_lv"]==2) {
            include 'menu_permission_2.php';
          }

          if($datarr["role_lv"]==3) {
            include 'menu_permission_3.php';
          }

				}
		  }

      //include 'menu_permission.php';

      ?>


            <!-- <li>
                <a href="logout.php"><i class="fa fa-sign-out"></i> <span class="nav-label">Logout</span></a>
            </li> -->
        </ul>


    </div>
</nav>
