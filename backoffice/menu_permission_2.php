
<? if(getRolePageMenu($datarr["role_id"],1)==1) { ?>
<li class="active">
  <a><img src="asset/icon_fairmanager.png" height="12">&nbsp;&nbsp;&nbsp;<span class="nav-label">Fair Management</span>  <span class="fa arrow"></span></a>
  <ul class="nav nav-second-level">

        <? if(getRolePageMenu($datarr["role_id"],9)==1) { ?>
        <li <?php if($_REQUEST['show']=="fair_master_list" or $_REQUEST['show']=="fair_master_add" or $_REQUEST['show']=="fair_master_edit"){echo "class='active'";}?>><a href="home.php?show=fair_master_list">• Fair Master</a></li>
        <? } ?>

        <? if(getRolePageMenu($datarr["role_id"],10)==1) { ?>
        <li <?php if($_REQUEST['show']=="adfair_list" or $_REQUEST['show']=="adfair_add" or $_REQUEST['show']=="adfair_edit" or $_REQUEST['show']=="adfair_information" or $_REQUEST['show']=="adfair_menu_list" or $_REQUEST['show']=="adfair_cover" or $_REQUEST['show']=="adfair_cover_add" or $_REQUEST['show']=="adfair_cover_edit" or $_REQUEST['show']=="adfair_token"){echo "class='active'";}?>><a href="home.php?show=adfair_list">• Fair Manager</a></li>
        <? } ?>

  </ul>
</li>
<? } ?>

<? if(getRolePageMenu($datarr["role_id"],2)==1) { ?>
<li class="active" >
  <a href="home.php?show=why_list"><img src="asset/icon_whythailand.png" height="12">&nbsp;&nbsp;&nbsp;<span class="nav-label">Which Industry</span> </a>
</li>
<? } ?>

<? if(getRolePageMenu($datarr["role_id"],3)==1) { ?>
<li class="active">
  <a href="home.php?show=news_list"><img src="asset/icon_news.png" height="12">&nbsp;&nbsp;&nbsp;<span class="nav-label">News Management</span> </a>
</li>
<? } ?>

<? if(getRolePageMenu($datarr["role_id"],4)==1) { ?>
<li class="active" >
  <a href="home.php?show=gallery_list"><img src="asset/icon_gallery.png" height="12">&nbsp;&nbsp;&nbsp;<span class="nav-label">Gallery Management</span> </a>
</li>
<? } ?>

<? if(getRolePageMenu($datarr["role_id"],5)==1) { ?>
<li class="active" >
  <a><img src="asset/icon_contact.png" height="12">&nbsp;&nbsp;&nbsp;<span class="nav-label">Contact Information</span>  <span class="fa arrow"></span></a>
  <ul class="nav nav-second-level">

    <? if(getRolePageMenu($datarr["role_id"],11)==1) { ?>
      <li <?php if($_REQUEST['show']=="contact_list" or $_REQUEST['show']=="contact_edit" or $_REQUEST['show']=="contact_cms"){echo "class='active'";}?>><a href="home.php?show=contact_list">• Contact Form</a></li>
    <? } ?>

    <? if(getRolePageMenu($datarr["role_id"],12)==1) { ?>
      <li <?php if($_REQUEST['show']=="fair_contact_list" or $_REQUEST['show']=="fair_contact_edit"){echo "class='active'";}?>><a href="home.php?show=fair_contact_list">• Fair Contact Form</a></li>
    <? } ?>

  </ul>
</li>
<? } ?>

<? if(getRolePageMenu($datarr["role_id"],6)==1) { ?>
<li class="active">
  <a><img src="asset/icon_admin.png" height="12">&nbsp;&nbsp;&nbsp;<span class="nav-label">Users & Setting</span>  <span class="fa arrow"></span></a>
  <ul class="nav nav-second-level">

        <? if(getRolePageMenu($datarr["role_id"],13)==1) { ?>
          <li <?php if($_REQUEST['show']=="admin_list" or $_REQUEST['show']=="admin_add" or $_REQUEST['show']=="admin_edit"){echo "class='active'";}?>><a href="home.php?show=admin_list">• Setting Users</a></li>
        <? } ?>

        <? if(getRolePageMenu($datarr["role_id"],14)==1) { ?>
          <li <?php if($_REQUEST['show']=="role_list" or $_REQUEST['show']=="role_add" or $_REQUEST['show']=="role_edit"){echo "class='active'";}?>><a href="home.php?show=role_list">• Setting Role</a></li>
        <? } ?>

        <? if(getRolePageMenu($datarr["role_id"],17)==1) { ?>
          <li <?php if($_REQUEST['show']=="tag_list" or $_REQUEST['show']=="tag_add" or $_REQUEST['show']=="tag_edit"){echo "class='active'";}?>><a href="home.php?show=tag_list">• Setting Tag Master</a></li>
        <? } ?>

        <? if(getRolePageMenu($datarr["role_id"],18)==1) { ?>
          <li <?php if($_REQUEST['show']=="bu_item"){echo "class='active'";}?>><a href="home.php?show=bu_item">• Setting Business Matching</a></li>
        <? } ?>

  </ul>
</li>
<? } ?>

<? if(getRolePageMenu($datarr["role_id"],7)==1) { ?>
<li class="active">
  <a><img src="asset/icon_report.png" height="12">&nbsp;&nbsp;&nbsp;<span class="nav-label">Report</span> </a>
</li>
<? } ?>

<? if(getRolePageMenu($datarr["role_id"],7)==1) { ?>
<li class="active">
  <a><img src="asset/icon_report.png" height="12">&nbsp;&nbsp;&nbsp;<span class="nav-label">Report</span>  <span class="fa arrow"></span></a>
  <ul class="nav nav-second-level">

        <? if(getRolePageMenu($datarr["role_id"],19)==1) { ?>
          <li><a>• Admin Activity Report</a></li>
        <? } ?>

        <? if(getRolePageMenu($datarr["role_id"],20)==1) { ?>
          <li><a>• User SSO Report</a></li>
        <? } ?>

  </ul>
</li>
<? } ?>

<? if(getRolePageMenu($datarr["role_id"],8)==1) { ?>
<li class="active">
  <a><img src="asset/icon_log.png" height="12">&nbsp;&nbsp;&nbsp;<span class="nav-label">Log History</span>  <span class="fa arrow"></span></a>
  <ul class="nav nav-second-level">

    <? if(getRolePageMenu($datarr["role_id"],15)==1) { ?>
      <li <?php if($_REQUEST['show']=="meeting_log" ){echo "class='active'";}?>><a href="home.php?show=meeting_log">• Online Meeting Log</a></li>
    <? } ?>

    <? if(getRolePageMenu($datarr["role_id"],16)==1) { ?>
      <li <?php if($_REQUEST['show']=="livechat_log" ){echo "class='active'";}?>><a href="home.php?show=livechat_log">• Live Chat Log</a></li>
    <? } ?>
  </ul>
</li>
<? } ?>
