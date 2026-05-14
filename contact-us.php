<?
$top_menu_active = "contact-us";
?>

<!doctype html>
<html lang="en">

<head>
    <title>Thailand Trade Fair  | Thailand Exhibition Calendar <?=date("Y")?> - Bangkok Show <?=date("Y")?> - Thai Trade Fair <?=date("Y")?></title>
    <?php include('components/header.php') ?>

    <!-- Custom styles for this template -->
    <link href="assets/css/carousel.css" rel="stylesheet">
</head>

<body class="d-flex flex-column h-100">
    <div id="vue-app" class="p-0 bg-header-1">
        <?php include('components/external_menu.php'); ?>
        <header class="d-flex flex-wrap justify-content-center ">
            <div class="container-fluid">
                <div class="container combined-shape-2 ">
                    <?php include('components/header_menu.php'); ?>
                    <?php include('components/top_slide.php'); ?>
                </div>
            </div>
        </header>

        <main>
            <div class="container-fluid">
                <div class="container">
                    <?php include('components/top_menu.php'); ?>

                    <div class="row mt-4 px-4">
                        <div class="col-12 col-md-7 navigator-text pt-2">
                            Style Bangkok 10-14 MAR 2022 / <span class="active">Contact Us</span>
                        </div>

                        <div class="col-12 col-md-5">
                            <h1 class="title p-0 m-0 text-end text-uppercase">Contact Us</h1>
                        </div>
                    </div>

                    <div class="row row-cols-1 row-cols-md-2 mt-4 px-4">
                        <div class="col map">

                        </div>
                        <div class="col px-4 pt-5 pt-md-0 pt-lg-5">
                            <h4>
                                Data Systems and Planning Group, Office of Digital Commerce
                            </h4>
                            <p class="mb-5">
                                Department of International Trade Promotion, Ministry of Commerce
                            </p>
                            <h4>
                                Office of Lifesyle Product Trade Promotion
                            </h4>
                            <p class="mb-5">
                                Department of International Trade Promotion, Ministry of Commerce.<br />
                                <b>Tel:</b> +66 (0) 2507 8361- 64, 0 2507 8404<br />
                                <b>Fax:</b> +66 (0) 2547 4281<br />
                                <b>E-mail:</b> lifestyleunit@ditp.go.th
                            </p>

                            <div class="d-block text-center">
                                <button class="btn btn-view-map mx-auto px-5">VIEW GOOGLE MAP</button>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5 px-0 px-lg-4 mb-5 justify-content-center">
                        <div class="col-12 col-lg-9 mb-2">
                            <div class="form-group mb-2">
                                <input type="text" class="form-control px-3 py-2" placeholder="Name">
                            </div>
                            <div class="form-group mb-2">
                                <input type="text" class="form-control px-3 py-2" placeholder="E-mail">
                            </div>
                            <div class="form-group mb-2">
                                <textarea class="form-control p-3" placeholder="Messagesss" rows="10"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-block text-center">
                                <button class="btn btn-view-map mx-auto px-5">SEND</button>
                            </div>
                        </div>
                    </div>

                </div>
            </div>

        </main>

        <?php include('components/footer.php') ?>
    </div>
    <script src="assets/js/script.js" type="text/javascript"></script>

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

    h2.title {
        font-size: 26px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        /* text-align: justify; */
        color: #111;
    }

    h4 {
        font-size: 20px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 1;
        letter-spacing: normal;
        color: #111;
    }

    p {
        font-size: 16px;
        font-weight: normal;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
    }

    .map {
        background: #fff;
        min-height: 350px;
        box-shadow: 0 3px 10px 0 rgba(0, 0, 0, 0.3);
    }

    @media (max-width: 991px) {
        .map {
            min-height: 300px;
            height: 300px;
        }
    }

    @media (max-width: 767px) {
        .map {
            min-height: 250px;
        }
    }

    .btn-view-map {
        font-size: 18px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: center;
        color: #fff !important;
        border-radius: 46px;
        box-shadow: 0 4px 12px 0 rgba(0, 0, 0, 0.4);
        background-image: linear-gradient(to left, #68c6f8, #004cb2) !important;
        border: none !important;
    }

    .form-group .form-control {
        font-size: 16px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 1;
        letter-spacing: -0.56px;
        text-align: justify;
        color: #111;
        border-radius: 29px;
        box-shadow: 0 3px 10px 0 rgba(0, 0, 0, 0.3);
        background-color: #fff;

    }

    ::-webkit-input-placeholder {
        /* Chrome, Firefox, Opera, Safari 10.1+ */
        color: #b2b2b2;
        /* Firefox */
    }

    :-moz-placeholder {
        /* Mozilla Firefox 4 to 18 */
        color: #b2b2b2;
    }

    :-ms-input-placeholder {
        /* Internet Explorer 10-11 */
        color: #b2b2b2;
    }

    ::-ms-input-placeholder {
        /* Microsoft Edge */
        color: #b2b2b2;
    }
    </style>

</body>


</html>
