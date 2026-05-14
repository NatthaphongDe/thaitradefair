<li class="active" style="position:relative;">
  <div style="position:absolute; z-index:1; top:8px; left:35px; display:none;">
    <span class="text-danger"><i class="fa fa-bell" aria-hidden="true"></i></span>
  </div>
  <a><span><img src="asset/icon_fairmanager.png" height="12"></span>&nbsp;&nbsp;&nbsp;<span class="nav-label">Fair Management</span>  <span class="fa arrow"></span></a>
  <ul class="nav nav-second-level">

        <li <?php if($_REQUEST['show']=="fair_master_list" or $_REQUEST['show']=="fair_master_add" or $_REQUEST['show']=="fair_master_edit"){echo "class='active'";}?>><a href="home.php?show=fair_master_list">• Fair Master</a></li>
        <li <?php if($_REQUEST['show']=="fair_list" or $_REQUEST['show']=="fair_add" or $_REQUEST['show']=="fair_edit" or $_REQUEST['show']=="fair_information" or $_REQUEST['show']=="fair_menu_list"){echo "class='active'";}?>><a href="home.php?show=fair_list">• Fair Manager</a></li>

  </ul>
</li>

<li class="active" style="position:relative;">
  <div style="position:absolute; z-index:1; top:8px; left:35px; display:none;">
    <span class="text-danger"><i class="fa fa-bell" aria-hidden="true"></i></span>
  </div>
  <a href="home.php?show=why_list"><span><img src="asset/icon_whythailand.png" height="12"></span>&nbsp;&nbsp;&nbsp;<span class="nav-label">Why Thailand</span> </a>
</li>

<li class="active" style="position:relative;">
  <div style="position:absolute; z-index:1; top:8px; left:35px; display:none;">
    <span class="text-danger"><i class="fa fa-bell" aria-hidden="true"></i></span>
  </div>
  <a href="home.php?show=news_list"><span><img src="asset/icon_news.png" height="12"></span>&nbsp;&nbsp;&nbsp;<span class="nav-label">News Management</span> </a>
</li>

<li class="active" style="position:relative;">
  <div style="position:absolute; z-index:1; top:8px; left:35px; display:none;">
    <span class="text-danger"><i class="fa fa-bell" aria-hidden="true"></i></span>
  </div>
  <a href="home.php?show=gallery_list"><span><img src="asset/icon_gallery.png" height="12"></span>&nbsp;&nbsp;&nbsp;<span class="nav-label">Gallery Management</span> </a>
</li>

<li class="active" style="position:relative;">
  <div style="position:absolute; z-index:1; top:8px; left:35px; display:none;">
    <span class="text-danger"><i class="fa fa-bell" aria-hidden="true"></i></span>
  </div>
  <a><span><img src="asset/icon_contact.png" height="12"></span>&nbsp;&nbsp;&nbsp;<span class="nav-label">Contact Information</span>  <span class="fa arrow"></span></a>
  <ul class="nav nav-second-level">

        <li <?php if($_REQUEST['show']=="contact_list"){echo "class='active'";}?>><a href="home.php?show=contact_list">• Contact Form</a></li>
        <li <?php if($_REQUEST['show']=="fair_contact_list"){echo "class='active'";}?>><a href="home.php?show=fair_contact_list">• Fair Contact Form</a></li>

  </ul>
</li>

<li class="active" style="position:relative;">
  <div style="position:absolute; z-index:1; top:8px; left:35px; display:none;">
    <span class="text-danger"><i class="fa fa-bell" aria-hidden="true"></i></span>
  </div>
  <a><span><img src="asset/icon_admin.png" height="12"></span>&nbsp;&nbsp;&nbsp;<span class="nav-label">Users & Role</span>  <span class="fa arrow"></span></a>
  <ul class="nav nav-second-level">

        <li <?php if($_REQUEST['show']=="admin_list" or $_REQUEST['show']=="admin_add" or $_REQUEST['show']=="admin_edit"){echo "class='active'";}?>><a href="home.php?show=admin_list">• Setting Users</a></li>
        <li <?php if($_REQUEST['show']=="role_list" or $_REQUEST['show']=="role_add" or $_REQUEST['show']=="role_edit"){echo "class='active'";}?>><a href="home.php?show=role_list">• Setting Role</a></li>

  </ul>
</li>

<li class="active" style="position:relative;">
  <div style="position:absolute; z-index:1; top:8px; left:35px; display:none;">
    <span class="text-danger"><i class="fa fa-bell" aria-hidden="true"></i></span>
  </div>
  <a><span><img src="asset/icon_report.png" height="12"></span>&nbsp;&nbsp;&nbsp;<span class="nav-label">Report</span> </a>
</li>

<li class="active" style="position:relative;">
  <div style="position:absolute; z-index:1; top:8px; left:35px; display:none;">
    <span class="text-danger"><i class="fa fa-bell" aria-hidden="true"></i></span>
  </div>
  <a><span><img src="asset/icon_log.png" height="12"></span>&nbsp;&nbsp;&nbsp;<span class="nav-label">Log History</span>  <span class="fa arrow"></span></a>
  <ul class="nav nav-second-level">

        <li><a>• Online Meeting Log</a></li>
        <li><a>• Live Chat Log</a></li>

  </ul>
</li>
