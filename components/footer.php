<? include_once ("backoffice/connect.php"); ?>
<footer class="footer mt-auto text-white text-center mt-5 pb-0">
    <div class="container footer-combined-shape py-3">
        <div class="d-grid justify-content-center logo-footer pt-0 pt-lg-4">
            <ul class="list-group list-group-horizontal">
                <li class="list-group-item px-2">
                  <a href="https://www.ditp.go.th/" target="_blank" > 
                    <img src="<?=ROOTPATHDOMAIN?>assets/images/logo/ENG Brand@3x.png" class="fdipt" alt="ditp" />
                  </a>
                </li>
                <li class="list-group-item px-2">
                    <img src="<?=ROOTPATHDOMAIN?>assets/images/logo/Thaitrade.svg" class="fttf" alt="thaitrade" />
                </li>
                <li class="list-group-item px-2">
                    <img src="<?=ROOTPATHDOMAIN?>assets/images/logo/web-logo.png" class="fweb" alt="thaitradefair" />
                </li>
                <li class="list-group-item px-2">
                    <img src="<?=ROOTPATHDOMAIN?>assets/images/logo/ttd-logo.svg" class="fttd" alt="thailand tourism directory" />
                </li>
                <li class="list-group-item px-2">
                    <img src="<?=ROOTPATHDOMAIN?>assets/images/logo/tceb-logo.svg" class="fceb" alt="tceb" />
                </li>
            </ul>
        </div>
        <div class="d-grid text-footer">
          Office of Information Technology and Service Development, Department of International Trade Promotion, Ministry of Commerce <br />
            563 Nonthaburi Road, Bang Kra Sor, Nonthaburi 11000, Thailand Email Tradeshow@ditp.go.th Call Center 1169
        </div>
    </div>
</footer>

<div class="modal fade text-left w-100 bg-modal" id="full-scrn_edit" tabindex="-1" aria-labelledby="myModalLabel20" aria-hidden="true">
  <div  class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-full _modelinv" role="document"></div>

</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-lgs" role="document" style="max-width: 800px;width: 100%;">
    <div class="modal-content" style="flex-direction: row;background-color: #00000000; box-shadow: 0 5px 15px rgba(0,0,0,0);border: none;">
      <div class="modal-body bgpopup" style="background-color: #00000000; text-align: center;">
      <div class="closeblog" style="position: absolute; z-index: 3;right: 30px;top: 30px;">
        <a onclick="closebanner();"><img src="https://www.thaitradefair.com/assets/images/pole/close.png" alt="thaitradefair" height="20"></a>
      </div>

        <div id="myCarousel" class="carousel slide" data-ride="carousel">
          <ol class="carousel-indicators">
            <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
          </ol>
          <div class="carousel-inner" style="max-width: 800px;width: 100%;">
            <div class="item active">
              <a href="#" target="_blank"> 
                <!-- <img style="max-width: 600px;width: 100%;" src="<?= ROOTPATHDOMAIN ?>assets/images/Maintenance.png" alt=""> -->
                <!-- <img style="max-width: 800px;width: 100%;" src="<?= ROOTPATHDOMAIN ?>assets/images/dtip-x-queq_800x6000-03.jpg" alt=""> -->
                <img style="max-width: 650px;width: 100%;" src="<?= ROOTPATHDOMAIN ?>assets/images/Popup Thaitradefair.png" alt="">
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<? 
$paymentDate = date('Y-m-d');
$paymentDate=date('Y-m-d', strtotime($paymentDate));
//echo $paymentDate; // echos stoday! 
$contractDateBegin = date('Y-m-d', strtotime("2025/04/30"));
$contractDateEnd = date('Y-m-d', strtotime("2025/05/06"));
if (($paymentDate >= $contractDateBegin) && ($paymentDate <= $contractDateEnd)){
  
  if (empty($_SESSION["popupshow"])) {?>
    <script type="text/javascript">
       $(document).ready(function() {
        popupshow();
       })
      </script>
  <? $_SESSION["popupshow"] = 1;
   }else { 
    $_SESSION["popupshow"] = null;
   }
}else{?>
  <script type="text/javascript">
  $(document).ready(function() {
    console.log(6);
    setTimeout(function () {
      <? if($_SESSION["poleshow"]=="") { ?>
        <? $_SESSION["poleshow"] = 1; ?>
        /* popupshow(); */
      <? } ?>
    },2000);
  
  
  });
  </script>
  <?}
?>
<script type="text/javascript">
function showPole() {
  $('#full-scrn_edit').modal('show');
  $('._modelinv').empty();
  $.ajax({
      type: "GET",
      url: "<?=ROOTPATHDOMAIN?>pole.php",
      dataType: "text",
      success : function(data) {
        $('._modelinv').html(data);
      }
  });

}
function popupshow() {
    $("#myModal").modal('show');
  }
  function closebanner() {
    $("#myModal").modal('hide');
  }
function closePole() {
  $('._modelinv').empty();
  $('#full-scrn_edit').modal('hide');
}
</script>

<script src="<?=ROOTPATHDOMAIN?>assets/js/bootstrap.bundle.min.js"></script>

<link rel="stylesheet" href="<?= ROOTPATHDOMAIN ?>assets/dist/bootstrap-select-1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="<?= ROOTPATHDOMAIN ?>assets/dist/bootstrap-select-1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<!-- <script src="<?= ROOTPATHDOMAIN ?>assets/dist/bootstrap-select-1.14.0-beta3/dist/js/i18n/defaults-*.min.js"></script> --> 
 <!-- Latest compiled and minified CSS -->
 <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css"> -->
 <!-- Latest compiled and minified JavaScript -->
 <!-- <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script> -->
 <!-- (Optional) Latest compiled and minified JavaScript translation files -->
 <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/i18n/defaults-*.min.js"></script>


 <!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=G-DDQ18343D4"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', 'G-DDQ18343D4');
</script>

<script>
$(document).ready(function() {
  //popupshow();
  $('#full-scrn_edit').modal({
      backdrop: 'static',
      keyboard: false
  });
  if (Cookies.get('question') === undefined) {
      var question_cookie = {
          num: 1,
          status: 0
      };
      Cookies.set('question', JSON.stringify(question_cookie), {
          expires: 7
      });
  } else {
      var question_cookie = JSON.parse(Cookies.get('question'));
      if (question_cookie.num < 5) {
          question_cookie.num = (question_cookie.num + 1)
          Cookies.set('question', JSON.stringify(question_cookie), {
              expires: 7
          });
          
      }
      if (question_cookie.num > 4 && question_cookie.status == 0 ) {
        setTimeout(function () {
          showPole();
        },1000);
      }
  }

  $(document).on('click', '#end_question', function() {
      var question_cookie = {
          status: 1
      };
      Cookies.set('question', JSON.stringify(question_cookie), {
          expires: 7
      });
  });

  $(document).on('click', '#sendfeed', function() {
      var question_cookie = {
          status: 1
      };
      Cookies.set('question', JSON.stringify(question_cookie), {
          expires: 7
      });
  });

  setTimeout(function () {$('.selectpicker').show()},1);
  
  /* setTimeout(function () {
    <? if($_SESSION["poleshow"]=="") { ?>
      <? $_SESSION["poleshow"] = 1; ?>
      showPole();
    <? }else{?>
   <? } ?>
  },2000); */


});
</script>
