<?
include_once("backoffice/connect.php");

if ($_REQUEST["method"] == "savepole") {
  if ($_SESSION['csrf_token'] != $_POST["csrf_token"]) { ?>
    <script type="text/javascript">
      setTimeout(function() {
        top.alertToken();
      }, 1000);
    </script>
  <?
    exit();
  }
  $rate_ip = get_real_ip();
  $rate_score = $_POST["rate"];
  $rate_message = $_POST["rate_message"];

  $sql = "insert into tt_rate_web (rate_score,rate_message,rate_date,rate_ip) values (?,?,now(),?) ";
  $stmt = $mysqli->prepare($sql);
  if ($stmt) {
    $stmt->bind_param('iss', $rate_score, $rate_message, $rate_ip);
    $stmt->execute();
  }
  ?>
  <script type="text/javascript">
    setTimeout(function() {
      top.alertSuccessPole();
    }, 1000);
    setTimeout(function() {
      top.closePole();
    }, 1000);
  </script>
<?
  exit();

  exit();
}

?>

<style media="screen">
  .modal-body {
    padding: 0 !important;
    overflow: hidden;
    scrollbar-width: thin;
    position: relative;

    background-image:
      url('../assets/images/pole/bg-left-top.png'),
      url('../assets/images/pole/bg-right-bottom.png'),
      url('../assets/images/pole/bg-top.png'),
      url('../assets/images/pole/bg-bottom.png');
    background-repeat: no-repeat, no-repeat, no-repeat, no-repeat;
    background-position: top left, bottom right, top center, bottom center;
    background-size: 25%, 18%, 100%, 100%;

  }


  .closeblog {
    position: absolute;
    z-index: 3;
    right: 20px;
    top: 20px;
  }



  .title_ploe {
    font-size: 16px;
    color: #2f2f2f;
    font-weight: bold;
  }

  .content_ploe {
    font-size: 14px;
    color: #2f2f2f;
    font-weight: bold;
    padding-top: 10px;
  }

  .inner {
    width: 80%;
    margin: auto;
    overflow: hidden;
  }

  .inner2 {
    width: 60%;
    margin: auto;
    overflow: hidden;
  }

  @media (max-width: 575px) {
    .inner2 {
      width: 80%;
    }
  }


  .texta {
    font-size: 14px;
  }

  .btn-feedback {
    background: #378dd7;
    border-color: #378dd7;
    font-size: 14px;
    padding-left: 40px;
    padding-right: 40px;
  }

  .list-group {
    background: none;
    border: none;
    display: flex;
    flex-direction: row;
    flex-wrap: wrap;
    max-width: 100%;
  }

  .list-group-item {
    background: none;
    border: none;
    max-width: 100%;
  }

  .mmt {
    margin-top: -7px;
  }

  .mainpole {
    position: relative;
    z-index: 1;
  }
</style>
<div class="modal-content">

  <div class="modal-body">


    <div class="closeblog">
      <a onclick="closePole();" id='end_question'><img src="<?= ROOTPATHDOMAIN ?>assets/images/pole/close.png" alt="thaitradefair" height="20" /></a>
    </div>

    <form class="form-horizontal" method="post" name="saveformform" enctype="multipart/form-data" id="saveformform" action="<?= ROOTPATHDOMAIN ?>pole.php?method=savepole" target="com_mxx" onsubmit="return chkPole();">
      <br>
      <div class="container mainpole">
        <div class="row">
          <div class="col-12 text-center">
            <img src="<?= ROOTPATHDOMAIN ?>assets/images/logo/web-logo.png" alt="thaitradefair" height="60" />
          </div>
        </div>

        <div class="row pt-2">
          <div class="col-12 text-center">
            <span class="title_ploe">Please rate your experience</span>
          </div>
        </div>


        <div class="row pt-3">
          <div class="col-12">
            <div class="inner2">
              <div class="row">

                <div class="col-4 text-center">
                  <div class="row">
                    <div class="col-12">
                      <a onclick="clickPole(1);"><img id="ic-1" src="<?= ROOTPATHDOMAIN ?>assets/images/pole/icon-1-null.png" alt="thaitradefair" height="60" /></a>
                    </div>
                    <div class="col-12 pt-1">
                      <span class="content_ploe">Poor</span>
                    </div>
                    <div class="col-12 mmt">
                      <span><img src="<?= ROOTPATHDOMAIN ?>assets/images/pole/star-act.png" alt="thaitradefair" height="13" /></span>
                      <span><img src="<?= ROOTPATHDOMAIN ?>assets/images/pole/star-null.png" alt="thaitradefair" height="13" /></span>
                      <span><img src="<?= ROOTPATHDOMAIN ?>assets/images/pole/star-null.png" alt="thaitradefair" height="13" /></span>
                    </div>
                  </div>

                </div>

                <div class="col-4 text-center">
                  <div class="row">
                    <div class="col-12">
                      <a onclick="clickPole(2);"><img id="ic-2" src="<?= ROOTPATHDOMAIN ?>assets/images/pole/icon-2-null.png" alt="thaitradefair" height="60" /></a>
                    </div>
                    <div class="col-12 pt-1">
                      <span class="content_ploe">OK</span>
                    </div>
                    <div class="col-12 mmt">
                      <span><img src="<?= ROOTPATHDOMAIN ?>assets/images/pole/star-act.png" alt="thaitradefair" height="13" /></span>
                      <span><img src="<?= ROOTPATHDOMAIN ?>assets/images/pole/star-act.png" alt="thaitradefair" height="13" /></span>
                      <span><img src="<?= ROOTPATHDOMAIN ?>assets/images/pole/star-null.png" alt="thaitradefair" height="13" /></span>
                    </div>
                  </div>

                </div>

                <div class="col-4 text-center">
                  <div class="row">
                    <div class="col-12">
                      <a onclick="clickPole(3);"><img id="ic-3" src="<?= ROOTPATHDOMAIN ?>assets/images/pole/icon-3-null.png" alt="thaitradefair" height="60" /></a>
                    </div>
                    <div class="col-12 pt-1">
                      <span class="content_ploe">Great</span>
                    </div>
                    <div class="col-12 mmt">
                      <span><img src="<?= ROOTPATHDOMAIN ?>assets/images/pole/star-act.png" alt="thaitradefair" height="13" /></span>
                      <span><img src="<?= ROOTPATHDOMAIN ?>assets/images/pole/star-act.png" alt="thaitradefair" height="13" /></span>
                      <span><img src="<?= ROOTPATHDOMAIN ?>assets/images/pole/star-act.png" alt="thaitradefair" height="13" /></span>
                    </div>
                  </div>

                </div>
              </div>
            </div>
          </div>


        </div>

        <div class="row pt-2">
          <div class="col-12 text-center">
            <span class="title_ploe">What can we do to improve service?</span>
          </div>
        </div>


        <div class="row">
          <div class="col-12">
            <div class="inner">
              <div class="row">
                <div class="col-12 pt-3">
                  <textarea name="rate_message" rows="3" placeholder="Type Message…" class="form-control texta"></textarea>
                </div>
              </div>

              <div class="row">
                <div class="col-12 pt-3 text-center">
                  <button type="submit" id='sendfeed' class="btn btn-primary btn-feedback">Send feedback</button>
                </div>
              </div>
              <hr>
            </div>
          </div>
        </div>


        <div class="row">
          <div class="col-12">
            <div class="footer d-grid justify-content-center ">
              <ul class="list-group list-group-horizontal">
                <li class="list-group-item px-2">
                  <img src="<?= ROOTPATHDOMAIN ?>assets/images/logo/ditp-logo.svg" alt="ditp" height="20" />
                </li>
                <li class="list-group-item px-2">
                  <img src="<?= ROOTPATHDOMAIN ?>assets/images/logo/Thaitrade.svg" alt="thaitrade" height="15" />
                </li>
                <li class="list-group-item px-2">
                  <img src="<?= ROOTPATHDOMAIN ?>assets/images/logo/web-logo.png" alt="thaitradefair" height="20" />
                </li>
                <li class="list-group-item px-2">
                  <img src="<?= ROOTPATHDOMAIN ?>assets/images/logo/ttd-logo.svg" alt="thailand tourism directory" height="20" />
                </li>
                <li class="list-group-item px-2">
                  <img src="<?= ROOTPATHDOMAIN ?>assets/images/logo/tceb-logo.svg" alt="tceb" height="20" />
                </li>
              </ul>
            </div>
          </div>
        </div>

      </div>
      <br>
      <input type="text" name="csrf_token" value="<?= $_SESSION['csrf_token'] ?>" hidden>
      <input type="hidden" name="rate" id="rate" value="0">
    </form>
  </div>

</div>
<iframe id="com_mxx" name="com_mxx" class="ifsave" width="0" height="0" frameborder="0" scrolling="no"></iframe>

<script type="text/javascript">
  function clickPole(r) {
    for (i = 1; i <= 3; i++) {
      $('#ic-' + i).attr("src", "<?= ROOTPATHDOMAIN ?>assets/images/pole/icon-" + i + "-null.png");
    }

    $('#ic-' + r).attr("src", "<?= ROOTPATHDOMAIN ?>assets/images/pole/icon-" + r + "-act.png");
    $('#rate').val(r);
  }

  function chkPole() {
    var r = $('#rate').val();
    if (r == 0) {
      swal({
        title: "Please choose your rate",
        text: "Please choose your rate experience.",
        type: "info",
        showCancelButton: false,
        confirmButtonColor: "#5cb85c",
        confirmButtonText: "close",
        closeOnConfirm: false
      });

      return false;
    }

    return true;
  }
</script>

<script type="text/javascript">
  function alertSuccessPole() {
    swal({
      title: "Success",
      text: "Thank you for your rating.",
      type: "success",
      showCancelButton: false,
      confirmButtonColor: "#5cb85c",
      confirmButtonText: "close",
      closeOnConfirm: false
    });
  }

  function alertToken() {
    swal({
      title: "",
      text: "The token provided is invalid. Please try again",
      type: "error",
      showCancelButton: false,
      confirmButtonColor: "#5cb85c",
      confirmButtonText: "close",
      closeOnConfirm: false
    }, function(isConfirm) {
      if (isConfirm) {
        window.location = "/";
      }
    });
  }
</script>