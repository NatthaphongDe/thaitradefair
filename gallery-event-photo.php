<?
$top_menu_active = "gallery";
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
                            Style Bangkok 10-14 MAY 2022 / Gallery / Album / <span class="active">Photo</span>
                        </div>

                        <div class="col-12 col-md-5">
                            <h1 class="title p-0 m-0 text-end text-uppercase">Gallery</h1>
                        </div>
                    </div>

                    <div class="row mt-4 px-4">
                        <div class="col-12 mb-2">
                            <h2 class="title p-0 m-0">Photo Gallery</h2>
                        </div>
                        <div id="gallery" class="col-12 mb-2">
                            <div class="row row-cols-1 row-cols-md-2 gallery-list">
                                <div class="wf-container">
                                    <div class="wf-box">
                                        <img src="assets/images/ex-gallery/ex-photo-1.png">
                                    </div>
                                    <div class="wf-box">
                                        <img src="assets/images/ex-gallery/ex-photo-2.png">
                                    </div>
                                    <div class="wf-box">
                                        <img src="assets/images/ex-gallery/ex-photo-3.png">
                                    </div>
                                    <div class="wf-box">
                                        <img src="assets/images/ex-gallery/ex-photo-4.png">
                                    </div>
                                    <div class="wf-box">
                                        <img src="assets/images/ex-gallery/ex-photo-5.png">
                                    </div>
                                    <div class="wf-box">
                                        <img src="assets/images/ex-gallery/ex-photo-6.png">
                                    </div>
                                    <div class="wf-box">
                                        <img src="assets/images/ex-gallery/ex-photo-7.png">
                                    </div>
                                    <div class="wf-box">
                                        <img src="assets/images/ex-gallery/ex-photo-8.png">
                                    </div>
                                    <div class="wf-box">
                                        <img src="assets/images/ex-gallery/ex-photo-9.png">
                                    </div>
                                    <div class="wf-box">
                                        <img src="assets/images/ex-gallery/ex-photo-10.png">
                                    </div>
                                    <div class="wf-box">
                                        <img src="assets/images/ex-gallery/ex-photo-11.png">
                                    </div>
                                    <div class="wf-box">
                                        <img src="assets/images/ex-gallery/ex-photo-12.png">
                                    </div>
                                    <div class="wf-box">
                                        <img src="assets/images/ex-gallery/ex-photo-13.png">
                                    </div>
                                    <div class="wf-box">
                                        <img src="assets/images/ex-gallery/ex-photo-14.png">
                                    </div>
                                    <div class="wf-box">
                                        <img src="assets/images/ex-gallery/ex-photo-15.png">
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
    <!-- <script src="assets/js/responsive_waterfall.js" type="text/javascript"></script> -->
    <!-- The core Waterfall library -->
    <script src="<?=ROOTPATHDOMAIN?>assets/js/waterfall-light.js"></script>

    <script>
    // use querySelector/querySelectorAll internally
    // var waterfall = new Waterfall({
    //     containerSelector: '.wf-container',
    //     boxSelector: '.wf-box',
    //     minBoxWidth: 250
    // });

    var setting = {
        gap: 10,
        gridWidth: [0, 576, 768],
        refresh: 500
    };
    $(function() {
        $('.wf-container').waterfall(setting);
    })
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

    #gallery .gallery-list>.col {
        min-height: 250px;
        cursor: pointer;
    }


    #gallery .gallery-list .event-area img {
        width: 40px;
        height: 40px;

    }

    #gallery .gallery-list .event-area .event-name {
        font-size: 16px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        color: #fff;
    }

    #gallery .gallery-list .lastest-album {
        font-size: 18px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.27;
        letter-spacing: normal;
        text-align: right;
        color: #fff;
    }

    #gallery .gallery-list .see-all-fair-gallery {
        font-size: 12px;
        font-weight: 600;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: right;
        color: #fff;
        text-decoration: none;
    }

    #gallery .gallery-list .bg-cover {
        background-repeat: no-repeat;
        background-position: center center;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
    }

    .see-all-fair-gallery {
        text-decoration: underline !important;
    }

    .view-more-area .bi {
        color: #111;
    }

    .view-more {
        font-size: 15px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: normal;
        letter-spacing: normal;
        text-align: center;
        color: #111;
    }


    .continer-content-list .card .card-image {
        height: 217px;
        border-radius: 1rem;
        overflow: hidden;
    }

    .continer-content-list .card .card-image img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .continer-content-list .card .card-title {
        height: 55px;
        -webkit-line-clamp: 4;
        -webkit-box-orient: vertical;
        overflow: hidden;
    }

    .continer-content-list .card .card-body h5 {
        font-size: 18px;
        font-weight: bold;
        font-stretch: normal;
        font-style: normal;
        line-height: 1.06;
        letter-spacing: normal;
    }

    .bg-yt-play {
        background: url('assets/images/icon-youtube-play.png') no-repeat center center;
        background-size: 60px auto;
        -moz-background-size: 60px auto;
        -o-background-size: 60px auto;
        background-size: 60px auto;
    }

    .wf-container {
        margin: 0 auto;
    }

    .wf-container:before,
    .wf-container:after {
        content: '';
        display: table;
    }

    .wf-container:after {
        clear: both;
    }

    .wf-box {
        margin: 0px 5px 10px 5px;
    }

    .wf-box img {
        display: block;
        width: 100%;
    }

    .wf-box .content {
        border: 1px solid #ccc;
        border-top-width: 0;
        padding: 5px 8px;
    }

    .wf-column {
        float: left;
    }

    @media screen and (min-width: 576px) {
        .wf-container {
            width: 576px;
        }
    }

    @media screen and (min-width: 768px) {
        .wf-container {
            width: 768px;
        }
    }

    @media screen and (min-width: 992px) {
        .wf-container {
            width: 992px;
        }
    }

    @media screen and (min-width: 1200px) {
        .wf-container {
            width: 1200px;
        }
    }

    @media screen and (min-width: 1400px) {
        .wf-container {
            width: 1400px;
        }
    }
    </style>
</body>


</html>
