<link href="<?=ROOTPATHDOMAIN?>assets/css/carousel.css" rel="stylesheet">
<link href="<?=ROOTPATHDOMAIN?>assets/dist/scrollbar/jquery.scrollbar.css" rel="stylesheet">

<form onsubmit="changePageEx(1);return false;" >
<div id="search-exhibitor-list" class="row mt-3 rounded-4">
    <div class="col align-self-center py-3 _exspad">
        <div id="search" class="input-group my-4">
            <!-- <select type="text" class="form-select type-exhibitors w-20">
                <option>
                    Search All
                </option>
            </select> -->

              <input id="wordsearch" type="text" class="form-control w-50"
                  placeholder="Company Name, Product Group, Product Name, Brand Name, Business Register No., Vat No. or Booth No." onkeyup="getWtxt();">

              <button  class="btn btn-search rounded-end text-center pt-2" type="submit">
                  <i class="bi bi-search text-white "></i>
              </button>

              <input type="text" name="csrf_token" value="<?=$_SESSION['csrf_token']?>" id="csrf_token" hidden>
        </div>
    </div>
</div>
</form>

<form method="post" target="com_m" action="<?=ROOTPATHDOMAIN?>print-exhibitor-list-directory.php">
<div id="company-list" class="mt-5 mt-sm-3 px-0 px-md-4">
    <ul class="nav nav-tabs border-0">
        <li class="nav-item">
            <a class="nav-link active" aria-current="page">Company </a>
        </li>
    </ul>


    <div class="float-end w-auto search-area pt-2" style="margin-top: -55px;">
        <button type="submit" class="btn w-auto float-end bg-green">
            <img src="<?=ROOTPATHDOMAIN?>assets/images/anticon-local-printshop-material.png" style="height:15px;" />
            Print
        </button>
        <select id="excat_select" class="form-select selectpicker w-auto float-end"
            style="padding-right:2rem; margin-right:0.5rem;" onchange="changePageEx(1);">
            <option value="" selected>All Categories</option>
            <?
            $sqlelc = " select product_cat from tt_exhibitor_list where fair_id = ? and product_cat != '' group by product_cat order by product_cat ASC  ";
            $stmtelc = $mysqli->prepare($sqlelc);
            $stmtelc->bind_param('i',$fair_id);
            $stmtelc->execute();
            $resultelc = $stmtelc->get_result();
            $numrowelc = $resultelc->num_rows;
            if($numrowelc>0) {
              while($dataelc = $resultelc->fetch_assoc()) {
            ?>
            <option value="<?=$dataelc["product_cat"]?>"><?=$dataelc["product_cat"]?></option>
            <? } } ?>
        </select>
    </div>


    <div class="tab-content px-0 _exdatalist" id="myTabContent">

      <?

      $sqlc = "select * from tt_fair_list_cat a left join tt_fair_category b on a.fcat_id=b.fcat_id where a.fair_id = ? and a.fct_id = ?  ";
      $stmtc = $mysqli->prepare($sqlc);
      $stmtc->bind_param('ii',$fair_id,$fct_id);
      $stmtc->execute();
      $resultc = $stmtc->get_result();
      $numrowc = $resultc->num_rows;
      $datac = $resultc->fetch_assoc();
      $fctname = $datac["fcat_name"];

      $showpage = 100;

      $sqlel = " select * from tt_exhibitor_list where fair_id = ? group by com_taxno order by com_name ASC  ";
      $stmtel = $mysqli->prepare($sqlel);
      $stmtel->bind_param('i',$fair_id);
      $stmtel->execute();
      $resultel = $stmtel->get_result();
      $numrowel = $resultel->num_rows;
      $allpage = ceil($numrowel/$showpage);
      if($numrowel<=$showpage) {
        $numrowelshow = $numrowel;
      } else {
        $numrowelshow = $showpage;
      }


      $page = (int)$_GET["page"];
      if($page<=0) {
        $page = 1;
      } else {
        if($page>=$allpage) {
          $page = $allpage;
        }
      }

      if($page<=0) {
        $page = 1;
      }

      $start_page = $showpage*($page-1);

      ?>

        <div class="fade show active pb-3" id="company-tab-pane" role="tabpanel"
            aria-labelledby="home-tab" tabindex="0">
            <div class="row tab-title w-100 mx-0 py-2" style="border-top-right-radius: 0.75rem;">
                <div class="col-12 col-sm-6  _exleftpad" style="padding-left: 2rem;">
                  <input class="form-check-input " type="checkbox" value=""
                      id="titleCheckDefault" onchange="getCheckItem('act_id_ss','titleCheckDefault');">
                    <label class="form-check-label" for="titleCheckDefault">
                        Company Name 
                    </label>
                    <span class="total-1">(<?=number_format($numrowel)?>)</span>
                </div>
                <div class="col-12 col-sm-6 text-white total-right text-end">
                    <span class="">
                        1 - <?=$numrowelshow?> of <?=number_format($numrowel)?>
                    </span>
                    <? if($page==1) { ?>
                      <i class="icon-navigator-page bi bi-chevron-left text-white disable"></i>
                    <? } else { ?>
                      <?
                      $backpage = $page-1;
                      ?>
                      <a onclick="changePageEx('<?=$backpage?>');"><i class="icon-navigator-page bi bi-chevron-left text-white"></i></a>
                    <? } ?>

                    <input type="text" value="<?=$page?>" id="pagebox" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" onkeyup="changePageExKey(this.value)"  />
                    <span class=""> / <?=$allpage?> </span>

                    <? if($page>=$allpage) { ?>
                      <i class="icon-navigator-page bi bi-chevron-right text-white disable"></i>
                    <? } else { ?>
                      <?
                      $nextpage = $page+1;
                      ?>
                      <a onclick="changePageEx('<?=$nextpage?>');"><i class="icon-navigator-page bi bi-chevron-right text-white"></i></a>
                    <? } ?>
                </div>
            </div>

            <div class="scrollbar-inner">
                <ul class="list-group checkbox-list-company border-0 px-4">

                  <?

                  $sqleld = " select * from tt_exhibitor_list where fair_id = ? group by com_taxno order by com_name ASC limit $start_page,100 ";
                  $stmteld = $mysqli->prepare($sqleld);
                  $stmteld->bind_param('i',$fair_id);
                  $stmteld->execute();
                  $resulteld = $stmteld->get_result();
                  $numroweld = $resulteld->num_rows;
                  if($numroweld>0) {
                    while($dataeld = $resulteld->fetch_assoc()) {
                  ?>
                    <li
                        class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0">
                        <div class="divchkex" >
                          <input class="form-check-input mx-2 act_id_ss" name="chklist[]" type="checkbox" value="<?=$dataeld["exl_id"]?>">
                        </div>

                        <div class="ms-2 me-auto">
                            <div class="title fw-bold">
                                <a href="<?=ROOTPATHDOMAIN?>fair-exhibitor/<?=$fair_id?>/<?=urlencode($datafair["fair_name"])?>/<?=$fct_id?>/<?=urlencode($fctname)?>/<?=$dataeld["exl_id"]?>/<?=urlencode($dataeld["com_name"])?>/">
                                    <?=$dataeld["com_name"]?>
                                </a>
                            </div>
                            <?=$dataeld["product_group"]?>
                        </div>
                    </li>
                  <? } } else { ?>
                    <li
                        class="list-group-item d-flex justify-content-between align-items-start border-0 border-bottom px-0 ">
                        <div class="ms-2 me-auto w-100 mt-5 mb-5 text-center">
                            Data not found.
                        </div>

                    </li>
                  <? } ?>

                </ul>
            </div>
        </div>
    </div>
</div>
<input type="text" name="csrf_token" value="<?=$_SESSION['csrf_token']?>" id="csrf_token" hidden>
</form>

<iframe id="com_m" name="com_m" class="ifsave" width="0" height="0" frameborder="0" scrolling="no"></iframe>
<input type="hidden" id="wsearch" value="">
<input type="hidden" id="wsearchtype" value="0">

<link rel="stylesheet" type="text/css" href="<?=ROOTPATHDOMAIN?>assets/js/jquery-ui.min.css" />
<script type="text/javascript" src="<?=ROOTPATHDOMAIN?>assets/js/jquery-ui.min.js"></script>
<script type="text/javascript">
function getWtxt() {
  $('#wsearch').val($('#wordsearch').val());
}

function changePageExKey(val) {
  if(val!="") {
    val = parseInt(val);
    if(val>0) {
      changePageEx(val);
    }
  }
}

function changePageEx(page) {
  var cat = $('#excat_select').val();
  var keyw = $('#wsearch').val();
  var keywtype = $('#wsearchtype').val();
  var csrf_token = $('#csrf_token').val();
  $.ajax({
    type: "GET",
      //url: "<?=ROOTPATHDOMAIN?>ajax-chagepage-exhibitor.php?fair_id=<?=$fair_id?>&fct_id=<?=$fct_id?>&cat="+encodeURIComponent(cat)+"&page="+page+'&keyw='+encodeURIComponent(keyw)+'&keywtype='+keywtype,
    url: "<?=ROOTPATHDOMAIN?>ajax-chagepage-exhibitor.php",
    data: {
        fair_id: <?=$fair_id?>,
        fct_id: <?=$fct_id?>, 
        cat: encodeURIComponent(cat),
        page:page,
        keyw:encodeURIComponent(keyw),
        keywtype:keywtype,
        token: csrf_token
      },
      dataType: "text",
      success : function(data) {
        $('._exdatalist').empty();
        $("._exdatalist").html(data);
      }
  });
}

$(document).ready(function() {
	$('#wordsearch').autocomplete({
		source: function( request, response ) {
			$.ajax({
				url : '<?=ROOTPATHDOMAIN?>ajax-keyword-exhibitor.php?fair_id=<?=$fair_id?>',
				dataType: "json",
				data: {
				   name_startsWith: request.term,
				   type: 'country_table',
				   row_num : 1
				},
				 success: function( data ) {
					 response( $.map( data, function( item ) {
						var code = item.split("|");
						return {
							label: code[0]+' - '+code[1],
							value: '',
							data : item
						}
					}));
				}
			});
		},
		autoFocus: true,
		minLength: 1,
    appendTo: '#search',
		select: function( event, ui ) {
			var names = ui.item.data.split("|");
      if(names[3]==1) {
        window.location='<?=ROOTPATHDOMAIN?>fair-exhibitor/<?=$fair_id?>/<?=urlencode($datafair["fair_name"])?>/<?=$fct_id?>/<?=urlencode($fctname)?>/'+names[2]+'/'+encodeURIComponent(names[4])+'/';
      } else {
        setTimeout(function () { $('#wordsearch').val(names[2]); getWtxt(); changePageEx(1); },100);
      }
		}
	}).data("ui-autocomplete")._renderItem = function( ul, item ) {
      let txt = String(item.value).replace(new RegExp(this.term, "gi"),"<b class='searchmatchtxt'>$&</b>");
      return $("<li></li>")
          .data("ui-autocomplete-item", item)
          .append("<a>" + txt + "</a>")
          .appendTo(ul);
  };

} );
</script>

<style>
.navigator-text {
    font-size: 18px;
    font-weight: normal;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #111;
}

.navigator-text .active {
    font-weight: bold;
}


h1.title {
    font-size: 40px;
    font-weight: bold;
    font-stretch: normal;
    font-style: normal;
    line-height: 1.34;
    letter-spacing: normal;
}



#search-exhibitor-list {

    background-blend-mode: multiply;
    background-image: linear-gradient(to bottom, #378dd7, #378dd7);
}

#search-exhibitor-list .input-group {
    border-radius: 6px;
    border: solid 1px #fff;
    background-color: #fff;
}


#search-exhibitor-list #search .form-control,
#search-exhibitor-list #search .form-select {
    font-size: 16px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #378dd7;

}

#search-exhibitor-list #search .form-select {
    background-image: url('<?=ROOTPATHDOMAIN?>assets/images/icon-chevron-down-blue.svg');
}

#search-exhibitor-list #search .btn-search {
    background-color: #378dd7 !important;
    border-color: #378dd7 !important;
}

#search-exhibitor-list #search .btn-search .bi {
    font-size: 20px;
}

#search-exhibitor-list #search .btn-search .bi:before {
    font-weight: bold !important;
}

@media (max-width: 575px) {
    .search-area {
        margin-top: -105px !important;
    }
}

.search-area .form-select {
    border: none !important;
    background: none !important;
    padding: 0;
    margin-right: 0.5rem;
    font-size: 16px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
}

#search-exhibitor-list #search .form-select.type-exhibitors {
    font-size: 20px;
}

.search-area .dropdown-toggle {
    padding-top: 4px !important;
    padding-bottom: 2px !important;
    height: auto;
    border-radius: 7px;
    background-color: #fba91e !important;
    border: none !important;
    font-size: 16px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #fff;
    /* padding: 0.3rem 1rem 0.3rem 1rem; */
    padding-left: 11px !important;
    padding-right: 11px !important;
    outline: none !important;

}

.bootstrap-select .dropdown-menu li a span.text {
    font-size: 16px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;

}

.bootstrap-select .dropdown-toggle:focus,
.bootstrap-select>select.mobile-device:focus+.dropdown-toggle {
    outline: none !important;
    outline-offset: inherit;
}

.search-area .bg-green {
    height: 28px;
    border-radius: 7px;
    background-color: none !important;
    border: none !important;
    font-size: 16px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #fff;
    padding: 0.1rem 1rem 0.3rem 1rem;
    background-color: #659a83 !important;
}


#company-list {
    position: relative;
}

#company-list .nav-link {
    border-top-left-radius: 0.75rem;
    border-top-right-radius: 0.75rem;
    border: none;
    font-size: 20px;
    font-weight: bold;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    text-align: justify;
}

#company-list .nav-link.active {
    background-color: #378dd7;
    color: #fff;
}

#company-list .nav-tabs .nav-item {
    z-index: 2;
}

#myTabContent {
    border-top-right-radius: 0.75rem;
    box-shadow: 0 2px 15px 0 rgba(0, 0, 0, 0.5);
    background-color: #fff;
    z-index: 1;
    margin-top: 0px;
    position: relative;
    border-end-end-radius: 0.75em;
    border-end-start-radius: 0.75em;
}

#myTabContent .tab-title {
    background-color: #378dd7;
}

#myTabContent .tab-title .form-check-label {
    font-size: 16px;
    font-weight: bold;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #fff;
    margin-left: 1.5rem;
}

#myTabContent .tab-title .total-1 {
    font-size: 14px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #fff;
}

#myTabContent .tab-title .total-right {
    font-size: 16px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #fff;
}

#myTabContent .tab-title .total-right input {
    border-radius: 6px;
    border: solid 1px #378dd7 !important;
    background-color: #fff;
    width: 50px;
    text-align: center;
    outline: none;
}

#myTabContent .tab-title .total-right .bi {
    font-size: 14px;
    line-height: 1;
}

#myTabContent .tab-title .total-right .bi.disable {
    opacity: 0.14;
}

#myTabContent .tab-title .total-right .bi:before {
    font-weight: bold;
}

.checkbox-list-company .list-group-item {
    font-size: 14px;
    font-weight: 500;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #000;
}

.checkbox-list-company .list-group-item .title {
    font-size: 18px;
    font-weight: bold;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #000;
}

.checkbox-list-company .list-group-item .form-check-input {
    border: solid 1px #378dd7;
}

#titleCheckDefault {
    width: 0.8em;
    height: 0.8em;
    border: solid 1px #378dd7;
}



.btn-all-category {
    font-size: 20px;
    font-weight: 600;
    font-stretch: normal;
    font-style: normal;
    line-height: normal;
    letter-spacing: normal;
    color: #fff !important;
    border-radius: 14px;
    border: none !important;
}
</style>

<script type="text/javascript">
function getCheckItem(div,id) {
  if($('#' + id).is(":checked")) {
    $("."+div).prop("checked", true);
  } else {
    $("."+div).prop("checked", false);
  }
}
</script>
