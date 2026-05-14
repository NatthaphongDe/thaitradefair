<?
$top_menu_active = "activities";
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
                            Style Bangkok 10-14 MAR 2022 / Activities / <span class="active">Special Activities</span>
                        </div>

                        <div class="col-12 col-md-5">
                            <h1 class="title p-0 m-0 text-end text-uppercase">Activities</h1>
                        </div>
                    </div>

                    <div class="row mt-4 px-4 mb-5">
                        <div class="col">
                            <div id="activitiesCarousel" class="carousel slide m-0 w-100" data-bs-ride="carousel">
                                <div class="carousel-indicators">
                                    <button type="button" data-bs-target="#activitiesCarousel" data-bs-slide-to="0"
                                        class="active" aria-current="true" aria-label="Slide 1"></button>
                                    <button type="button" data-bs-target="#activitiesCarousel" data-bs-slide-to="1"
                                        aria-label="Slide 2" class=""></button>
                                    <button type="button" data-bs-target="#activitiesCarousel" data-bs-slide-to="2"
                                        aria-label="Slide 3" class=""></button>
                                </div>
                                <div class="carousel-inner">
                                    <div class="carousel-item active">
                                        <img class="bd-placeholder-img w-100"
                                            src="assets/images/ex-activities/pr-for-ditp-website.png" />
                                    </div>
                                    <div class="carousel-item">
                                        <img class="bd-placeholder-img w-100"
                                            src="assets/images/ex-activities/pr-for-ditp-website.png" />
                                    </div>
                                    <div class="carousel-item">
                                        <img class="bd-placeholder-img w-100"
                                            src="assets/images/ex-activities/pr-for-ditp-website.png" />
                                    </div>
                                </div>
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
        text-align: justify;
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


    #activitiesCarousel {
        height: 650px;
    }

    .carousel-indicators [data-bs-target] {
        opacity: 0.4;
        border-radius: 8px !important;
        background-color: #111;
        width: 15px;
        height: 6px;
        border-top: 0px solid transparent;
        border-bottom: 0px solid transparent;
        height: 8px;

    }

    .carousel-indicators .active {
        border-radius: 8px !important;
        opacity: 1;
        width: 25px;
        height: 6px;
        border-top: 0px solid transparent;
        border-bottom: 0px solid transparent;
        background-color: #111;
        height: 8px;
    }

    @media (max-width: 1399px) {

        #activitiesCarousel {
            height: 570px;
        }
    }

    @media (max-width: 1199px) {

        #activitiesCarousel {
            height: 480px;
        }
    }

    @media (max-width: 991px) {

        #activitiesCarousel {
            height: 360px;
        }
    }
    </style>

</body>


</html>
