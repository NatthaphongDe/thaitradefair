<? include_once ("backoffice/connect.php");

$bulink = "";
$sqlbu = "select * from tt_business_link where bu_id = '1' limit 1 ";
$stmtbu = $mysqli->prepare($sqlbu);
if($stmtbu) {
  $stmtbu->execute();
  $resultbu = $stmtbu->get_result();
  $numrowbu = $resultbu->num_rows;
  if($numrowbu>0) {
    $databu = $resultbu->fetch_assoc();
    if($databu["bu_url"]!="") {
      $bulink = $databu["bu_url"];
    }
  }
}
?>
<div class="collapse h-100 position-fixed top-0 left-0 w-100 bg-light" id="navbarToggleExternalMenu">
    <div class="container-fluid ">
        <div class="container">
            <div class="row px-0 px-md-5 pt-2 pt-md-3 d-none d-md-block">
                <div class="col">
                    <ul class="nav nav-pills float-end" id="top-menu">

                        <li class="nav-item">
                            <? if($_COOKIE["ssoid"]!="") { ?>
                            <div class="profileavatarname">
                              <a href="<?=ROOTPATHDOMAIN?>my-profile/" class="link-dark text-decoration-none opacity-100 mtmtop"><?=getSSOLoginShortName($_COOKIE["ssoid"])?></a>
                            </div>
                          <? } ?>
                        </li>

                        <li class="nav-item ">
                            <a class="py-0 px-2 opacity-100 navbar-toggler toggler-external-menu cursor-pointer"
                                data-bs-toggle="collapse">
                                <img src="<?=ROOTPATHDOMAIN?>assets/images/ico-menu-extend.png" class="ico-menu-extend mtmtop30" alt="icon">
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row px-0 px-md-5 pt-2 pt-md-3 d-block d-md-none">
                <div class="col m-0">
                    <a href="<?=ROOTPATHDOMAIN?>" class="d-flex align-items-center me-md-auto  float-start">
                        <img class="img-logo" src="<?=ROOTPATHDOMAIN?>assets/images/logo/logo.png" alt="thaitradefair">
                    </a>
                </div>
                <div class="col">
                    <ul class="nav nav-pills float-end pt-2" id="top-menu2">

                        <li class="nav-item d-sm-block d-md-none">
                            <? if($_COOKIE["ssoid"]!="") { ?>
                              <div class="profileavatarname profileavatarname_mt0">
                                <a href="<?=ROOTPATHDOMAIN?>my-profile/" class="link-dark text-decoration-none opacity-100 mtmtop"><?=getSSOLoginShortName($_COOKIE["ssoid"])?></a>
                              </div>
                            <? } ?>
                        </li>

                        <li class="nav-item ">
                            <a class="py-0 px-2 opacity-100 navbar-toggler toggler-external-menu cursor-pointer"
                                data-bs-toggle="collapse">
                                <i class="bi bi-list text-white icon-menu mtmtop30"></i>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="row px-3 px-md-5 mt-5">
                <div class="col d-none d-md-block">
                    <a href="<?=ROOTPATHDOMAIN?>" class="d-flex align-items-center me-md-auto float-start mt-2 mt-md-0">
                        <img class="img-logo imglogotop" src="<?=ROOTPATHDOMAIN?>assets/images/logo/logo.png" alt="thaitradefair">
                    </a>
                </div>
                <div class="col tab-content">
                    <div class="tab-pane fade show active" id="nav-right-menu">
                        <span class="title-text">Menu</span>
                        <ul class="list-group">
                            <li class="list-group-item py-1 <? echo ($top_menu_active =='fair-calendar')?'active':''?>">
                                <a href="<?=ROOTPATHDOMAIN?>fair-calendar/">FAIR CALENDAR</a>
                            </li>
                            <li class="list-group-item py-1 <? echo ($top_menu_active =='pre-registration')?'active':''?>">
                                <a href="<?=ROOTPATHDOMAIN?>pre-registration/">PRE-REGISTRATION</a>
                            </li>
                            <li class="list-group-item py-1 <? echo ($top_menu_active =='official-hotels')?'active':''?>">
                                <a href="<?=ROOTPATHDOMAIN?>official-hotels/">OFFICIAL HOTELS</a>
                            </li>
                            <li
                                class="list-group-item py-1 <? echo ($top_menu_active =='BUSINESS-MATCHING')?'active':''?>">
                                <? if($bulink!="") { ?>
                                  <a href="<?=$bulink?>" target="_blank" >BUSINESS MATCHING</a>
                                <? } else { ?>
                                  <a href="#">BUSINESS MATCHING</a>
                                <? } ?>
                            </li>
                            <li
                                class="list-group-item py-1 <? echo ($top_menu_active =='exporters-list-directory')?'active':''?>">
                                <a href="<?=ROOTPATHDOMAIN?>exporters-list-directory/">EXPORTER LIST</a>
                            </li>
                            <li class="list-group-item py-1 <? echo ($top_menu_active =='contact-us')?'active':''?>">
                                <a href="<?=ROOTPATHDOMAIN?>contact-us/">CONTACT US</a>
                            </li>
                            <? if($_COOKIE["ssoid"]!="") { ?>
                              <li
                                  class="list-group-item py-1 <? echo ($top_menu_active =='signin-register')?'active':''?>">
                                  <a class="link-open-signin" href="<?=ROOTPATHDOMAIN?>logout/">LOGOUT</a>
                              </li>
                            <? } else { ?>
                              <li
                                  class="list-group-item py-1 <? echo ($top_menu_active =='signin-register')?'active':''?>">
                                  <a class="link-open-signin" href="https://sso.ditp.go.th/sso/auth?client_id=SSO220628&response_type=token&redirect_uri=<?=ROOTPATHDOMAIN?>get-sso-login.php">BUYER SIGN-IN / REGISTER</a>
                              </li>
                            <? } ?>

                        </ul>
                    </div>


                    <!-- <div class="tab-pane fade mt-4 nav-form" id="nav-signin">
                        <div class="title-signin mb-4">Sign in</div>
                        <div class="form-group mb-2">
                            <input class="form-control" type="text" placeholder="Email">
                        </div>
                        <div class="form-group mb-3 position-relative">
                            <input class="form-control input-password" type="password" placeholder="Password" >
                            <i class="bi bi-eye togglePassword"></i>

                        </div>
                        <div class="form-grou mb-3">
                            <button class="btn btn-submit px-5 pt-2">Log in</button>
                        </div>
                        <div class="form-group mb-5">
                            <a class="fotget-passwd-text" href="#">Forgot your password?</a>
                        </div>

                        <div class="form-group mb-1">
                            <span class="fotget-register-text">Don’t have an account yet?</span>
                        </div>
                        <div class="form-grou mb-4">
                            <button class="btn btn-regist px-5 link-open-register">Register</button>
                        </div>
                    </div>

                    <div class="tab-pane fade mt-4 nav-form nav-register" id="nav-register">
                        <div class="title-signin mb-4">Register</div>
                        <div class="form-group mb-2">
                            <input class="form-control" type="text" placeholder="Company Name">
                        </div>
                        <div class="form-group mb-2">
                            <input class="form-control" type="text" placeholder="Full Name">
                        </div>
                        <div class="form-group mb-2">
                            <input class="form-control" type="text" placeholder="Country">
                        </div>
                        <div class="form-group mb-2">
                            <input class="form-control" type="text" placeholder="Email">
                        </div>
                        <div class="form-group mb-2 position-relative">
                            <input class="form-control input-password" type="password" placeholder="Password">
                            <i class="bi bi-eye togglePassword"></i>
                        </div>
                        <div class="form-group mb-3 position-relative">
                            <input class="form-control input-password" type="password" placeholder="Confirm Password">
                            <i class="bi bi-eye togglePassword"></i>
                        </div>
                        <div class="form-grou mb-3">
                            <button class="btn btn-submit px-5">Create your account</button>
                        </div>
                        <div class="form-group mb-5">
                            <a class="fotget-passwd-text" href="#">Forgot your password?</a>
                        </div>

                        <div class="form-group mb-1">
                            <span class="fotget-register-text pt-2">Already have an account ?</span>
                        </div>
                        <div class="form-grou mb-4">
                            <button class="btn btn-regist px-5 link-open-signin">Sign in</button>
                        </div>
                    </div> -->

                </div>
            </div>
        </div>
    </div>
</div>
