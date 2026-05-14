<?
$top_menu_active = "my-profile";
?>
<!doctype html>
<html lang="en">

<head>
    <title>Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?></title>
    <?php include('components/header.php') ?>

    <!-- Custom styles for this template -->
    <link href="assets/css/carousel.css" rel="stylesheet">

    <link rel="stylesheet" type="text/css" href="assets/dist/slick/slick.css">
    <link rel="stylesheet" type="text/css" href="assets/dist/slick/slick-theme.css">
</head>

<body class="d-flex flex-column h-100">
    <div id="vue-app" class="p-0 bg-header-1">
        <?php include('components/external_menu.php'); ?>
        <header class="d-flex flex-wrap justify-content-center ">
            <div class="container-fluid">
                <div class="container combined-shape-2 ">
                    <?php include('components/header_menu.php'); ?>
                    <div class="row mt-4 px-4 mb-5">
                        <div class="col">
                            <div id="my-profile" class="row rounded-4 overflow-hidden">
                                <div class="col-12 col-lg-8 pt-4 px-5 pb-4 pb-lg-0 _exinner">
                                    <div class="d-grid company-name text-start mb-2">
                                        My Profile
                                    </div>

                                    <div class="row company-detail">
                                        <div class="col-12 col-sm-3 txt-title mb-0 mb-sm-2">
                                            Name :
                                        </div>
                                        <div class="col-12 col-sm-9 txt-detail mb-2">
                                            <input class="form-control" type="text" />
                                        </div>
                                        <div class="col-12 col-sm-3 txt-title mb-0 mb-sm-2">
                                            Email :
                                        </div>
                                        <div class="col-12 col-sm-9 txt-detail mb-2">
                                            <input class="form-control" type="text" />
                                        </div>
                                        <div class="col-12 col-sm-3 txt-title mb-0 mb-sm-2">
                                            Company Name :
                                        </div>
                                        <div class="col-12 col-sm-9 txt-detail mb-2">
                                            <input class="form-control" type="text" />
                                        </div>
                                        <div class="col-12 col-sm-3 txt-title mb-0 mb-sm-2">
                                            Country :
                                        </div>
                                        <div class="col-12 col-sm-9 txt-detail mb-2">
                                            <input class="form-control" type="text" />
                                        </div>
                                        <div class="col-12 col-sm-3 txt-title mb-0 mb-sm-2">
                                            Address :
                                        </div>
                                        <div class="col-12 col-sm-9 txt-detail mb-2">
                                            <textarea class="form-control" ></textarea>
                                        </div>
                                        <div class="col-12 col-sm-9 offset-sm-3 txt-detail mt-4 mb-4">
                                            <button class="btn btn-bg-blue p-1 px-4">Save</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-lg-4 p-0 contact-data-area pb-4">
                                    <div class="row p-0 contact-detail px-5 mb-5">
                                        <div class="col-12 py-4 text-center">
                                            <div class="rounded-circle avatar mx-auto"
                                                style="width:150px; height:150px;">
                                                <img src="assets/images/ex-my-profile.png">
                                            </div>
                                        </div>
                                        <div class="col-12">
                                            <div class="d-grid contact-btn mb-2">
                                                <button class="btn btn-outline-white d-block py-1">Change Photo</button>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="contact-btn-area mt-auto">
                                        <div class="d-grid contact-btn px-5 mb-2">
                                            <button class="btn btn-outline-white d-block py-1">Log Out</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <main>
            <div class="container-fluid">
                <div class="container">
                </div>
            </div>
        </main>

        <?php include('components/footer.php') ?>
    </div>

    <script src="assets/js/script.js" type="text/javascript"></script>

    <style>
    .combined-shape {
        min-height: 550px;
        height: auto;
    }

    #my-profile {
        box-shadow: 0 2px 15px 0 rgba(0, 0, 0, 0.5);
        background-color: #fff;
        min-height: 500px;
    }

    #my-profile .company-name {
        font-size: 22px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #378dd7;
        border-bottom: solid 1px #979797;
    }

    #my-profile .txt-title {
        font-size: 18px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 2.09;
        letter-spacing: normal;
        text-align: right;
        color: #000;
    }

    #my-profile .txt-detail mb-2 {
        font-size: 18px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 2.09;
        letter-spacing: normal;
        text-align: left;
        color: #000;
    }

    #my-profile .contact-data-area {
        position: relative;
        background-image: linear-gradient(to bottom, #ffd05c, #fba81d);
    }



    #my-profile .btn-bg-blue {
        box-shadow: 0 4px 12px 0 rgba(0, 0, 0, 0.4);
        background-image: linear-gradient(to left, #68c6f8, #004cb2) !important;
        border: none !important;
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: center;
        color: #fff;
    }

    .contact-data-area .btn-white {
        border-radius: 14px;
        background-color: #fff !important;
        border-color: #fff !important;
    }

    .contact-data-area .title {
        font-size: 22px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #fff;
    }

    .contact-data-area .contact-detail {
        font-size: 16px;
        font-weight: 500;
        font-stretch: normal;
        font-style: normal;
        line-height: 2.23;
        letter-spacing: normal;
        color: #fff;
    }

    .contact-data-area .btn-outline-white {
        border-radius: 14px;
        border: solid 2px #fff;
        font-size: 20px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: center;
        color: #fff;
    }

    .avatar {
        border: solid 5px #fff;
        background-color: #378dd7;
    }

    .avatar img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .contact-btn-area {
        width: 100%;
        position: absolute;
        bottom: 1rem;
    }


    @media (max-width: 767px) {
        #my-profile .txt-title {
            text-align: left;
        }
    }
    </style>

</body>


</html>
